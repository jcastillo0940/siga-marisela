<?php
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\RoleApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CertificateTemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes
Route::post('/auth/login', [AuthApiController::class, 'login'])->name('api.login');

// Verificación pública de certificados (sin autenticación)
Route::post('certificates/verify', [CertificateController::class, 'verify'])
    ->name('api.certificates.verify-public');

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth Info
    Route::post('/auth/logout', [AuthApiController::class, 'logout'])->name('api.logout');
    Route::get('/auth/me', [AuthApiController::class, 'me'])->name('api.me');
    
    // Users API 
    Route::apiResource('users', UserApiController::class)->names([
        'index'   => 'api.users.index',
        'store'   => 'api.users.store',
        'show'    => 'api.users.show',
        'update'  => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);
    
    Route::patch('users/{user}/toggle-status', [UserApiController::class, 'toggleStatus'])
        ->name('api.users.toggle-status');
    
    // Roles API
    Route::apiResource('roles', RoleApiController::class)->names([
        'index'   => 'api.roles.index',
        'store'   => 'api.roles.store',
        'show'    => 'api.roles.show',
        'update'  => 'api.roles.update',
        'destroy' => 'api.roles.destroy',
    ]);
    
    // Kommo Webhook
    Route::post('/kommo/webhook', [App\Http\Controllers\Api\KommoWebhookController::class, 'handle'])
        ->name('api.kommo.webhook');

    // ===== ASISTENCIA =====
    Route::prefix('attendances')->name('api.attendances.')->group(function () {
        Route::get('session/{session}', [AttendanceController::class, 'index'])->name('index');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::post('session/{session}/bulk', [AttendanceController::class, 'bulkStore'])->name('bulk-store');
        Route::post('check-in-qr', [AttendanceController::class, 'checkInQR'])->name('check-in-qr');
        Route::post('session/{session}/mark-absent', [AttendanceController::class, 'markAbsent'])->name('mark-absent');
        Route::put('{attendance}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('enrollment/{enrollment}/stats', [AttendanceController::class, 'studentStats'])->name('student-stats');
        Route::get('course/{course}/report', [AttendanceController::class, 'courseReport'])->name('course-report');
        Route::get('enrollment/{enrollment}/generate-qr', [AttendanceController::class, 'generateQR'])->name('generate-qr');
    });

    // ===== CERTIFICADOS =====
    Route::prefix('certificates')->name('api.certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::post('/', [CertificateController::class, 'store'])->name('store');
        Route::post('bulk-generate', [CertificateController::class, 'bulkGenerate'])->name('bulk-generate');
        Route::get('{certificate}', [CertificateController::class, 'show'])->name('show');
        Route::get('{certificate}/download', [CertificateController::class, 'download'])->name('download');
        Route::post('{certificate}/regenerate', [CertificateController::class, 'regenerate'])->name('regenerate');
        Route::post('{certificate}/revoke', [CertificateController::class, 'revoke'])->name('revoke');
        Route::post('{certificate}/reissue', [CertificateController::class, 'reissue'])->name('reissue');
        Route::get('student/{student}', [CertificateController::class, 'studentCertificates'])->name('student-certificates');
        Route::get('enrollment/{enrollment}/check-eligibility', [CertificateController::class, 'checkEligibility'])->name('check-eligibility');
        Route::delete('{certificate}', [CertificateController::class, 'destroy'])->name('destroy');
    });

    // ===== PLANTILLAS DE CERTIFICADOS =====
    Route::prefix('certificate-templates')->name('api.certificate-templates.')->group(function () {
        Route::get('/', [CertificateTemplateController::class, 'index'])->name('index');
        Route::post('/', [CertificateTemplateController::class, 'store'])->name('store');
        Route::get('{template}', [CertificateTemplateController::class, 'show'])->name('show');
        Route::put('{template}', [CertificateTemplateController::class, 'update'])->name('update');
        Route::post('{template}/toggle-active', [CertificateTemplateController::class, 'toggleActive'])->name('toggle-active');
        Route::post('{template}/set-default', [CertificateTemplateController::class, 'setDefault'])->name('set-default');
        Route::get('{template}/preview', [CertificateTemplateController::class, 'preview'])->name('preview');
        Route::get('{template}/variables', [CertificateTemplateController::class, 'variables'])->name('variables');
        Route::post('{template}/duplicate', [CertificateTemplateController::class, 'duplicate'])->name('duplicate');
        Route::post('create-default', [CertificateTemplateController::class, 'createDefault'])->name('create-default');
        Route::delete('{template}', [CertificateTemplateController::class, 'destroy'])->name('destroy');
    });
});