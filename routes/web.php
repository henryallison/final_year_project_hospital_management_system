<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AdminAccessController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AppointmentController;
/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/

// Test Route (Safe to remove for production)
Route::get('/test', function () {
    return response()->json([
        'status' => 'working',
        'database' => config('database.default'),
        'time' => now(),
        'admin_code_configured' => !empty(config('app.admin_access_code'))
    ]);
});

// Environment Check Route (for debugging)
Route::get('/env-check', function() {
    return response()->json([
        'env_value' => env('ADMIN_ACCESS_CODE'),
        'config_value' => config('app.admin_access_code'),
        'server_value' => $_SERVER['ADMIN_ACCESS_CODE'] ?? 'Not set',
        'loaded_from' => 'env'
    ]);
});

// Auth Routes (Handles login, registration, etc.)
Auth::routes(['register' => true]);

// Password Reset Routes - Updated and simplified
Route::prefix('password')->name('password.')->group(function() {
    // Request reset link
    Route::get('/reset', [PasswordResetController::class, 'showRequestForm'])
        ->name('request');

    // Send reset code
    Route::post('/email', [PasswordResetController::class, 'sendResetCode'])
        ->name('email');

    // Code verification form
    Route::get('/code-verify', [PasswordResetController::class, 'showVerifyCodeForm'])
        ->name('code.verify');

    // Process code verification
    Route::post('/code-verify', [PasswordResetController::class, 'verifyCode'])
        ->name('code.verify.submit');

    // Show reset form (with token)
    Route::get('/reset/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('reset');

    // Process password reset
    Route::post('/reset', [PasswordResetController::class, 'resetPassword'])
        ->name('update');
});

// Admin code verification
Route::post('/verify-admin-code', [AdminAccessController::class, 'verifyCode'])
    ->name('admin.verify-code');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Home Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index']);

    // Patient Management
    Route::resource('patients', PatientController::class);

    // Custom patient routes
    Route::prefix('patients')->group(function () {
        Route::get('/{patient}/history', [PatientController::class, 'showHistory'])
            ->name('patients.history');
        Route::post('/{patient}/record', [PatientController::class, 'storeRecord'])
            ->name('patients.record.store');
    });

    // Medication Management
    Route::resource('medications', MedicationController::class);

    // Admin-only section
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])
            ->name('admin.dashboard');

        // User Management Routes
        Route::prefix('admin/users')->group(function() {
            Route::get('/', [UserManagementController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('admin.users.create');
            Route::post('/', [UserManagementController::class, 'store'])->name('admin.users.store');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
        });

        // Legacy UserController routes
        Route::resource('users', UserController::class);
    });
});
// Log routes (public but with controller-level protection)
Route::prefix('admin')->group(function() {
    Route::get('/logs', [LogController::class, 'index'])
        ->name('admin.logs.index');
    Route::post('/logs/clear', [LogController::class, 'clear'])
        ->name('admin.logs.clear');
});
// Recent Patients Route
Route::get('/patients/recent', [PatientController::class, 'recentPatients'])
    ->name('patients.recent');

// Reports & Doctors
Route::prefix('reports')->group(function () {
    Route::get('/generate', [ReportController::class, 'index'])->name('reports.generate');
    Route::get('/staff', [ReportController::class, 'staff'])->name('reports.staff');
    Route::get('/patients', [ReportController::class, 'patients'])->name('reports.patients');
    Route::get('/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/medications', [ReportController::class, 'medications'])->name('reports.medications');
});

Route::resource('doctors', DoctorController::class);

// Optional Public Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

// Add this inside your auth middleware group (right after the medication routes)
Route::resource('appointments', AppointmentController::class);
Route::prefix('appointments')->group(function () {
    Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
});
// Add these new profile routes (right after the above)
Route::prefix('profile')->group(function() {
    Route::get('/', [AppointmentController::class, 'showProfile'])->name('profile.show');
    Route::get('/edit', [AppointmentController::class, 'editProfile'])->name('profile.edit');
    Route::put('/', [AppointmentController::class, 'updateProfile'])->name('profile.update');
});
Route::resource('tasks', 'App\Http\Controllers\TaskController');

// Debug Route for Admin Code
Route::get('/debug-admin-code', function() {
    return response()->json([
        'env_code' => env('ADMIN_ACCESS_CODE'),
        'config_code' => config('app.admin_access_code'),
        'valid_length' => is_string(config('app.admin_access_code'))
            ? strlen(config('app.admin_access_code'))
            : 'invalid',
        'is_digits' => is_string(config('app.admin_access_code'))
            ? preg_match('/^\d{10}$/', config('app.admin_access_code'))
            : false,
        'all_config' => config('app')
    ]);
});

// Fallback route for 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
