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
// use App\Http\Controllers\DataController;

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


    // Escort Staffs routes
    Route::get('/escort-staff', [EscortStaffsController::class, 'index'])->name('escort-staff');
    Route::get('/escort-staff/add', [EscortStaffsController::class, 'create'])->name('escort-staff.create');
    Route::get('/escort-staff/edit', [EscortStaffsController::class, 'edit'])->name('escort-staff.edit');
    // Inspection Officers routes
    Route::get('/inspection-officer', [InspectionOfficersController::class, 'index'])->name('inspection-officer');
    Route::get('/inspection-officer/add', [InspectionOfficersController::class, 'create'])->name('inspection-officer.create');
    Route::get('/inspection-officer/edit', [InspectionOfficersController::class, 'edit'])->name('inspection-officer.edit');

    // CI Assistants
    Route::get('/ci-assistant', [CIAssistantsController::class, 'index'])->name('ci-assistant');
    Route::get('/ci-assistant/add', [CIAssistantsController::class, 'create'])->name('ci-assistant.create');
    Route::post('/cheif-invigilator-assistant/store', [CIAssistantsController::class, 'store'])->name('ci-assistant.store');
    Route::get('/ci-assistant/edit/{id}', [CIAssistantsController::class, 'edit'])->name('ci-assistant.edit');
    Route::put('/ci-assistant/{id}', [CIAssistantsController::class, 'update'])->name('ci-assistant.update');
    Route::get('/ci-assistant/{id}', [CIAssistantsController::class, 'show'])->name('ci-assistant.show');
    Route::post('/ci-assistant/{id}/toggle-status', [CIAssistantsController::class, 'toggleStatus'])->name('ciAssistant.toggleStatus');

    // Role
    Route::get('/role', [RoleController::class, 'index'])->name('role');
    Route::get('/role/add', [RoleController::class, 'create'])->name('role.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('role.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/role/show', [RoleController::class, 'edit'])->name('roles.show');
    // CI CheckList
    Route::get('/ci-checklist', [CIChecklistController::class, 'index'])->name('ci-checklist');
    Route::get('/ci-checklist/add', [CIChecklistController::class, 'create'])->name('ci-checklist.create');
    Route::post('/ci-checklist/store', [CiChecklistController::class, 'store'])->name('ci-checklist.store');
    Route::get('ci-checklist/edit/{id}', [CiChecklistController::class, 'edit'])->name('ci-checklist.edit');
    Route::put('ci-checklist/update/{id}', [CiChecklistController::class, 'update'])->name('ci-checklist.update');
    Route::post('/ci-checklist/{id}/toggle-status', [CiChecklistController::class, 'toggleCiChecklistStatus']);
    //Current Exam

    Route::get('/current-exam/ciTask', [CurrentExamController::class, 'ciTask'])->name('current-exam.citask');
    Route::get('/current-exam/ciMeeting', [CurrentExamController::class, 'ciMeeting'])->name('current-exam.ciMeeting');
    Route::get('/current-exam/examActivityTask', [CurrentExamController::class, 'examActivityTask'])->name('current-exam.examActivityTask');
    Route::get('/current-exam/districtTask', [CurrentExamController::class, 'districtCollectrateTask'])->name('current-exam.districtTask');
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
//mobile-team-staffs Route::prefix('mobile-team-staffs')->group(function () {
Route::prefix('mobile-team-staffs')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [MobileTeamStaffsController::class, 'index'])->name('mobile-team-staffs.index');
        Route::get('/create', [MobileTeamStaffsController::class, 'create'])->name('mobile-team-staffs.create');
        Route::post('/', [MobileTeamStaffsController::class, 'store'])->name('mobile-team-staffs.store');
        Route::get('/{id}/edit', [MobileTeamStaffsController::class, 'edit'])->name('mobile-team-staffs.edit');
        Route::put('/{id}', [MobileTeamStaffsController::class, 'update'])->name('mobile-team-staffs.update');
        Route::get('/{id}', [MobileTeamStaffsController::class, 'show'])->name('mobile-team-staffs.show');
        Route::post('/{id}/toggle-status', [MobileTeamStaffsController::class, 'toggleStatus'])->name('mobile-team-staffs.toggle-status');
        Route::delete('/{id}', [MobileTeamStaffsController::class, 'destroy'])->name('mobile-team-staffs.destroy');
    });
});
//venues Route::prefix('venues')->group(function () {
Route::prefix('venues')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [VenuesController::class, 'index'])->name('venues.index');
        Route::get('/create', [VenuesController::class, 'create'])->name('venues.create');
        Route::post('/', [VenuesController::class, 'store'])->name('venues.store');
        Route::get('/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit');
        Route::put('/{id}', [VenuesController::class, 'update'])->name('venues.update');
        Route::get('/{id}', [VenuesController::class, 'show'])->name('venues.show');
        Route::post('/{id}/toggle-status', [VenuesController::class, 'toggleStatus'])->name('venues.toggle-status');
        Route::delete('/{id}', [VenuesController::class, 'destroy'])->name('venues.destroy');
    });
});
//invigilators Route::prefix('invigilators')->group(function () {
Route::prefix('invigilators')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [InvigilatorsController::class, 'index'])->name('invigilators.index');
        Route::get('/create', [InvigilatorsController::class, 'create'])->name('invigilators.create');
        Route::post('/', [InvigilatorsController::class, 'store'])->name('invigilators.store');
        Route::get('/{id}/edit', [InvigilatorsController::class, 'edit'])->name('invigilators.edit');
        Route::put('/{id}', [InvigilatorsController::class, 'update'])->name('invigilators.update');
        Route::get('/{id}', [InvigilatorsController::class, 'show'])->name('invigilators.show');
        Route::post('/{id}/toggle-status', [InvigilatorsController::class, 'toggleStatus'])->name('invigilators.toggle-status');
        Route::delete('/{id}', [InvigilatorsController::class, 'destroy'])->name('invigilators.destroy');
    });
});
//scribes Route::prefix('scribes')->group(function () {
Route::prefix('scribes')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [ScribeController::class, 'index'])->name('scribes.index');
        Route::get('/create', [ScribeController::class, 'create'])->name('scribes.create');
        Route::post('/', [ScribeController::class, 'store'])->name('scribes.store');
        Route::get('/{id}/edit', [ScribeController::class, 'edit'])->name('scribes.edit');
        Route::put('/{id}', [ScribeController::class, 'update'])->name('scribes.update');
        Route::get('/{id}', [ScribeController::class, 'show'])->name('scribes.show');
        Route::post('/{id}/toggle-status', [ScribeController::class, 'toggleStatus'])->name('scribes.toggle-status');
        Route::delete('/{id}', [ScribeController::class, 'destroy'])->name('scribes.destroy');
    });
});
//chief-invigilators Route::prefix('chief-invigilators')->group(function () {
Route::prefix('chief-invigilators')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [ChiefInvigilatorsController::class, 'index'])->name('chief-invigilators.index');
        Route::get('/create', [ChiefInvigilatorsController::class, 'create'])->name('chief-invigilators.create');
        Route::post('/', [ChiefInvigilatorsController::class, 'store'])->name('chief-invigilators.store');
        Route::get('/{id}/edit', [ChiefInvigilatorsController::class, 'edit'])->name('chief-invigilators.edit');
        Route::put('/{id}', [ChiefInvigilatorsController::class, 'update'])->name('chief-invigilators.update');
        Route::get('/{id}', [ChiefInvigilatorsController::class, 'show'])->name('chief-invigilators.show');
        Route::post('/{id}/toggle-status', [ChiefInvigilatorsController::class, 'toggleStatus'])->name('chief-invigilators.toggle-status');
        Route::delete('/{id}', [ChiefInvigilatorsController::class, 'destroy'])->name('chief-invigilators.destroy');
    });
});
//exam-service Route::prefix('exam-services')->group(function(){
Route::prefix('exam-services')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [ExamServiceController::class, 'index'])->name('exam-services.index');
        Route::get('/create', [ExamServiceController::class, 'create'])->name('exam-services.create');
        Route::post('/', [ExamServiceController::class, 'store'])->name('exam-services.store');
        Route::get('/{id}/edit', [ExamServiceController::class, 'edit'])->name('exam-services.edit');
        Route::put('/{id}', [ExamServiceController::class, 'update'])->name('exam-services.update');
        Route::get('/{id}', [ExamServiceController::class, 'show'])->name('exam-services.show');
        Route::post('/{id}/toggle-status', [ExamServiceController::class, 'toggleStatus'])->name('exam-services.toggle-status');
        Route::delete('/{id}', [ExamServiceController::class, 'destroy'])->name('exam-services.destroy');
    });
});
//department-officials Route::prefix('department-officials ')->group(function(){
Route::prefix('department-officials')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [DepartmentOfficialsController::class, 'index'])->name('department-officials.index');
        Route::get('/create', [DepartmentOfficialsController::class, 'create'])->name('department-officials.create');
        Route::post('/', [DepartmentOfficialsController::class, 'store'])->name('department-officials.store');
        Route::get('/{id}/edit', [DepartmentOfficialsController::class, 'edit'])->name('department-officials.edit');
        Route::put('/{id}', [DepartmentOfficialsController::class, 'update'])->name('department-officials.update');
        Route::get('/{id}', [DepartmentOfficialsController::class, 'show'])->name('department-officials.show');
        Route::post('/{id}/toggle-status', [DepartmentOfficialsController::class, 'toggleStatus'])->name('department-officials.toggle-status');
        Route::delete('/{id}', [DepartmentOfficialsController::class, 'destroy'])->name('department-officials.destroy');
    });
});
//current-exam Route::prefix('current-exam')->group(function(){
Route::prefix('current-exam')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [CurrentExamController::class, 'index'])->name('current-exam.index');
        Route::get('/create', [CurrentExamController::class, 'create'])->name('current-exam.create');
        Route::post('/', [CurrentExamController::class, 'store'])->name('current-exam.store');
        Route::post('/fetch-exam-details', [CurrentExamController::class, 'getExamByNotificationNo'])->name('current-exam.getExamByNotificationNo');
        Route::get('/{id}/edit', [CurrentExamController::class, 'edit'])->name('current-exam.edit');
        Route::put('/{id}', [CurrentExamController::class, 'update'])->name('current-exam.update');
        Route::get('/{id}', [CurrentExamController::class, 'show'])->name('current-exam.show');
        Route::post('/{id}/toggle-status', [CurrentExamController::class, 'toggleStatus'])->name('current-exam.toggle-status');
        Route::delete('/{id}', [CurrentExamController::class, 'destroy'])->name('current-exam.destroy');
        Route::get('/task/{id}', [CurrentExamController::class, 'task'])->name('current-exam.task');
    });
});
// Route::get('/run-function', [DataController::class, 'addData']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');