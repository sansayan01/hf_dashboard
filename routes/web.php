<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;


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

        // Joining Letter
        Route::get('/{user}/joining-letter', [UserController::class, 'joiningLetter'])->name('joining-letter');

        // Toggle Officer in Charge status
        Route::post('/{user}/toggle-oic', [UserController::class, 'toggleOic'])->name('toggle-oic');

        Route::match(['get', 'post'], '/bulk/print-all', [UserController::class, 'printAllIdCards'])->name('print-all-id-cards');
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

    // Patient Management (using Survey model with 'patient' parameter name)
    Route::bind('patient', function ($value) {
        return \App\Models\Survey::findOrFail($value);
    });

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/export', [PatientController::class, 'export'])->name('export');
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');

        // Membership Management
        Route::get('/{patient}/membership', [App\Http\Controllers\MembershipController::class, 'show'])->name('membership');
        Route::post('/{patient}/membership/register', [App\Http\Controllers\MembershipController::class, 'register'])->name('membership.register');

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

    // Global Appointments (Accessible from Sidebar)
    Route::get('/appointments', [AppointmentController::class, 'all'])->name('appointments.all');
    Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/appointments/{appointment}/report-missed', [AppointmentController::class, 'reportMissed'])->name('appointments.report_missed');
    Route::post('/appointments/{appointment}/confirm-missed', [AppointmentController::class, 'confirmMissed'])->name('appointments.confirm_missed');

    // Temporary Migration Trigger (Delete after use)
    Route::get('/setup-db', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ["--force" => true]);

            // Force seed the new permission if migration didn't pick it up or was already "run"
            $roles = ['office_in_charge', 'dm', 'bm', 'rm', 'ro'];
            $permission = 'can_edit_user_details';

            foreach ($roles as $role) {
                $enabled = ($role === 'office_in_charge');
                \Illuminate\Support\Facades\DB::table('role_permissions')->updateOrInsert(
                    ['role' => $role, 'permission_key' => $permission],
                    ['is_enabled' => $enabled, 'created_at' => now(), 'updated_at' => now()]
                );
            }

            return "Database setup successful! Permissions updated. Go back to <a href='/profile'>Admin Controls</a>";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    // Profile Settings
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/permissions', [App\Http\Controllers\ProfileController::class, 'updatePermissions'])->name('profile.permissions');

    // Attendance Management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::post('/store', [AttendanceController::class, 'store'])->name('store');
        Route::get('/{user}', [AttendanceController::class, 'show'])->name('show');
    });

    // AI Assistant
    Route::post('/ai/chat', [\App\Http\Controllers\AIController::class, 'chat'])->name('ai.chat');

    // Medicine Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Stock management
        Route::get('/', [App\Http\Controllers\InventoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\InventoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\InventoryController::class, 'store'])->name('store');
        Route::get('/transactions', [App\Http\Controllers\InventoryController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/export', [App\Http\Controllers\InventoryController::class, 'exportTransactions'])->name('transactions.export');
        Route::put('/transactions/{transaction}', [App\Http\Controllers\InventoryController::class, 'updateTransaction'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [App\Http\Controllers\InventoryController::class, 'destroyTransaction'])->name('transactions.destroy');

        // Dispensing to patients
        Route::get('/dispense/{patient?}', [App\Http\Controllers\InventoryController::class, 'dispense'])->name('dispense');
        Route::post('/dispense', [App\Http\Controllers\InventoryController::class, 'processDispense'])->name('process-dispense');

        // Stock Transfer
        Route::get('/transfer', [App\Http\Controllers\InventoryController::class, 'transfer'])->name('transfer');
        Route::post('/transfer', [App\Http\Controllers\InventoryController::class, 'processTransfer'])->name('process-transfer');

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
    });

    // Coupon Code Management (Super Admin Only)
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [App\Http\Controllers\CouponCodeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\CouponCodeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CouponCodeController::class, 'store'])->name('store');
        Route::delete('/{coupon}', [App\Http\Controllers\CouponCodeController::class, 'destroy'])->name('destroy');
        Route::get('/export', [App\Http\Controllers\CouponCodeController::class, 'export'])->name('export');
    });

    // Messenger (Disabled)
    // Route::prefix('messenger')->name('messenger.')->group(function () {
    //     Route::get('/', [App\Http\Controllers\MessengerController::class, 'index'])->name('index');
    //     Route::get('/start/{user}', [App\Http\Controllers\MessengerController::class, 'start'])->name('start');
    //     Route::get('/{conversation}', [App\Http\Controllers\MessengerController::class, 'show'])->name('show');
    //     Route::post('/{conversation}/messages', [App\Http\Controllers\MessengerController::class, 'store'])->name('messages.store');
    // });


});

// AJAX Coupon Validation (accessible during registration)
Route::post('/coupons/validate', [App\Http\Controllers\CouponCodeController::class, 'validateAjax'])->name('coupons.validate');



// Cache clearing route
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

// Temporary File Checker Route
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

    // Check directory casing
    $html .= "</ul><h2>Directory Casing Check</h2><ul>";
    $controllerDir = app_path('Http/Controllers');
    if (is_dir($controllerDir)) {
        $contents = scandir($controllerDir);
        foreach ($contents as $item) {
            if (stripos($item, 'MedicineDistributionController') !== false) {
                $html .= "<li>Found in directory: <code>{$item}</code></li>";
            }
        }
    }

    $html .= "</ul>";
    return $html;
});

// Diagnostic route - Moved outside auth for debugging
Route::get('/diag/oic', function () {
    try {
        $out = "<div style='font-family: sans-serif; padding: 20px;'>";
        $out .= "<h1 style='color: #3C50E0;'>System Diagnostic</h1>";

        $out .= "<h2>Office In Charge Accounts</h2>";
        try {
            $oics = \App\Models\User::where('designation', 'office_in_charge')
                ->orWhere('is_office_in_charge', true)
                ->get();
            $out .= "Found " . $oics->count() . " OIC users.<br><br>";
            foreach ($oics as $u) {
                // Ensure helper methods exist or use properties
                $isExpired = method_exists($u, 'isOfficeInChargeExpired') ? ($u->isOfficeInChargeExpired() ? 'Yes' : 'No') : 'N/A';
                $out .= "<b>ID:</b> {$u->id}, <b>EmpID:</b> {$u->employee_id}, <b>Status:</b> {$u->status}, <b>IsOIC:</b> " . ($u->is_office_in_charge ? 'Yes' : 'No') . ", <b>Expired:</b> $isExpired<br>";
            }
        } catch (\Exception $e) {
            $out .= "Error fetching OICs: " . $e->getMessage() . "<br>";
        }

        $out .= "<h2>Session & Cookies</h2>";
        $out .= "<b>Session Driver:</b> " . config('session.driver') . "<br>";
        $out .= "<b>Is Writable:</b> " . (is_writable(storage_path('framework/sessions')) ? "<span style='color: green;'>YES</span>" : "<span style='color: red;'>NO</span>") . "<br>";
        $out .= "<b>Session Secure Cookie:</b> " . (config('session.secure') ? "True" : "False") . "<br>";
        $out .= "<b>Session SameSite:</b> " . config('session.same_site', 'lax') . "<br>";

        $out .= "<h2>URL/Domain Check</h2>";
        $out .= "<b>APP_URL (env):</b> " . env('APP_URL') . "<br>";
        $out .= "<b>Current URL:</b> " . url()->current() . "<br>";
        $out .= "<b>URL Host match:</b> " . (str_contains(env('APP_URL'), request()->getHost()) ? "<span style='color: green;'>MATCH</span>" : "<span style='color: orange;'>MISMATCH</span>") . "<br>";

        $out .= "<h2>Database Connection</h2>";
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $out .= "<span style='color: green;'>Database Connected Successfully</span><br>";
        } catch (\Exception $e) {
            $out .= "<span style='color: red;'>Database Failed: " . $e->getMessage() . "</span><br>";
        }

        $out .= "<h2>PHP Extensions</h2>";
        $extensions = ['pdo_mysql', 'mysqli', 'openssl', 'mbstring', 'gd', 'curl'];
        foreach ($extensions as $ext) {
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

Route::get('/diag/clear', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        return "Caches cleared successfully! Try logging in again.";
    } catch (\Exception $e) {
        return "Error clearing caches: " . $e->getMessage();
    }
});



// Route to Fix Storage Link and provide diagnostic info
Route::get('/fix-storage', function () {
    try {
        $publicPath = public_path();
        $storagePath = storage_path('app/public');
        $details = "";

        // Manual check for common shared hosting public directories
        // Sometimes public_path() returns the project/public but index.php is in public_html
        if (!file_exists($publicPath . '/index.php')) {
            $details .= "index.php not found in public_path(). Checking alternatives...<br>";
            $possiblePaths = [
                base_path('public_html'),
                base_path('../public_html'),
                dirname(base_path()) . '/public_html',
            ];
            foreach ($possiblePaths as $path) {
                if (file_exists($path . '/index.php')) {
                    $publicPath = $path;
                    $details .= "Found Public Root: $path<br>";
                    break;
                }
            }
        }

        $linkPath = $publicPath . '/storage';

        // 1. Remove broken symlink or folder
        if (file_exists($linkPath) || is_link($linkPath)) {
            $details .= "Existing storage link/folder found. Attempting removal...<br>";
            try {
                if (is_link($linkPath)) {
                    unlink($linkPath);
                    $details .= "- Unlinked existing symlink.<br>";
                } elseif (is_dir($linkPath)) {
                    // It's a directory, maybe someone copied it
                    $details .= "- WARNING: Found actual directory at /storage. Symlink might fail unless this is deleted manually.<br>";
                }
            } catch (\Throwable $te) {
                $details .= "- Removal failed: " . $te->getMessage() . "<br>";
            }
        }

        // 2. Try Artisan Link
        $details .= "Attempting artisan storage:link...<br>";
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            $artisanOutput = \Illuminate\Support\Facades\Artisan::output();
            $details .= "Artisan Output: " . trim($artisanOutput ?: "No output") . "<br>";
        } catch (\Throwable $ae) {
            $details .= "Artisan failed: " . $ae->getMessage() . "<br>";
        }

        // 3. Manual symlink if artisan failed or path is different
        if (!file_exists($linkPath) && !is_link($linkPath)) {
            $details .= "Artisan failed to create link at $linkPath. Trying manual symlink...<br>";
            if (function_exists('symlink')) {
                try {
                    @symlink($storagePath, $linkPath);
                    $details .= "Manual symlink attempt finished.<br>";
                } catch (\Throwable $se) {
                    $details .= "Manual internal error: " . $se->getMessage() . "<br>";
                }
            } else {
                $details .= "symlink() function is disabled on this server.<br>";
            }
        }

        $isFixed = (file_exists($linkPath) || is_link($linkPath));
        $linkStatus = $isFixed ? "<span style='color: #22c55e;'>SUCCESS</span>" : "<span style='color: #ef4444;'>FAILED (Using Fallback Bridge)</span>";

        // Find a sample image to test the bridge
        $sampleImage = \App\Models\UserProfile::whereNotNull('profile_picture')->first();
        $bridgeTest = "No images found in database to test.";
        if ($sampleImage) {
            $testUrl = route('storage.bridge', ['path' => $sampleImage->profile_picture]);
            $bridgeTest = "<a href='$testUrl' target='_blank' style='color: #3C50E0; font-weight: bold;'>Click here to test Bridge Image</a><br><small>If you see a picture after clicking, the system is working perfectly!</small>";
        }

        $html = "
        <div style='font-family: sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; color: #334155;'>
            <h1 style='color: #3C50E0;'>Storage System Diagnostic</h1>
            <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 24px;'>
                <p><b>Link Status:</b> $linkStatus</p>
                <p><b>Public Root:</b> $publicPath</p>
                <p><b>Storage Target:</b> $storagePath</p>
                <p><b>Link Location:</b> $linkPath</p>
            </div>

            <div style='background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 16px; padding: 24px; margin-bottom: 24px;'>
                <h3 style='color: #166534; margin-top: 0;'>Bridge Test (Your custom fallback)</h3>
                <p>$bridgeTest</p>
            </div>
            
            <div style='background: #1e293b; color: #94a3b8; border-radius: 16px; padding: 24px; font-family: monospace; font-size: 13px; line-height: 1.6;'>
                <h3 style='color: #fff; margin-top: 0;'>Log:</h3>
                $details
            </div>

            <div style='margin-top: 40px; display: flex; gap: 16px;'>
                <a href='/' style='padding: 12px 24px; background: #3C50E0; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;'>Go to Dashboard</a>
                <a href='/fix-storage' style='padding: 12px 24px; border: 1px solid #3C50E0; color: #3C50E0; text-decoration: none; border-radius: 8px; font-weight: bold;'>Refresh Diagnostic</a>
            </div>
        </div>";

        return $html;
    } catch (\Throwable $e) {
        return "
        <div style='padding: 40px; font-family: sans-serif;'>
            <h1 style='color: #ef4444;'>Diagnostic Crashed</h1>
            <p>Error: " . htmlspecialchars($e->getMessage()) . "</p>
            <p>File: " . $e->getFile() . " on line " . $e->getLine() . "</p>
            <pre style='background: #f1f5f9; padding: 20px; border-radius: 8px; overflow-x: auto;'>" . $e->getTraceAsString() . "</pre>
        </div>";
    }
});

// Fallback "Storage Bridge" with Smart Finder logic
Route::get('/storage-render/{path}', function ($path) {
    // List of possible locations to check for the file
    $possiblePaths = [
        storage_path('app/public/' . $path),
        base_path('storage/app/public/' . $path),
        public_path('storage/' . $path), // Check if it's a real folder in public
        base_path('../storage/app/public/' . $path), // External storage check
    ];

    foreach ($possiblePaths as $fullPath) {
        if (file_exists($fullPath) && !is_dir($fullPath)) {
            return response()->file($fullPath);
        }
    }

    // Still not found? Log detail for user
    $checked = implode("<br>", $possiblePaths);
    return "<h3>File Not Found</h3>
            <p>Searched for: <b>$path</b></p>
            <p><b>Checked Locations:</b><br>$checked</p>
            <p>Please ensure you have uploaded the <code>storage</code> folder to your server.</p>";
})->where('path', '.*')->name('storage.bridge');


// Diagnostic route update to include search
Route::get('/fix-storage', function () {
    try {
        $publicPath = public_path();
        $storagePath = storage_path('app/public');
        $details = "";

        // Manual check for index.php
        if (!file_exists($publicPath . '/index.php')) {
            foreach ([base_path('public_html'), base_path('../public_html')] as $p) {
                if (file_exists($p . '/index.php')) {
                    $publicPath = $p;
                    break;
                }
            }
        }
        $linkPath = $publicPath . '/storage';

        // Attempt link fix (silent)
        try {
            if (is_link($linkPath))
                @unlink($linkPath);
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        } catch (\Throwable $e) {
        }

        $isFixed = (file_exists($linkPath) && is_link($linkPath));
        $linkStatus = $isFixed ? "SUCCESS" : "FAILED (Using Smart Bridge)";

        // SEARCH FOR REAL FILE
        $sample = \App\Models\UserProfile::whereNotNull('profile_picture')->first();
        $searchResult = "No images found in database.";
        if ($sample) {
            $foundAt = "NOT FOUND ANYWHERE";
            $searchPaths = [
                storage_path('app/public/' . $sample->profile_picture),
                base_path('storage/app/public/' . $sample->profile_picture),
                public_path('storage/' . $sample->profile_picture),
            ];
            foreach ($searchPaths as $sp) {
                if (file_exists($sp)) {
                    $foundAt = $sp;
                    break;
                }
            }
            $searchResult = "<b>Searching for:</b> " . $sample->profile_picture . "<br><b>Real Location:</b> " . $foundAt;
        }

        return "
        <div style='font-family: sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; color: #334155;'>
            <h1 style='color: #3C50E0;'>Storage System Diagnostic</h1>
            <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 24px;'>
                <p><b>Link Status:</b> $linkStatus</p>
                <p><b>Public Root:</b> $publicPath</p>
                <p><b>Smart Finder:</b> $searchResult</p>
            </div>
            
            <div style='margin-top: 20px;'>
                <a href='/' style='padding: 12px 24px; background: #3C50E0; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;'>Go to Dashboard</a>
                <a href='" . route('storage.bridge', ['path' => $sample->profile_picture ?? 'test']) . "' target='_blank' style='margin-left: 10px; color: #3C50E0; font-weight: bold;'>Try Viewing Image Again</a>
            </div>
        </div>";
    } catch (\Throwable $e) {
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
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Checked Paths</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">URL</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Preview</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($profiles as $profile) {
        /** @var \App\Models\UserProfile $profile */
        $path = $profile->profile_picture;
        $possiblePaths = [
            'storage_path' => storage_path('app/public/' . $path),
            'app_blade' => resource_path('views/layouts/app.blade.php'),
            'dist_controller' => app_path('Http/Controllers/MedicineDistributionController.php'),
            'base_path' => base_path('storage/app/public/' . $path),
            'public_path' => public_path('storage/' . $path), // Check if it's a real folder in public
            'external' => base_path('../storage/app/public/' . $path), // External storage check
        ];

        $foundPath = null;
        $existsChecks = [];
        foreach ($possiblePaths as $label => $fullPath) {
            $exists = file_exists($fullPath) && !is_dir($fullPath);
            $existsChecks[] = "<b>$label:</b> " . ($exists ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . "<br><small style='color: #666;'>$fullPath</small>";
            if ($exists && !$foundPath) {
                $foundPath = $fullPath;
            }
        }

        $url = $profile->getProfilePictureUrl();
        $urlDisplay = $url ? "<a href='$url' target='_blank' style='color: #3C50E0;'>View</a>" : '<span style="color: red;">NULL</span>';

        $preview = $url ? "<img src='$url' style='width: 50px; height: 50px; object-fit: cover; border-radius: 8px;' onerror='this.style.border=\"2px solid red\"'>" : '❌';

        $html .= '<tr>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($profile->full_name ?? 'N/A') . '<br><small>' . ($profile->user->employee_id ?? 'N/A') . '</small></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><code>' . $path . '</code></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($foundPath ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd; font-size: 11px;">' . implode('<br><br>', $existsChecks) . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . $urlDisplay . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $preview . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Storage directory permissions
    $html .= '<h2>Storage Directory Information</h2>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
    $html .= '<thead><tr style="background: #f1f5f9;">';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Path Type</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Full Path</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Exists?</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Writable?</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Files Count</th>';
    $html .= '</tr></thead><tbody>';

    $storageDirs = [
        'storage_path' => storage_path('app/public/profile_pictures'),
        'base_path' => base_path('storage/app/public/profile_pictures'),
        'public_path' => public_path('storage/profile_pictures'),
        'external' => base_path('../storage/app/public/profile_pictures'),
    ];

    foreach ($storageDirs as $label => $dir) {
        $exists = is_dir($dir);
        $writable = $exists && is_writable($dir);
        $count = 0;
        if ($exists) {
            $files = glob($dir . '/*');
            $count = count(array_filter($files, 'is_file'));
        }

        $html .= '<tr>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><b>' . $label . '</b></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><code>' . $dir . '</code></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($exists ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($writable ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . $count . ' files</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Environment info
    $html .= '<h2>Environment Information</h2>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>APP_ENV</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . env('APP_ENV') . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>APP_URL</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . env('APP_URL') . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Base Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . base_path() . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Storage Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . storage_path() . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Public Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . public_path() . '</td></tr>';
    $html .= '</table>';

    $html .= '<div style="margin-top: 40px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">';
    $html .= '<h3 style="color: #166534; margin-top: 0;">Recommendations</h3>';
    $html .= '<ol style="color: #166534;">';
    $html .= '<li>If files exist locally but not on live server, you need to upload the <code>storage/app/public/profile_pictures</code> folder to your live server.</li>';
    $html .= '<li>Make sure the storage directory has proper write permissions (755 or 775).</li>';
    $html .= '<li>If using FTP/SFTP, ensure you upload the storage folder after each deployment.</li>';
    $html .= '<li>Consider setting up automated deployment or using rsync to sync files.</li>';
    $html .= '</ol>';
    $html .= '</div>';

    $html .= '</div>';

    return $html;
})->middleware('auth');
