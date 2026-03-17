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

        // Step 0: Cleanup Trashed IDs that don't have TRASH_ prefix
        $this->info('Cleaning up legacy deleted IDs to free up gaps...');
        $trashed = Survey::onlyTrashed()
            ->whereNotNull('patient_id')
            ->where('patient_id', 'not like', 'TRASH_%')
            ->get();

        if ($trashed->count() > 0) {
            $bar = $this->output->createProgressBar($trashed->count());
            $bar->start();
            foreach ($trashed as $t) {
                $t->patient_id = 'TRASH_' . $t->patient_id . '_' . $t->id;
                $t->timestamps = false;
                $t->save();
                $bar->advance();
            }
            $bar->finish();
            $this->info("\nRenamed {$trashed->count()} legacy deleted IDs.");
        } else {
            $this->info("No legacy deleted IDs found.");
        }

        // Step 1: Copy patient_id to survey_id (replace HFP/HFPM with HFS)
        $this->info('Copying patient_id to survey_id... this may take a moment.');
        Survey::withTrashed()->whereNull('survey_id')->chunk(500, function ($surveys) {
            foreach ($surveys as $survey) {
                if ($survey->patient_id) {
                    $newId = preg_replace('/^(HFPM|HFP)/', 'HFS', $survey->patient_id);
                    
                    // Check if this survey_id already exists to avoid unique constraint errors
                    $exists = Survey::withTrashed()->where('survey_id', $newId)->exists();
                    if (!$exists) {
                        $survey->survey_id = $newId;
                        $survey->timestamps = false;
                        $survey->save();
                    }
                }
            }
        });

        // Step 2: Clear patient_id for ALL non-member surveys that DO NOT have appointments
        $this->info('Clearing patient_id for non-member field surveys...');
        $affected = Survey::withTrashed()
            ->where('is_member', false)
            ->doesntHave('appointments')
            ->doesntHave('medicineDistributions') // NEW
            ->doesntHave('pathologyTests')        // NEW
            ->update(['patient_id' => null]);
            
        $this->info("Cleared patient_id from {$affected} field surveys.");
        $this->info("\nMigration completed successfully. The gap filler will now automatically recycle the missing IDs for the next clinic patients.");
    }
}
