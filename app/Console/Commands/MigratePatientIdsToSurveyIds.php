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

        // Step 2: Clear patient_id for ALL non-member surveys that DO NOT have appointments
        $this->info('Clearing patient_id for non-member field surveys...');
        $affected = Survey::withTrashed()
            ->where('is_member', false)
            ->doesntHave('appointments') // ONLY clear if they have ZERO clinical appointments
            ->update(['patient_id' => null]);
            
        $this->info("Cleared patient_id from {$affected} field surveys.");
        $this->info("\nMigration completed successfully. The gap filler will now automatically recycle the missing IDs for the next clinic patients.");
    }
}
