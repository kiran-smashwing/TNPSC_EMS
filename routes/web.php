<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\TreasuryOfficerController;
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
Route::middleware(['auth.multi'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // MyAccount routes 
    Route::get('/myaccount', [MyAccountController::class, 'index'])->name('myaccount');
    // Change Password routes
    Route::get('/change-password', [ChangePasswordController::class, 'showchangePassword'])->name('change-password');
  
    // Mobile Team Staffs routes
    Route::get('/mobile-team', [MobileTeamStaffsController::class, 'index'])->name('mobile-team');
    Route::get('/mobile-team/add', [MobileTeamStaffsController::class, 'create'])->name('mobile-team.create');
    Route::post('mobile-team', [MobileTeamStaffsController::class, 'store'])->name('mobile-team.store');
    Route::get('/mobile-team/edit/{id}', [MobileTeamStaffsController::class, 'edit'])->name('mobile-team.edit');
    Route::put('/mobile-team/{mobile_id}', [MobileTeamStaffsController::class, 'update'])->name('mobile-team.update');
    Route::get('/mobile-team/{id}', [MobileTeamStaffsController::class, 'show'])->name('mobile-team.show');
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
    Route::post('invigilator/store', [InvigilatorsController::class, 'store'])->name('invigilator.store');
    Route::get('/invigilator/{id}/edit', [InvigilatorsController::class, 'edit'])->name('invigilator.edit');
    Route::put('/invigilator/{id}', [InvigilatorsController::class, 'update'])->name('invigilator.update');
    Route::post('/invigilator/{id}/toggle-status', [InvigilatorsController::class, 'toggleInvigilatorStatus'])->name('invigilator.toggleStatus');
    Route::get('invigilators/{id}', [InvigilatorsController::class, 'show'])->name('invigilator.show');
    // Scribe
    Route::get('/scribe', [ScribeController::class, 'index'])->name('scribe');
    Route::get('/scribe/add', [ScribeController::class, 'create'])->name('scribe.create');
    Route::post('/scribe', [ScribeController::class, 'store'])->name('scribes.store');
    Route::get('{id}/edit', [ScribeController::class, 'edit'])->name('scribes.edit'); // Edit form route
    Route::put('/scribe/{id}', [ScribeController::class, 'update'])->name('scribe.update');
    Route::get('/scribe/{id}', [ScribeController::class, 'show'])->name('scribe.show');
    // CI Assistants
    Route::get('/ci-assistant', [CIAssistantsController::class, 'index'])->name('ci-assistant');
    Route::get('/ci-assistant/add', [CIAssistantsController::class, 'create'])->name('ci-assistant.create');
    Route::post('/cheif-invigilator-assistant/store', [CIAssistantsController::class, 'store'])->name('ci-assistant.store');
    Route::get('/ci-assistant/edit/{id}', [CIAssistantsController::class, 'edit'])->name('ci-assistant.edit');
    Route::put('/ci-assistant/{id}', [CIAssistantsController::class, 'update'])->name('ci-assistant.update');
    Route::get('/ci-assistant/{id}', [CIAssistantsController::class, 'show'])->name('ci-assistant.show');

    // Role
    Route::get('/role', [RoleController::class, 'index'])->name('role');
    Route::get('/role/add', [RoleController::class, 'create'])->name('role.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('role.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/role/show', [RoleController::class, 'edit'])->name('roles.show');
    // Venue
    Route::get('/venue', [VenuesController::class, 'index'])->name('venue');
    Route::get('/venue/add', [VenuesController::class, 'create'])->name('venue.create');
    Route::post('/venues', [VenuesController::class, 'store'])->name('venues.store');
    Route::get('venues/{id}/edit', [VenuesController::class, 'edit'])->name('venue.edit');
    Route::put('/venue/{id}', [VenuesController::class, 'update'])->name('venue.update');
    Route::post('/venue/{id}/toggle-status', [VenuesController::class, 'toggleVenueStatus'])->name('venue.toggleStatus');
    Route::get('/venue/{id}', [VenuesController::class, 'show'])->name('venue.show');
    // Department
    Route::get('/department', [DepartmentOfficialsController::class, 'index'])->name('department');
    Route::get('/department/add', [DepartmentOfficialsController::class, 'create'])->name('department.create');
    Route::post('/department-officials/store', [DepartmentOfficialsController::class, 'store'])->name('department-officials.store');
    Route::get('/department-officials/{id}/edit', [DepartmentOfficialsController::class, 'edit'])->name('department.edit');
    Route::put('department/officials/{id}', [DepartmentOfficialsController::class, 'update'])->name('department.officials.update');
    Route::get('/department/officials/show/{id}', [DepartmentOfficialsController::class, 'show'])->name('department.show');
    // Examination Services
    Route::get('/exam-service', [ExamServiceController::class, 'index'])->name('exam-service');
    Route::get('/exam-service/add', [ExamServiceController::class, 'create'])->name('exam-service.create');
    Route::post('/exam-service/store', [ExamServiceController::class, 'store'])->name('examservice.store');
    Route::get('/exam-service/{id}/edit', [ExamServiceController::class, 'edit'])->name('examservice.edit');
    Route::put('/exam-service/{id}', [ExamServiceController::class, 'update'])->name('examservice.update');
    // CI CheckList
    Route::get('/ci-checklist', [CIChecklistController::class, 'index'])->name('ci-checklist');
    Route::get('/ci-checklist/add', [CIChecklistController::class, 'create'])->name('ci-checklist.create');
    Route::post('/ci-checklist/store', [CiChecklistController::class, 'store'])->name('ci-checklist.store');
    Route::get('ci-checklist/edit/{id}', [CiChecklistController::class, 'edit'])->name('ci-checklist.edit');
    Route::put('ci-checklist/update/{id}', [CiChecklistController::class, 'update'])->name('ci-checklist.update');
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
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('district.index');
        Route::get('/create', [DistrictController::class, 'create'])->name('district.create');
        Route::post('/', [DistrictController::class, 'store'])->name('district.store');
        Route::get('/{id}/edit', [DistrictController::class, 'edit'])->name('district.edit');
        Route::put('/{id}', [DistrictController::class, 'update'])->name('district.update');
        Route::get('/{id}', [DistrictController::class, 'show'])->name('district.show');
        Route::post('/{id}/toggle-status', [DistrictController::class, 'toggleStatus'])->name('districts.toggle-status');
        Route::delete('/{id}', [DistrictController::class, 'destroy'])->name('district.destroy');
    });
});
//treasury-officers Route::prefix('treasury-officers')->group(function () {
Route::prefix('treasury-officers')->group(function () {

    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [TreasuryOfficerController::class, 'index'])->name('treasury-officers.index');
        Route::get('/create', [TreasuryOfficerController::class, 'create'])->name('treasury-officers.create');
        Route::post('/', [TreasuryOfficerController::class, 'store'])->name('treasury-officers.store');
        Route::get('/{id}/edit', [TreasuryOfficerController::class, 'edit'])->name('treasury-officers.edit');
        Route::put('/{id}', [TreasuryOfficerController::class, 'update'])->name('treasury-officers.update');
        Route::get('/{id}', [TreasuryOfficerController::class, 'show'])->name('treasury-officers.show');
        Route::post('/{id}/toggle-status', [TreasuryOfficerController::class, 'toggleStatus'])->name('treasury-officers.toggle-status');
        Route::delete('/{id}', [TreasuryOfficerController::class, 'destroy'])->name('treasury-officers.destroy');
    });
});
//centers Route::prefix('centers')->group(function () {
Route::prefix('centers')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [CenterController::class, 'index'])->name('centers.index');
        Route::get('/create', [CenterController::class, 'create'])->name('centers.create');
        Route::post('/', [CenterController::class, 'store'])->name('centers.store');
        Route::get('/{id}/edit', [CenterController::class, 'edit'])->name('centers.edit');
        Route::put('/{id}', [CenterController::class, 'update'])->name('centers.update');
        Route::get('/{id}', [CenterController::class, 'show'])->name('centers.show');
        Route::post('/{id}/toggle-status', [CenterController::class, 'toggleStatus'])->name('centers.toggle-status');
        Route::delete('/{id}', [CenterController::class, 'destroy'])->name('centers.destroy');
    });
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
