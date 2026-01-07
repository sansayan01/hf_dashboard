<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Survey;
use App\Models\Appointment;
use App\Services\GoogleSheetsService;

class SyncToSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sheets {--model=all : The model to sync (users, patients, appointments, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync existing data to Google Sheets';

    protected $sheetsService;

    public function __construct(GoogleSheetsService $sheetsService)
    {
        parent::__construct();
        $this->sheetsService = $sheetsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->option('model');
        $url = env('GOOGLE_SHEET_WEB_APP_URL');

        if (!$url) {
            $this->error('GOOGLE_SHEET_WEB_APP_URL is not set in .env');
            return;
        }

        if ($model === 'all' || $model === 'users') {
            $this->info('Syncing Users...');
            User::all()->each(fn($u) => $this->syncUser($u));
        }

        if ($model === 'all' || $model === 'patients') {
            $this->info('Syncing Patients/Surveys...');
            Survey::all()->each(fn($s) => $this->syncSurvey($s));
        }

        if ($model === 'all' || $model === 'appointments') {
            $this->info('Syncing Appointments...');
            Appointment::all()->each(fn($a) => $this->syncAppointment($a));
        }

        $this->info('Sync completed!');
    }

    protected function syncUser($model)
    {
        $status = $this->sheetsService->syncData('Users', [
            'Action' => 'Initial Sync',
            'employeeId' => $model->employee_id,
            'designation' => $model->getDesignationLabel(),
            'upline' => $model->parent ? ($model->parent->profile?->full_name ?? $model->parent->employee_id) : 'Top Level',
            'fullName' => $model->profile?->full_name ?? 'N/A',
            'profilePicture' => $model->profile?->profile_picture ? url('storage/' . $model->profile->profile_picture) : 'No Image',
            'phone' => $model->profile?->phone_number ?? 'N/A',
            'email' => $model->email,
            'bloodGroup' => $model->profile?->blood_group ?? 'N/A',
            'aadhar' => $model->profile?->aadhaar_number ?? 'N/A',
            'pan' => $model->profile?->pan_number ?? 'N/A',
            'address' => $model->profile?->address ?? 'N/A',
            'state' => $model->profile?->state ?? 'N/A',
            'district' => $model->profile?->district ?? 'N/A',
            'block' => $model->profile?->block ?? 'N/A',
            'gramPanchayat' => $model->profile?->gram_panchayat ?? 'N/A',
            'pinCode' => $model->profile?->pin_code ?? 'N/A',
            'bankName' => $model->bankDetails?->bank_name ?? 'N/A',
            'accountNumber' => $model->bankDetails?->account_number ?? 'N/A',
            'ifsc' => $model->bankDetails?->ifsc_code ?? 'N/A',
        ], 'employeeId', $model->employee_id);

        if ($status) {
            $this->line(" - Synced: {$model->employee_id}");
        } else {
            $this->error(" - Failed: {$model->employee_id}");
        }
    }

    protected function syncSurvey($model)
    {
        $collectorName = $model->creator?->profile?->full_name ?? 'System';
        $collectorId = $model->creator?->employee_id ?? 'N/A';
        $collector = "{$collectorName} ({$collectorId})";

        // Sync to Patients tab
        $status1 = $this->sheetsService->syncData('Patients', [
            'Action' => 'Initial Sync',
            'patientId' => $model->patient_id,
            'fullName' => $model->full_name,
            'phone' => $model->phone_number,
            'aadhar' => $model->aadhar_number,
            'pan' => $model->pan_number,
            'district' => $model->district,
            'block' => $model->block,
            'gp' => $model->gp,
            'address' => $model->address,
            'pin' => $model->pin,
            'collector' => $collector,
        ], 'patientId', $model->patient_id);

        // Sync to Surveys tab
        $status2 = $this->sheetsService->syncData('Surveys', [
            'Action' => 'Initial Sync',
            'patientId' => $model->patient_id,
            'fullName' => $model->full_name,
            'phone' => $model->phone_number,
            'pin' => $model->pin,
            'relativeName' => $model->relative_name,
            'age' => $model->age,
            'gender' => $model->gender,
            'bloodGroup' => $model->blood_group,
            'pastDiseases' => $model->past_diseases,
            'healthIssues' => $model->health_issues,
            'insuranceLoanReq' => $model->insurance_loan_req,
            'landmark' => $model->landmark,
            'collector' => $collector,
        ], 'patientId', $model->patient_id);



        if ($status1 && $status2) {
            $this->line(" - Synced: {$model->patient_id} ({$model->full_name}) to both [Patients] and [Surveys]");
        } else {
            $this->error(" - Failed: {$model->patient_id} ({$model->full_name})");
        }
    }


    protected function syncAppointment($model)
    {
        $status = $this->sheetsService->syncData('Appointments', [
            'Action' => 'Initial Sync',
            'appointmentId' => $model->appointment_id,
            'patientId' => $model->survey?->patient_id ?? 'N/A',
            'patientName' => $model->survey?->full_name ?? 'N/A',
            'doctorType' => $model->doctor_type,
            'location' => $model->location,
            'date' => $model->appointment_date ? \Illuminate\Support\Carbon::parse($model->appointment_date)->format('Y-m-d') : 'N/A',
            'time' => $model->appointment_time,
            'status' => $model->status,
        ], 'appointmentId', $model->appointment_id);

        if ($status) {
            $this->line(" - Synced: {$model->appointment_id}");
        } else {
            $this->error(" - Failed: {$model->appointment_id}");
        }
    }
}


