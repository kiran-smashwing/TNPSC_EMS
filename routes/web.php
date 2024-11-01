<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\TreasuryOfficersController;
use App\Http\Controllers\MobileTeamStaffsController;
use App\Http\Controllers\EscortStaffsController;
use App\Http\Controllers\InspectionOfficersController;
use App\Http\Controllers\ChiefInvigilatorsController;
use App\Http\Controllers\InvigilatorsController;
use App\Http\Controllers\ScribeController;
use App\Http\Controllers\CIAssistantsController;
use App\Http\Controllers\VenuesController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CIChecklistController;
use App\Http\Controllers\DepartmentOfficialsController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ExamServiceController;
use App\Http\Controllers\CurrentExamController;
use App\Http\Controllers\CompletedExamController;
use App\Http\Controllers\QrCodeController;

// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard'); // Redirect to the dashboard
});
// Authentication routes 
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('send-reset-link-email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('password/check-email', [AuthController::class, 'showCheckEmail'])->name('password.check-email');


// Protected routes (require user to be logged in) 
Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // MyAccount routes 
    Route::get('/myaccount', [MyAccountController::class, 'index'])->name('myaccount');
    // Change Password routes
    Route::get('/change-password', [ChangePasswordController::class, 'showchangePassword'])->name('change-password');
    // Center routes
    Route::get('/center', [CenterController::class, 'index'])->name('center');
    Route::get('/center/add', [CenterController::class, 'create'])->name('center.create');
    Route::get('/center/edit', [CenterController::class, 'edit'])->name('center.edit');
    Route::post('/center/add', [CenterController::class, 'store'])->name('center.store');
    Route::put('/center/update/{center}', [CenterController::class, 'update'])->name('center.update');
    Route::get('/center/show', [CenterController::class, 'show'])->name('center.show');
    // Treasury routes
    Route::get('/treasury-officer', [TreasuryOfficersController::class, 'index'])->name('treasury-officer');
    Route::get('/treasury-officer/add', [TreasuryOfficersController::class, 'create'])->name('treasury-officer.create');
    Route::get('/treasury-officer/edit', [TreasuryOfficersController::class, 'edit'])->name('treasury-officer.edit');
    Route::get('/treasury-officer/show', [TreasuryOfficersController::class, 'show'])->name('treasury-officer.show');
    // Mobile Team Staffs routes
    Route::get('/mobile-team', [MobileTeamStaffsController::class, 'index'])->name('mobile-team');
    Route::get('/mobile-team/add', [MobileTeamStaffsController::class, 'create'])->name('mobile-team.create');
    Route::get('/mobile-team/edit', [MobileTeamStaffsController::class, 'edit'])->name('mobile-team.edit');
    Route::get('/mobile-team/show', [MobileTeamStaffsController::class, 'show'])->name('mobile-team.show');
    // Escort Staffs routes
    Route::get('/escort-staff', [EscortStaffsController::class, 'index'])->name('escort-staff');
    Route::get('/escort-staff/add', [EscortStaffsController::class, 'create'])->name('escort-staff.create');
    Route::get('/escort-staff/edit', [EscortStaffsController::class, 'edit'])->name('escort-staff.edit');
    // Inspection Officers routes
    Route::get('/inspection-officer', [InspectionOfficersController::class, 'index'])->name('inspection-officer');
    Route::get('/inspection-officer/add', [InspectionOfficersController::class, 'create'])->name('inspection-officer.create');
    Route::get('/inspection-officer/edit', [InspectionOfficersController::class, 'edit'])->name('inspection-officer.edit');
    // Cheif Invigilator routes
    Route::get('/chief-invigilator', [ChiefInvigilatorsController::class, 'index'])->name('chief-invigilator');
    Route::get('/chief-invigilator/add', [ChiefInvigilatorsController::class, 'create'])->name('chief-invigilator.create');
    Route::get('/chief-invigilator/edit', [ChiefInvigilatorsController::class, 'edit'])->name('chief-invigilator.edit');
    Route::get('/chief-invigilator/show', [ChiefInvigilatorsController::class, 'show'])->name('chief-invigilator.show');
    // Invigilator routes
    Route::get('/invigilator', [InvigilatorsController::class, 'index'])->name('invigilator');
    Route::get('/invigilator/add', [InvigilatorsController::class, 'create'])->name('invigilator.create');
    Route::get('/invigilator/edit', [InvigilatorsController::class, 'edit'])->name('invigilator.edit');
    Route::get('/invigilator/show', [InvigilatorsController::class, 'show'])->name('invigilator.show');
    // Scribe
    Route::get('/scribe', [ScribeController::class, 'index'])->name('scribe');
    Route::get('/scribe/add', [ScribeController::class, 'create'])->name('scribe.create');
    Route::get('/scribe/edit', [ScribeController::class, 'edit'])->name('scribe.edit');
    Route::get('/scribe/show', [ScribeController::class, 'show'])->name('scribe.show');
    // CI Assistants
    Route::get('/ci-assistant', [CIAssistantsController::class, 'index'])->name('ci-assistant');
    Route::get('/ci-assistant/add', [CIAssistantsController::class, 'create'])->name('ci-assistant.create');
    Route::get('/ci-assistant/edit', [CIAssistantsController::class, 'edit'])->name('ci-assistant.edit');
    Route::get('/ci-assistant/show', [CIAssistantsController::class, 'show'])->name('ci-assistant.show');
    // Collectrate
    // Route::get('/collectorate', [DistrictController::class, 'index'])->name('collectorate');
    // Route::get('/collectorate/add', [DistrictController::class, 'create'])->name('collectorate.create');
    // Route::get('/collectorate/edit', [DistrictController::class, 'edit'])->name('collectorate.edit');
    // Route::get('/collectorate/show', [DistrictController::class, 'show'])->name('collectorate.show');
    // Role
    Route::get('/role', [RoleController::class, 'index'])->name('role');
    Route::get('/role/add', [RoleController::class, 'create'])->name('role.create');
    Route::get('/role/edit', [RoleController::class, 'edit'])->name('role.edit');
    // Venue
    Route::get('/venue', [VenuesController::class, 'index'])->name('venue');
    Route::get('/venue/add', [VenuesController::class, 'create'])->name('venue.create');
    Route::get('/venue/edit', [VenuesController::class, 'edit'])->name('venue.edit');
    Route::get('/venue/show', [VenuesController::class, 'show'])->name('venue.show');
    // Department
    Route::get('/department', [DepartmentOfficialsController::class, 'index'])->name('department');
    Route::get('/department/add', [DepartmentOfficialsController::class, 'create'])->name('department.create');
    Route::get('/department/edit', [DepartmentOfficialsController::class, 'edit'])->name('department.edit');
    Route::get('/department/show', [DepartmentOfficialsController::class, 'show'])->name('department.show');
    // Examination Services
    Route::get('/exam-service', [ExamServiceController::class, 'index'])->name('exam-service');
    Route::get('/exam-service/add', [ExamServiceController::class, 'create'])->name('exam-service.create');
    Route::get('/exam-service/edit', [ExamServiceController::class, 'edit'])->name('exam-service.edit');
    // CI CheckList
    Route::get('/ci-checklist', [CIChecklistController::class, 'index'])->name('ci-checklist');
    Route::get('/ci-checklist/add', [CIChecklistController::class, 'create'])->name('ci-checklist.create');
    Route::get('/ci-checklist/edit', [CIChecklistController::class, 'edit'])->name('ci-checklist.edit');
    //Current Exam
    Route::get('/current-exam', [CurrentExamController::class, 'index'])->name('current-exam');
    Route::get('/current-exam/add', [CurrentExamController::class, 'create'])->name('current-exam.create');
    Route::get('/current-exam/task', [CurrentExamController::class, 'task'])->name('current-exam.task');
    Route::get('/current-exam/ciTask', [CurrentExamController::class, 'ciTask'])->name('current-exam.citask');
    Route::get('/current-exam/ciMeeting', [CurrentExamController::class, 'ciMeeting'])->name('current-exam.ciMeeting');
    Route::get('/current-exam/examActivityTask', [CurrentExamController::class, 'examActivityTask'])->name('current-exam.examActivityTask');
    Route::get('/current-exam/districtTask', [CurrentExamController::class, 'districtCollectrateTask'])->name('current-exam.districtTask');
    Route::get('/current-exam/edit', [CurrentExamController::class, 'edit'])->name('current-exam.edit');
    Route::get('/current-exam/increaseCandidate', [CurrentExamController::class, 'increaseCandidate'])->name('current-exam.incCandidate');
    Route::get('/current-exam/intimateCollectorate', [CurrentExamController::class, 'sendMailtoCollectorate'])->name('current-exam.intimateCollectorate');
    Route::get('/current-exam/venueConsent', [CurrentExamController::class, 'venueConsent'])->name('current-exam.venueConsent');
    Route::get('/current-exam/intimateVenue', [CurrentExamController::class, 'selectSendMailtoVenue'])->name('current-exam.intimateVenue');
    Route::get('/current-exam/confirmVenues', [CurrentExamController::class, 'confirmVenues'])->name('current-exam.confirmVenues');
    Route::get('/current-exam/ciReceiveMaterials', [CurrentExamController::class, 'ciReceiveMaterials'])->name('current-exam.ciReceiveMaterials');
    Route::get('/current-exam/treasury-mobTeam', [CurrentExamController::class, 'mobileTeamReceiveMaterialsFromTreasury'])->name('current-exam.treasury-mobTeam');
    Route::get('/current-exam/mobTeam-ci', [CurrentExamController::class, 'ciReceiveMaterialsFromMobileTeam'])->name('current-exam.mobTeam-ci');
    Route::get('/current-exam/bundlePackaging', [CurrentExamController::class, 'bundlePackaging'])->name('current-exam.bundlePackaging');
    Route::get('/current-exam/bundlePackagingverfiy', [CurrentExamController::class, 'bundlePackagingverfiy'])->name('current-exam.bundlePackagingverfiy');
    Route::get('/current-exam/vandutyBundlePackagingverfiy', [CurrentExamController::class, 'vandutyBundlePackagingverfiy'])->name('current-exam.vandutyBundlePackagingverfiy');
    Route::get('/current-exam/vdstotreasuryofficer', [CurrentExamController::class, 'vdstotreasuryofficer'])->name('current-exam.vdstotreasuryofficer');
    Route::get('/current-exam/routeView', [CurrentExamController::class, 'routeView'])->name('current-exam.routeView');
    Route::get('/current-exam/routeCreate', [CurrentExamController::class, 'routeCreate'])->name('current-exam.routeCreate');
    Route::get('/current-exam/routeEdit', [CurrentExamController::class, 'routeEdit'])->name('current-exam.routeEdit');
    Route::get('/current-exam/updateMaterialScanDetails', [CurrentExamController::class, 'updateMaterialScanDetails'])->name('current-exam.updateMaterialScanDetails');
    //Current Exam
    Route::get('/completed-exam', [CompletedExamController::class, 'index'])->name('completed-exam');
    Route::get('/completed-exam/task', [CompletedExamController::class, 'task'])->name('completed-exam.task');
    Route::get('/completed-exam/edit', [CompletedExamController::class, 'edit'])->name('completed-exam.edit');
    //Qr Code Reader
    Route::get('/qr-code-reader', [QrCodeController::class, 'index'])->name('qr-code-reader');
    Route::post('/process-qr-code', [QrCodeController::class, 'process'])->name('process-qr-code');
    // Add other protected routes here
});

//District Route::prefix('district')->group(function () {
Route::prefix('district')->group(function () {
    Route::get('login', [DistrictController::class, 'showLoginForm'])->name('district.login');
    Route::post('login', [DistrictController::class, 'login'])->name('district.login.submit');
    Route::post('logout', [DistrictController::class, 'logout'])->name('district.logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('district.index');
        Route::get('/create', [DistrictController::class, 'create'])->name('district.create');
        Route::post('/', [DistrictController::class, 'store'])->name('district.store');
        Route::get('/{id}/edit', [DistrictController::class, 'edit'])->name('district.edit');
        Route::put('/{id}', [DistrictController::class, 'update'])->name('district.update');
        Route::get('/{id}', [DistrictController::class, 'show'])->name('district.show');
        Route::delete('/{id}', [DistrictController::class, 'destroy'])->name('district.destroy');
    });
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
