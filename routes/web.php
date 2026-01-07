<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'hierarchy.access'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hierarchy-tree', [DashboardController::class, 'getHierarchyTree'])->name('hierarchy.tree');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Approval
        Route::post('/{user}/approve', [UserController::class, 'approve'])->name('approve');

        // BIN / Trash System
        Route::get('/trash/bin', [UserController::class, 'bin'])->name('bin');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [UserController::class, 'forceDelete'])->name('force-delete');
    });

    // Survey Management
    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('/', [App\Http\Controllers\SurveyController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SurveyController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SurveyController::class, 'store'])->name('store');
        Route::get('/{survey}/edit', [App\Http\Controllers\SurveyController::class, 'edit'])->name('edit');
        Route::put('/{survey}', [App\Http\Controllers\SurveyController::class, 'update'])->name('update');
    });

    // Patient Management (using Survey model with 'patient' parameter name)
    Route::bind('patient', function ($value) {
        return \App\Models\Survey::findOrFail($value);
    });

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('destroy');

        // BIN / Trash System
        Route::get('/trash/bin', [PatientController::class, 'bin'])->name('bin');
        Route::post('/{id}/restore', [PatientController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [PatientController::class, 'forceDelete'])->name('force-delete');

        // Appointments
        Route::get('/{patient}/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/{patient}/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/{patient}/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Membership Management
    Route::get('/membership', [App\Http\Controllers\MembershipController::class, 'index'])->name('membership.index');
    Route::get('/patients/{patient}/membership', [App\Http\Controllers\MembershipController::class, 'show'])->name('patients.membership');

    // Global Appointments (Accessible from Sidebar)
    Route::get('/appointments', [AppointmentController::class, 'all'])->name('appointments.all');
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/appointments/{appointment}/report-missed', [AppointmentController::class, 'reportMissed'])->name('appointments.report_missed');
    Route::post('/appointments/{appointment}/confirm-missed', [AppointmentController::class, 'confirmMissed'])->name('appointments.confirm_missed');

    // Temporary Migration Trigger (Delete after use)
    Route::get('/setup-db', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ["--force" => true]);
            return "Database setup successful! Go back to <a href='/patients'>Patients</a>";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    // Profile Settings
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Future Office-In-Charge routes can go here
});

// Temporary Route to Fix Storage Link (Run once then delete)
Route::get('/fix-storage', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage Linked Successfully! <a href="/login">Go to Login</a>';
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
