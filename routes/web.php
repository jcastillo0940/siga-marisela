<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseOfferingController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\PublicLeadController; // Movido arriba
use App\Http\Controllers\Web\AttendanceWebController;
use App\Http\Controllers\Web\CertificateWebController;
use Illuminate\Support\Facades\Route;

// =========================================================
// RUTAS PÚBLICAS (Accesibles sin iniciar sesión)
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Formulario público de inscripción y API de carga dinámica
Route::get('inscripcion', [PublicLeadController::class, 'create'])->name('public.leads.create');
Route::post('inscripcion', [PublicLeadController::class, 'store'])->name('public.leads.store');
Route::get('inscripcion/exito', function() {
    return view('public.leads.success');
})->name('public.register.success');

Route::get('api/courses/{course}/offerings', [PublicLeadController::class, 'getOfferings'])->name('api.public.offerings');

// Verificación pública de certificados (QR)
Route::get('certificates/verify/{number}/{code}', [CertificateWebController::class, 'verify'])->name('certificates.verify');


// =========================================================
// RUTAS PRIVADAS (Requieren autenticación)
// =========================================================
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Users
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('users', UserController::class);
    
    // Roles
    Route::resource('roles', RoleController::class);
    
    // Students
    Route::patch('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
    Route::resource('students', StudentController::class);
    
    // Leads (CORREGIDO: Ahora dentro de auth)
    Route::post('leads/{lead}/verify-payment', [LeadController::class, 'verifyPayment'])->name('leads.verify-payment');
    Route::post('leads/{lead}/convert', [LeadController::class, 'convertToStudent'])->name('leads.convert');
    Route::resource('leads', LeadController::class);
    
    // Courses
    Route::patch('courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    Route::resource('courses', CourseController::class);
    
    // Course Offerings
    Route::patch('course-offerings/{offering}/toggle-status', [CourseOfferingController::class, 'toggleStatus'])->name('course-offerings.toggle-status');
    Route::resource('course-offerings', CourseOfferingController::class);
    
    // Enrollments
    Route::post('enrollments/{enrollment}/issue-certificate', [EnrollmentController::class, 'issueCertificate'])->name('enrollments.issue-certificate');
    Route::resource('enrollments', EnrollmentController::class);
    Route::get('api/students/search', [EnrollmentController::class, 'searchStudents'])->name('api.students.search');
   
    // Payments
    Route::get('api/payments/search-students', [PaymentController::class, 'searchStudentPayments'])->name('api.payments.search-students');
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
    Route::get('payments/{id}/pdf', [PaymentController::class, 'downloadPdf'])->name('payments.pdf');
    Route::post('payments/{id}/send-email', [PaymentController::class, 'sendEmail'])->name('payments.send-email');
   
    // Cash Registers
    Route::get('cash-registers', [CashRegisterController::class, 'index'])->name('cash-registers.index');
    Route::get('cash-registers/history', [CashRegisterController::class, 'history'])->name('cash-registers.history');
    Route::post('cash-registers/open', [CashRegisterController::class, 'open'])->name('cash-registers.open');
    Route::post('cash-registers/{id}/close', [CashRegisterController::class, 'close'])->name('cash-registers.close');
    Route::get('cash-registers/{id}/report', [CashRegisterController::class, 'report'])->name('cash-registers.report');
    Route::get('cash-registers/{id}/corte-x', [CashRegisterController::class, 'corteX'])->name('cash-registers.corte-x');
    Route::get('cash-registers/{id}/report-pdf', [CashRegisterController::class, 'downloadReportPdf'])->name('cash-registers.report-pdf');
    
    // POS
    Route::get('pos', [PaymentController::class, 'pos'])->name('pos.index');
    
    // Asistencia
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceWebController::class, 'index'])->name('index');
        Route::get('/offering/{offering}/sessions', [AttendanceWebController::class, 'sessions'])->name('sessions');
        Route::get('/session/{session}/take', [AttendanceWebController::class, 'take'])->name('take');
        Route::post('/session/{session}/store', [AttendanceWebController::class, 'store'])->name('store');
        Route::get('/offering/{offering}/report', [AttendanceWebController::class, 'courseReport'])->name('course-report');
        Route::get('/enrollment/{enrollment}/report', [AttendanceWebController::class, 'studentReport'])->name('student-report');
    });

    // Certificados
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateWebController::class, 'index'])->name('index');
        Route::get('/template/{template}', [CertificateWebController::class, 'showTemplate'])->name('template.show');
        Route::get('/student/{student}', [CertificateWebController::class, 'studentCertificates'])->name('student');
        Route::post('/generate/{enrollment}', [CertificateWebController::class, 'generate'])->name('generate');
        Route::get('/{certificate}/download', [CertificateWebController::class, 'download'])->name('download');
    });
});