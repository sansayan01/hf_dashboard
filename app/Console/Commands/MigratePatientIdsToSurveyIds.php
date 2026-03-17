<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class MigratePatientIdsToSurveyIds extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:migrate-patient-ids-to-survey-ids';

    /**
     * The console command description.
     */
    protected $description = 'Migrate HFP and HFPM patient IDs to HFS survey IDs, and regenerate gapless patient IDs for those with appointments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of patient IDs to survey IDs...');

        // Step 1: Copy patient_id to survey_id (replace HFP/HFPM with HFS)
        $this->info('Copying patient_id to survey_id... this may take a moment.');
        Survey::withTrashed()->chunk(500, function ($surveys) {
            foreach ($surveys as $survey) {
                if ($survey->patient_id) {
                    $newId = str_replace(['HFPM', 'HFP'], 'HFS', $survey->patient_id);
                    $survey->survey_id = $newId;
                    $survey->timestamps = false;
                    $survey->save();
                }
            }
        });

        // Step 2: Clear patient_id for ALL non-member surveys
        $this->info('Clearing patient_id for non-member surveys...');
        Survey::withTrashed()->where('is_member', false)->update(['patient_id' => null]);
        
        // Step 3: Regenerate patient_id for those with appointments, ordered by creation date to maintain historical sequence
        $this->info('Regenerating patient_id for actual clinical patients...');
        $clinicalPatients = Survey::withTrashed()
            ->where('is_member', false)
            ->has('appointments')
            ->orderBy('created_at')
            ->get();

        $seq = 1;
        $bar = $this->output->createProgressBar(count($clinicalPatients));
        $bar->start();

        foreach ($clinicalPatients as $patient) {
            $patient->patient_id = 'HFP' . str_pad($seq, 7, '0', STR_PAD_LEFT);
            $patient->timestamps = false;
            $patient->save();
            $seq++;
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nMigration completed successfully. Next patient ID will be: HFP" . str_pad($seq, 7, '0', STR_PAD_LEFT));
    }
}
