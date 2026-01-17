<?php

namespace App\Observers;

use App\Services\GoogleSheetsService;
use App\Models\User;
use App\Models\Survey;
use App\Models\Appointment;

class GoogleSheetSyncObserver
{
    protected $sheetsService;

    public function __construct(GoogleSheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    public function created($model)
    {
        $this->sync($model, 'Created');
    }

    public function updated($model)
    {
        $this->sync($model, 'Updated');
    }

    protected function sync($model, $action)
    {
        try {
            $sheetName = 'General';
            $data = [];
            $uniqueKey = null;
            $uniqueValue = null;

            if ($model instanceof User) {
                $sheetName = 'Users';
                $uniqueKey = 'employeeId';
                $uniqueValue = $model->employee_id;
                $data = [
                    'Action' => $action,
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
                ];
            } elseif ($model instanceof Survey) {
                $collectorName = $model->creator?->profile?->full_name ?? 'System';
                $collectorId = $model->creator?->employee_id ?? 'N/A';
                $collector = "{$collectorName} ({$collectorId})";

                // Sync to PATIENTS sheet (Basic Info)
                $this->sheetsService->syncData('Patients', [
                    'Action' => $action,
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

                // Sync to SURVEYS sheet (Detailed Health Info)
                $this->sheetsService->syncData('Surveys', [
                    'Action' => $action,
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


                return; // Since we handled the sync call inside this block

            } elseif ($model instanceof Appointment) {

                $sheetName = 'Appointments';
                $uniqueKey = 'appointmentId';
                $uniqueValue = $model->appointment_id;
                $data = [
                    'Action' => $action,
                    'appointmentId' => $model->appointment_id,
                    'patientId' => $model->survey?->patient_id ?? 'N/A',
                    'patientName' => $model->survey?->full_name ?? 'N/A',
                    'doctorType' => $model->doctor_type,
                    'location' => $model->location,
                    'date' => $model->appointment_date ? \Illuminate\Support\Carbon::parse($model->appointment_date)->format('Y-m-d') : 'N/A',
                    'time' => $model->appointment_time,
                    'status' => $model->status,
                ];
            }

            $this->sheetsService->syncData($sheetName, $data, $uniqueKey, $uniqueValue);
        } catch (\Exception $e) {
            \Log::error('Google Sheets Sync Failed', [
                'error' => $e->getMessage(),
                'model' => get_class($model),
                'id' => $model->id ?? 'N/A'
            ]);
        }
    }
}

