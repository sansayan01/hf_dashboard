<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IncentiveConfigController;
use Illuminate\Support\Facades\Route;

// Public Member Verification
Route::get('/verify/member/{id}', [App\Http\Controllers\MemberVerificationController::class, 'show'])->name('verify.member');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset Routes
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth', 'hierarchy.access'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/clear', [DashboardController::class, 'clearContext'])->name('dashboard.clear');
    Route::get('/hierarchy-tree', [DashboardController::class, 'getHierarchyTree'])->name('hierarchy.tree');
    Route::get('/hierarchy-children/{user}', [DashboardController::class, 'getTreeChildren'])->name('hierarchy.children');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/staffs', [UserController::class, 'staffIndex'])->name('staffIndex');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Approval
        Route::post('/{user}/approve', [UserController::class, 'approve'])->name('approve');
        Route::post('/bulk-approve', [UserController::class, 'bulkApprove'])->name('bulk-approve');

        // BIN / Trash System
        Route::get('/trash/bin', [UserController::class, 'bin'])->name('bin');
        Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [UserController::class, 'forceDelete'])->name('force-delete');

        // ID Card
        Route::get('/{user}/id-card', [UserController::class, 'idCard'])->name('id-card');

        // Joining Letter & Offer Letter
        Route::get('/{user}/joining-letter', [UserController::class, 'joiningLetter'])->name('joining-letter');
        Route::post('/{user}/upload-signed-letter', [UserController::class, 'uploadSignedOfferLetter'])->name('upload-signed-letter');
        Route::match(['get', 'post'], '/bulk-print-selection', [UserController::class, 'bulkPrintSelection'])->name('bulk-print-selection');
        Route::match(['get', 'post'], '/bulk-offer-letters', [UserController::class, 'bulkOfferLetters'])->name('bulk-offer-letters');
        Route::match(['get', 'post'], '/printable-offer-letters', [UserController::class, 'printableOfferLetters'])->name('printable-offer-letters');

        // Toggle Officer in Charge status
        Route::post('/{user}/toggle-oic', [UserController::class, 'toggleOic'])->name('toggle-oic');

        // Toggle Salary Mode (TAB \u2194 DAB)
        Route::post('/{user}/toggle-salary-mode', [UserController::class, 'toggleSalaryMode'])->name('toggle-salary-mode');

        Route::match(['get', 'post'], '/bulk/print-all', [UserController::class, 'printAllIdCards'])->name('print-all-id-cards');

        // Real-time Uniqueness Check
        Route::post('/check-uniqueness', [UserController::class, 'checkUniqueness'])->name('check-uniqueness');

        // Next Available ID
        Route::get('/next-id', [UserController::class, 'getNextId'])->name('next-id');
    });

    // Survey Management
    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('/', [App\Http\Controllers\SurveyController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SurveyController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SurveyController::class, 'store'])->name('store');
        Route::get('/{survey}/edit', [App\Http\Controllers\SurveyController::class, 'edit'])->name('edit');
        Route::put('/{survey}', [App\Http\Controllers\SurveyController::class, 'update'])->name('update');
        Route::delete('/{survey}', [App\Http\Controllers\SurveyController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\SurveyController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    // Patient Management
    Route::bind('patient', function ($value) {
        return \App\Models\Survey::findOrFail($value);
    });

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/export', [PatientController::class, 'export'])->name('export');
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::post('/check-uniqueness', [PatientController::class, 'checkUniqueness'])->name('check-uniqueness');

        // Membership Management
        Route::get('/{patient}/membership', [App\Http\Controllers\MembershipController::class, 'show'])->name('membership');
        Route::post('/{patient}/membership/register', [App\Http\Controllers\MembershipController::class, 'register'])->name('membership.register');
        Route::post('/{patient}/membership/cancel', [App\Http\Controllers\MembershipController::class, 'cancel'])->name('membership.cancel');

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
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // Membership Registry
    Route::get('/membership', [App\Http\Controllers\MembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/{patient}/card', [App\Http\Controllers\MembershipCardController::class, 'download'])->name('membership.card.download');
    Route::get('/membership/{patient}/card/preview', [App\Http\Controllers\MembershipCardController::class, 'stream'])->name('membership.card.preview');

    // Global Appointments (Accessible from Sidebar)
    Route::get('/appointments/export', [AppointmentController::class, 'export'])->name('appointments.export');
    Route::get('/appointments', [AppointmentController::class, 'all'])->name('appointments.all');
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/appointments/{appointment}/report-missed', [AppointmentController::class, 'reportMissed'])->name('appointments.report_missed');
    Route::post('/appointments/{appointment}/confirm-missed', [AppointmentController::class, 'confirmMissed'])->name('appointments.confirm_missed');

    // Admin Control Panel (Super Admin Only)
    Route::get('/admin/control-panel', [App\Http\Controllers\AdminPanelController::class, 'index'])->name('admin.control-panel');

    // Profile Settings
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/permissions', [App\Http\Controllers\ProfileController::class, 'updatePermissions'])->name('profile.permissions');

    // Attendance Management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/mark', [AttendanceController::class, 'index'])->name('mark');
        Route::post('/store', [AttendanceController::class, 'store'])->name('store');
        Route::get('/my-dashboard', [AttendanceController::class, 'roDashboard'])->name('dashboard');
        Route::get('/reports', [AttendanceController::class, 'report'])->name('reports');
        Route::get('/export/report', [AttendanceController::class, 'exportReport'])->name('export-report');
        Route::get('/{user}', [AttendanceController::class, 'show'])->name('show');
    });

    // Incentive Configuration (Admin Only)
    Route::middleware(['auth'])->prefix('admin/incentive-configs')->name('admin.incentive-configs.')->group(function () {
        Route::get('/', [IncentiveConfigController::class, 'index'])->name('index');
        Route::post('/store-ta', [IncentiveConfigController::class, 'storeTa'])->name('store-ta');
        Route::post('/store-da', [IncentiveConfigController::class, 'storeDa'])->name('store-da');
        Route::post('/sync', [IncentiveConfigController::class, 'syncAttendances'])->name('sync');
        Route::delete('/{incentiveConfig}', [IncentiveConfigController::class, 'destroy'])->name('destroy');
    });

    // AI Assistant
    Route::post('/ai/chat', [\App\Http\Controllers\AIController::class, 'chat'])->name('ai.chat');

    // Chatbot Training (Super Admin only)
    Route::get('/ai/training', [\App\Http\Controllers\AIController::class, 'trainingIndex'])->name('ai.training.index');
    Route::post('/ai/training', [\App\Http\Controllers\AIController::class, 'trainingStore'])->name('ai.training.store');
    Route::put('/ai/training/{id}', [\App\Http\Controllers\AIController::class, 'trainingUpdate'])->name('ai.training.update');
    Route::delete('/ai/training/{id}', [\App\Http\Controllers\AIController::class, 'trainingDestroy'])->name('ai.training.destroy');

    // Medicine Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Stock management
        Route::get('/', [App\Http\Controllers\InventoryController::class, 'index'])->name('index');
        Route::get('/export-batch', [App\Http\Controllers\InventoryController::class, 'exportBatchInventory'])->name('export-batch');
        Route::get('/create', [App\Http\Controllers\InventoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\InventoryController::class, 'store'])->name('store');
        Route::get('/transactions', [App\Http\Controllers\InventoryController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/export', [App\Http\Controllers\InventoryController::class, 'exportTransactions'])->name('transactions.export');
        Route::put('/transactions/{transaction}', [App\Http\Controllers\InventoryController::class, 'updateTransaction'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [App\Http\Controllers\InventoryController::class, 'destroyTransaction'])->name('transactions.destroy');
        Route::post('/transactions/pay/{id}', [App\Http\Controllers\InventoryController::class, 'clearDue'])->name('dispense.pay');

        // Dispensing to patients
        Route::get('/dispense/{patient?}', [App\Http\Controllers\InventoryController::class, 'dispense'])->name('dispense');
        Route::post('/dispense', [App\Http\Controllers\InventoryController::class, 'processDispense'])->name('process-dispense');

        // Stock Transfer
        Route::get('/transfer', [App\Http\Controllers\InventoryController::class, 'transfer'])->name('transfer');
        Route::post('/transfer', [App\Http\Controllers\InventoryController::class, 'processTransfer'])->name('process-transfer');

        // Stock Adjustment
        Route::get('/stocks/{stock}/adjust', [App\Http\Controllers\InventoryController::class, 'adjust'])->name('adjust');
        Route::post('/stocks/{stock}/adjust', [App\Http\Controllers\InventoryController::class, 'processAdjust'])->name('process-adjust');

        // Medicine CRUD
        Route::prefix('medicines')->name('medicines.')->group(function () {
            Route::get('/', [App\Http\Controllers\MedicineController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\MedicineController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\MedicineController::class, 'store'])->name('store');
            Route::get('/{medicine}/edit', [App\Http\Controllers\MedicineController::class, 'edit'])->name('edit');
            Route::put('/{medicine}', [App\Http\Controllers\MedicineController::class, 'update'])->name('update');
            Route::delete('/{medicine}', [App\Http\Controllers\MedicineController::class, 'destroy'])->name('destroy');
        });

        // Warehouse, Camp and Sponsor management
        Route::resource('warehouses', App\Http\Controllers\InventoryWarehouseController::class)->except(['create', 'show', 'edit']);
        Route::resource('camps', App\Http\Controllers\InventoryCampController::class)->except(['create', 'show', 'edit']);
        Route::resource('sponsors', App\Http\Controllers\InventorySponsorController::class)->except(['create', 'show', 'edit']);

        // Category CRUD
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [App\Http\Controllers\MedicineController::class, 'categoriesIndex'])->name('index');
            Route::post('/', [App\Http\Controllers\MedicineController::class, 'categoriesStore'])->name('store');
            Route::put('/{category}', [App\Http\Controllers\MedicineController::class, 'categoriesUpdate'])->name('update');
            Route::delete('/{category}', [App\Http\Controllers\MedicineController::class, 'categoriesDestroy'])->name('destroy');
        });
    });

    // Medicine Distribution
    Route::prefix('medicine')->name('medicine.')->group(function () {
        Route::get('/distribute/{patient}', [App\Http\Controllers\MedicineDistributionController::class, 'create'])->name('distribute');
        Route::get('/search', [App\Http\Controllers\MedicineDistributionController::class, 'searchMedicine'])->name('search');
        Route::post('/distribute', [App\Http\Controllers\MedicineDistributionController::class, 'store'])->name('distribute.store');
        Route::get('/invoice/{id}', [App\Http\Controllers\MedicineDistributionController::class, 'show'])->name('invoice');
        Route::get('/distribution/{id}/edit', [App\Http\Controllers\MedicineDistributionController::class, 'edit'])->name('distribution.edit');
        Route::put('/distribution/{id}', [App\Http\Controllers\MedicineDistributionController::class, 'update'])->name('distribution.update');
        Route::delete('/distribution/{id}', [App\Http\Controllers\MedicineDistributionController::class, 'destroy'])->name('distribution.destroy');
    });

    // Pathology Tests
    Route::prefix('pathology')->name('pathology.')->group(function () {
        Route::get('/create/{patient}', [App\Http\Controllers\PathologyTestController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\PathologyTestController::class, 'store'])->name('store');
        Route::delete('/{pathologyTest}', [App\Http\Controllers\PathologyTestController::class, 'destroy'])->name('destroy');
    });

    // Finances Hub
    Route::get('finances', [App\Http\Controllers\FinancesController::class, 'index'])->name('finances.index');
    Route::get('camp_records/export', [App\Http\Controllers\CampRecordController::class, 'export'])->name('camp_records.export');
    Route::get('camp_records/{camp_record}/pdf', [App\Http\Controllers\CampRecordController::class, 'exportPdf'])->name('camp_records.pdf');
    Route::resource('camp_records', App\Http\Controllers\CampRecordController::class);

    // Coupon Code Management (Super Admin Only)
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [App\Http\Controllers\CouponCodeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\CouponCodeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CouponCodeController::class, 'store'])->name('store');
        Route::delete('/{coupon}', [App\Http\Controllers\CouponCodeController::class, 'destroy'])->name('destroy');
        Route::get('/export', [App\Http\Controllers\CouponCodeController::class, 'export'])->name('export');
    });
});

// AJAX Coupon Validation (accessible during registration)
Route::post('/coupons/validate', [App\Http\Controllers\CouponCodeController::class, 'validateAjax'])->name('coupons.validate');

// Public Database Setup / Cache Clear
Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ["--force" => true]);
        return "Database setup successful! <a href='/'>Go to Dashboard</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/clear-all-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return "<h1 style='color:green'>All caches cleared successfully!</h1><p><a href='/'>Go to Dashboard</a></p>";
    } catch (\Exception $e) {
        return "<h1 style='color:red'>Error clearing caches:</h1><p>" . $e->getMessage() . "</p>";
    }
});

// Diagnostic routes group
Route::middleware(['auth'])->group(function () {
    Route::get('/diag/file-check', function () {
        $files = [
            'Controller' => app_path('Http/Controllers/MedicineDistributionController.php'),
            'View Distribute' => resource_path('views/medicine/distribute.blade.php'),
            'View Invoice' => resource_path('views/medicine/invoice.blade.php'),
        ];

        $html = "<h1>File Existence Checker</h1><ul>";
        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $color = $exists ? 'green' : 'red';
            $status = $exists ? 'FOUND' : 'MISSING';
            $html .= "<li><strong>{$name}:</strong> <span style='color: {$color}'>{$status}</span><br><small>{$path}</small></li>";
        }
        $html .= "</ul>";
        return $html;
    });

    Route::get('/diag/oic', function () {
        try {
            $out = "<div style='font-family: sans-serif; padding: 20px;'>";
            $out .= "<h1 style='color: #3C50E0;'>System Diagnostic</h1>";
            $out .= "<h2>Database Connection</h2>";
            try {
                \DB::connection()->getPdo();
                $out .= "<span style='color: green;'>Database Connected Successfully</span><br>";
            } catch (\Exception $e) {
                $out .= "<span style='color: red;'>Database Failed: " . $e->getMessage() . "</span><br>";
            }

            $out .= "<h2>PHP Extensions</h2>";
            foreach (['pdo_mysql', 'mysqli', 'openssl', 'mbstring', 'gd', 'curl'] as $ext) {
                $status = extension_loaded($ext) ? "<span style='color: green;'>LOADED</span>" : "<span style='color: red;'>MISSING</span>";
                $out .= "<b>$ext:</b> $status<br>";
            }

            $out .= "<h2>Accounts Summary</h2>";
            try {
                $counts = \App\Models\User::select('designation', \DB::raw('count(*) as total'))
                    ->groupBy('designation')
                    ->get();
                foreach ($counts as $c) {
                    $out .= "<b>{$c->designation}:</b> {$c->total}<br>";
                }
            } catch (\Exception $e) {
                $out .= "Error counting: " . $e->getMessage();
            }
            $out .= "</div>";
            return $out;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    // Profile Picture Diagnostic Route
    Route::get('/diag/profile-pictures', function () {
        $html = '<div style="font-family: sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto;">';
        $html .= '<h1 style="color: #3C50E0;">Profile Picture Diagnostic</h1>';

        // Get recent profiles with pictures
        $profiles = \App\Models\UserProfile::whereNotNull('profile_picture')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $html .= '<h2>Recent Profiles with Pictures (Last 10)</h2>';
        $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $html .= '<thead><tr style="background: #f1f5f9;">';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">User</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">DB Path</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">File Exists?</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">URL</th>';
        $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Preview</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($profiles as $profile) {
            /** @var \App\Models\UserProfile $profile */
            $path = $profile->profile_picture;
            $fullPath = storage_path('app/public/' . $path);
            $exists = file_exists($fullPath) && !is_dir($fullPath);
            $url = $profile->getProfilePictureUrl();
            $preview = $url ? "<img src='$url' style='width: 50px; height: 50px; object-fit: cover; border-radius: 8px;' onerror='this.src=\"https://via.placeholder.com/50\"'>" : '❌';

            $html .= '<tr>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($profile->full_name ?? 'N/A') . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><code>' . $path . '</code></td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($exists ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><a href="' . $url . '" target="_blank">Link</a></td>';
            $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $preview . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';
        return $html;
    });

    // Run training migration (one-time)
    Route::get('/run-training-migration', function () {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('chatbot_training_data')) {
                return '<h2 style="color:green;">✅ Table already exists!</h2>';
            }
            \Illuminate\Support\Facades\Schema::create('chatbot_training_data', function ($table) {
                $table->id();
                $table->text('question');
                $table->text('answer');
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
            return '<h2 style="color:green;">✅ Table created successfully!</h2>';
        } catch (\Exception $e) {
            return '<h2 style="color:red;">❌ Error: ' . $e->getMessage() . '</h2>';
        }
    });

    // Fix Storage Link
    Route::get('/fix-storage', function () {
        try {
            if (file_exists(public_path('storage'))) {
                @unlink(public_path('storage'));
            }
            \Artisan::call('storage:link');
            return "Storage link fixed! <a href='/'>Go Home</a>";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });
});

// Fallback "Storage Bridge"
Route::get('/storage-render/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (file_exists($fullPath) && !is_dir($fullPath)) {
        return response()->file($fullPath, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
    return abort(404);
})->where('path', '.*')->name('storage.bridge');
