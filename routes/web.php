<?php

use App\Http\Controllers\AlertNotificationController;
use App\Http\Controllers\DistrictCandidatesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\TreasuryOfficerController;
use App\Http\Controllers\MobileTeamStaffsController;
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
use App\Http\Controllers\MyExamController;
use App\Http\Controllers\APDCandidatesController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\IDCandidatesController;
use App\Http\Controllers\VenueConsentController;
use App\Http\Controllers\CIConsolidateController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\CIMeetingController;
use App\Http\Controllers\ExamMaterialsDataController;
use App\Http\Controllers\EDController;
use App\Http\Controllers\Vehicle_SecurityController;
use App\Http\Controllers\ReceiveExamMaterialsController;
use App\Http\Controllers\Omr_AccountController;
use App\Http\Controllers\ExpenditureStatmentController;
use App\Http\Controllers\BundleReceivingReportController;
use App\Http\Controllers\CIPreliminaryCheckController;
use App\Http\Controllers\ExamMaterialsRouteController;
use App\Http\Controllers\ExamStaffAllotmentController;
use App\Http\Controllers\ExamTrunkBoxOTLDataController;
use App\Http\Controllers\QpBoxlogController;
use App\Http\Controllers\CICandidateLogsController;
use App\Http\Controllers\CIPaperReplacementsController;
use App\Http\Controllers\BundlePackagingController;
use App\Http\Controllers\CiMeetingAttendanceController;
use App\Http\Controllers\ConsolidatedStatementController;
use App\Http\Controllers\ChartedVehicleRoutesController;
use App\Http\Controllers\CandidateRemarksController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserGuideController;
use App\Http\Controllers\ExamMaterialsDiscrepancyController;
use App\Http\Controllers\EmergencyAlarmNotificationsController;



// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard'); // Redirect to the dashboard
});
Route::get('venues/verify/{token}', [VenuesController::class, 'verifyEmail'])->name('venues.verifyEmail');

// Authentication routes 
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/sw-login', [AuthController::class, 'showAdminLogin'])->name('sw-login');
    Route::post('/sw-login', [AuthController::class, 'Adminlogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('send-reset-link-email');
    Route::get('password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('password/check-email', [AuthController::class, 'showCheckEmail'])->name('password.check-email');
    // Email verification routes
    Route::get('/district/verify/{token}', [DistrictController::class, 'verifyEmail'])->name('district.verify');
    Route::get('/center/verify/{token}', [CenterController::class, 'verifyEmail'])->name('center.verifyEmail');
    Route::get('/department-official/verify/{token}', [DepartmentOfficialsController::class, 'verifyEmail'])->name('department-official.verifyEmail');
    Route::get('mobile-team/verify/{token}', [MobileTeamStaffsController::class, 'verifyEmail'])->name('mobile_team.verifyEmail');
    Route::get('chief-invigilator/verify/{token}', [ChiefInvigilatorsController::class, 'verifyEmail'])->name('chief-invigilator.verifyEmail');
    Route::get('/treasury-officers/verify-email/{token}', [TreasuryOfficerController::class, 'verifyEmail'])->name('treasury-officers.verifyEmail');

});

// Protected routes (require user to be logged in) 
Route::middleware(['auth.multi','check.session'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // MyAccount routes 
    Route::get('/myaccount', [MyAccountController::class, 'index'])->name('myaccount');
    // Change Password routes
    Route::get('/change-password', [ChangePasswordController::class, 'showchangePassword'])->name('change-password');
    // todo: -change in the perfixes
    // Route::post('/check-old-password', [ChangePasswordController::class, 'checkOldPassword'])->name('password.check');
    Route::post('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.update');
    //email verification link
    // Route::get('/test-mail', [TestMailController::class, 'sendTestEmail']);
    // Add other protected routes here
    Route::post('/resend-verification-link', [AuthController::class, 'resendVerificationEmail'])->name('user.resend-verification-link');

});

// CI Assistants
Route::prefix('ci-assistant')->group(function () {
    Route::middleware(['auth.multi','check.session', 'role.permission:ci-assistant.index'])->group(function () {
        Route::get('/', [CIAssistantsController::class, 'index'])
            ->name('ci-assistant');
        Route::get('/add', [CIAssistantsController::class, 'create'])
            ->name('ci-assistant.create')
            ->middleware('role.permission:ci-assistant.create');
        Route::post('/store', [CIAssistantsController::class, 'store'])
            ->name('ci-assistant.store')
            ->middleware('role.permission:ci-assistant.create');
        Route::get('/edit/{id}', [CIAssistantsController::class, 'edit'])
            ->name('ci-assistant.edit')
            ->middleware('role.permission:ci-assistant.edit');
        Route::put('/{id}', [CIAssistantsController::class, 'update'])
            ->name('ci-assistant.update')
            ->middleware('role.permission:ci-assistant.edit');
        Route::get('/{id}', [CIAssistantsController::class, 'show'])
            ->name('ci-assistant.show')
            ->middleware('role.permission:ci-assistant.show');
        Route::post('/{id}/toggle-status', [CIAssistantsController::class, 'toggleStatus'])
            ->name('ci-assistant.toggleStatus')
            ->middleware('role.permission:ci-assistant.toggle-status');
    });
});

// Role
Route::prefix('role')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:role.index'])->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('role')
            ->middleware('role.permission:role.index');
        Route::get('/add', [RoleController::class, 'create'])
            ->name('role.create')
            ->middleware('role.permission:role.create');
        Route::post('/', [RoleController::class, 'store'])
            ->name('role.store')
            ->middleware('role.permission:role.create');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->name('roles.edit')
            ->middleware('role.permission:role.edit');
        Route::put('/{id}', [RoleController::class, 'update'])
            ->name('roles.update')
            ->middleware('role.permission:role.edit');
    });
});

// CI CheckList
Route::prefix('ci-checklist')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:ci-checklist.index'])->group(function () {
        Route::get('/', [CIChecklistController::class, 'index'])
            ->name('ci-checklist')
            ->middleware('role.permission:ci-checklist.index');
        Route::get('/add', [CIChecklistController::class, 'create'])
            ->name('ci-checklist.create')
            ->middleware('role.permission:ci-checklist.create');
        Route::post('/', [CIChecklistController::class, 'store'])
            ->name('ci-checklist.store')
            ->middleware('role.permission:ci-checklist.create');
        Route::get('/{id}/edit', [CIChecklistController::class, 'edit'])
            ->name('ci-checklist.edit')
            ->middleware('role.permission:ci-checklist.edit');
        Route::put('/{id}', [CIChecklistController::class, 'update'])
            ->name('ci-checklist.update')
            ->middleware('role.permission:ci-checklist.edit');
        Route::post('/{id}/toggle-status', [CIChecklistController::class, 'toggleCiChecklistStatus'])
            ->name('ci-checklist.toggleStatus')
            ->middleware('role.permission:ci-checklist.toggle-status');
    });
});

//Current Exam 
Route::prefix('support')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/support', [SupportController::class, 'index'])->name('support');
    });
});
Route::prefix('user_guide')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/user_guide', [UserGuideController::class, 'index'])->name('user_guide');
    });
});

//completed-exam
Route::prefix('completed-exam')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:completed-exam.index'])->group(function () {
        Route::get('/', [CompletedExamController::class, 'index'])->name('completed-exam');
    });
});

//District
Route::prefix('district')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('district.index')
            ->middleware('role.permission:district.index');
        Route::get('/create', [DistrictController::class, 'create'])->name('district.create')
            ->middleware('role.permission:district.create');
        Route::post('/', [DistrictController::class, 'store'])->name('district.store')
            ->middleware('role.permission:district.create');
        Route::get('/{id}/edit', [DistrictController::class, 'edit'])->name('district.edit')
            ->middleware('role.permission:district.edit');
        Route::put('/{id}', [DistrictController::class, 'update'])->name('district.update')
            ->middleware('role.permission:district.edit');
        Route::get('/{id}', [DistrictController::class, 'show'])->name('district.show')
            ->middleware('role.permission:district.show');
        Route::post('/{id}/toggle-status', [DistrictController::class, 'toggleStatus'])->name('districts.toggle-status')
            ->middleware('role.permission:district.toggle-status');
        Route::delete('/{id}', [DistrictController::class, 'destroy'])->name('district.destroy')
            ->middleware('role.permission:district.destroy');
    });
});
//treasury-officers 
Route::prefix('treasury-officers')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [TreasuryOfficerController::class, 'index'])->name('treasury-officers.index')
            ->middleware('role.permission:treasury-officers.index');
        Route::get('/create', [TreasuryOfficerController::class, 'create'])->name('treasury-officers.create')
            ->middleware('role.permission:treasury-officers.create');
        Route::get('/{id}/edit', [TreasuryOfficerController::class, 'edit'])->name('treasury-officers.edit')
            ->middleware('role.permission:treasury-officers.edit');
        Route::post('/', [TreasuryOfficerController::class, 'store'])->name('treasury-officers.store')
            ->middleware('role.permission:treasury-officers.create');
        Route::put('/{id}', [TreasuryOfficerController::class, 'update'])->name('treasury-officers.update')
            ->middleware('role.permission:treasury-officers.edit');
        Route::get('/{id}', [TreasuryOfficerController::class, 'show'])->name('treasury-officers.show')
            ->middleware('role.permission:treasury-officers.show');
        Route::delete('/{id}', [TreasuryOfficerController::class, 'destroy'])->name('treasury-officers.destroy')
            ->middleware('role.permission:treasury-officers.destroy');
        Route::post('/{id}/toggle-status', [TreasuryOfficerController::class, 'toggleStatus'])->name('treasury-officers.toggle-status')
            ->middleware('role.permission:treasury-officers.toggle-status');
    });
});

//centers 
Route::prefix('centers')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [CenterController::class, 'index'])->name('centers.index')
            ->middleware('role.permission:centers.index');
        Route::get('/create', [CenterController::class, 'create'])->name('centers.create')
            ->middleware('role.permission:centers.create');
        Route::post('/', [CenterController::class, 'store'])->name('centers.store')
            ->middleware('role.permission:centers.create');
        Route::get('/{id}/edit', [CenterController::class, 'edit'])->name('centers.edit')
            ->middleware('role.permission:centers.edit');
        Route::put('/{id}', [CenterController::class, 'update'])->name('centers.update')
            ->middleware('role.permission:centers.edit');
        Route::get('/{id}', [CenterController::class, 'show'])->name('centers.show')
            ->middleware('role.permission:centers.show');
        Route::post('/{id}/toggle-status', [CenterController::class, 'toggleStatus'])->name('centers.toggle-status')
            ->middleware('role.permission:centers.toggle-status');
        Route::delete('/{id}', [CenterController::class, 'destroy'])->name('centers.destroy')
            ->middleware('role.permission:centers.destroy');
    });
});
//mobile-team-staffs
Route::prefix('mobile-team-staffs')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [MobileTeamStaffsController::class, 'index'])->name('mobile-team-staffs.index')
            ->middleware('role.permission:mobile-team.index');
        Route::get('/create', [MobileTeamStaffsController::class, 'create'])->name('mobile-team-staffs.create')
            ->middleware('role.permission:mobile-team.create');
        Route::post('/', [MobileTeamStaffsController::class, 'store'])->name('mobile-team-staffs.store')
            ->middleware('role.permission:mobile-team.create');
        Route::get('/{id}/edit', [MobileTeamStaffsController::class, 'edit'])->name('mobile-team-staffs.edit')
            ->middleware('role.permission:mobile-team.edit');
        Route::put('/{id}', [MobileTeamStaffsController::class, 'update'])->name('mobile-team-staffs.update')
            ->middleware('role.permission:mobile-team.edit');
        Route::get('/{id}', [MobileTeamStaffsController::class, 'show'])->name('mobile-team-staffs.show')
            ->middleware('role.permission:mobile-team.show');
        Route::post('/{id}/toggle-status', [MobileTeamStaffsController::class, 'toggleStatus'])->name('mobile-team-staffs.toggle-status')
            ->middleware('role.permission:mobile-team.toggle-status');
        Route::delete('/{id}', [MobileTeamStaffsController::class, 'destroy'])->name('mobile-team-staffs.destroy')
            ->middleware('role.permission:mobile-team.destroy');
    });
});
//venues
Route::prefix('venues')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [VenuesController::class, 'index'])->name('venues.index')
            ->middleware('role.permission:venues.index');
        Route::get('/json', [VenuesController::class, 'getVenuesJson'])->name('venues.json')
            ->middleware('role.permission:venues.index');
        Route::get('/create', [VenuesController::class, 'create'])->name('venues.create')
            ->middleware('role.permission:venues.create');
        Route::post('/', [VenuesController::class, 'store'])->name('venues.store')
            ->middleware('role.permission:venues.create');
        Route::get('/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit')
            ->middleware('role.permission:venues.edit');
        Route::put('/{id}', [VenuesController::class, 'update'])->name('venues.update')
            ->middleware('role.permission:venues.edit');
        Route::get('/{id}', [VenuesController::class, 'show'])->name('venues.show')
            ->middleware('role.permission:venues.show');
        Route::post('/{id}/toggle-status', [VenuesController::class, 'toggleStatus'])->name('venues.toggle-status')
            ->middleware('role.permission:venues.toggle-status');
        Route::delete('/{id}', [VenuesController::class, 'destroy'])->name('venues.destroy')
            ->middleware('role.permission:venues.destroy');
        Route::get('/{id}/venue-consent', [VenueConsentController::class, 'showVenueConsentForm'])->name('venues.venue-consent')
            ->middleware('role.permission:venues.venue-consent');
        Route::post('/{id}/venue-consent', [VenueConsentController::class, 'submitVenueConsentForm'])->name('venues.submit-venue-consent')
            ->middleware('role.permission:venues.submit-venue-consent');
        Route::get('/{id}/show-venue-consent', [VenueConsentController::class, 'showVenueConsentForm'])->name('venues.show-venue-consent')
            ->middleware('role.permission:venues.show-venue-consent');
    });
});
//invigilators
Route::prefix('invigilators')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:invigilators.index'])->group(function () {
        Route::get('/', [InvigilatorsController::class, 'index'])->name('invigilators.index')
            ->middleware('role.permission:invigilators.index');
        Route::get('/create', [InvigilatorsController::class, 'create'])->name('invigilators.create')
            ->middleware('role.permission:invigilators.create');
        Route::post('/', [InvigilatorsController::class, 'store'])->name('invigilators.store')
            ->middleware('role.permission:invigilators.create');
        Route::get('/{id}/edit', [InvigilatorsController::class, 'edit'])->name('invigilators.edit')
            ->middleware('role.permission:invigilators.edit');
        Route::put('/{id}', [InvigilatorsController::class, 'update'])->name('invigilators.update')
            ->middleware('role.permission:invigilators.edit');
        Route::get('/{id}', [InvigilatorsController::class, 'show'])->name('invigilators.show')
            ->middleware('role.permission:invigilators.show');
        Route::post('/{id}/toggle-status', [InvigilatorsController::class, 'toggleStatus'])->name('invigilators.toggle-status')
            ->middleware('role.permission:invigilators.toggle-status');
        Route::delete('/{id}', [InvigilatorsController::class, 'destroy'])->name('invigilators.destroy')
            ->middleware('role.permission:invigilators.destroy');
    });
});

//scribes
Route::prefix('scribes')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:scribes.index'])->group(function () {
        Route::get('/', [ScribeController::class, 'index'])->name('scribes.index')
            ->middleware('role.permission:scribes.index');
        Route::get('/create', [ScribeController::class, 'create'])->name('scribes.create')
            ->middleware('role.permission:scribes.create');
        Route::post('/', [ScribeController::class, 'store'])->name('scribes.store')
            ->middleware('role.permission:scribes.create');
        Route::get('/{id}/edit', [ScribeController::class, 'edit'])->name('scribes.edit')
            ->middleware('role.permission:scribes.edit');
        Route::put('/{id}', [ScribeController::class, 'update'])->name('scribes.update')
            ->middleware('role.permission:scribes.edit');
        Route::get('/{id}', [ScribeController::class, 'show'])->name('scribes.show')
            ->middleware('role.permission:scribes.show');
        Route::post('/{id}/toggle-status', [ScribeController::class, 'toggleStatus'])->name('scribes.toggle-status')
            ->middleware('role.permission:scribes.toggle-status');
        Route::delete('/{id}', [ScribeController::class, 'destroy'])->name('scribes.destroy')
            ->middleware('role.permission:scribes.destroy');
    });
});
//chief-invigilators
Route::prefix('chief-invigilators')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [ChiefInvigilatorsController::class, 'index'])->name('chief-invigilators.index')
            ->middleware('role.permission:chief-invigilators.index');
        Route::get('/json', [ChiefInvigilatorsController::class, 'getChiefInvigilatorsJson'])->name('chief-invigilators.json')
            ->middleware('role.permission:chief-invigilators.index');
        Route::get('/create', [ChiefInvigilatorsController::class, 'create'])->name('chief-invigilators.create')
            ->middleware('role.permission:chief-invigilators.create');
        Route::post('/', [ChiefInvigilatorsController::class, 'store'])->name('chief-invigilators.store')
            ->middleware('role.permission:chief-invigilators.create');
        Route::get('/{id}/edit', [ChiefInvigilatorsController::class, 'edit'])->name('chief-invigilators.edit')
            ->middleware('role.permission:chief-invigilators.edit');
        Route::put('/{id}', [ChiefInvigilatorsController::class, 'update'])->name('chief-invigilators.update')
            ->middleware('role.permission:chief-invigilators.edit');
        Route::get('/{id}', [ChiefInvigilatorsController::class, 'show'])->name('chief-invigilators.show')
            ->middleware('role.permission:chief-invigilators.show');
        Route::post('/{id}/toggle-status', [ChiefInvigilatorsController::class, 'toggleStatus'])->name('chief-invigilators.toggle-status')
            ->middleware('role.permission:chief-invigilators.toggle-status');
        Route::delete('/{id}', [ChiefInvigilatorsController::class, 'destroy'])->name('chief-invigilators.destroy')
            ->middleware('role.permission:chief-invigilators.destroy');
    });
});
//exam-services
Route::prefix('exam-services')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [ExamServiceController::class, 'index'])->name('exam-services.index')
            ->middleware('role.permission:exam-services.index');
        Route::get('/create', [ExamServiceController::class, 'create'])->name('exam-services.create')
            ->middleware('role.permission:exam-services.create');
        Route::post('/', [ExamServiceController::class, 'store'])->name('exam-services.store')
            ->middleware('role.permission:exam-services.create');
        Route::get('/{id}/edit', [ExamServiceController::class, 'edit'])->name('exam-services.edit')
            ->middleware('role.permission:exam-services.edit');
        Route::put('/{id}', [ExamServiceController::class, 'update'])->name('exam-services.update')
            ->middleware('role.permission:exam-services.edit');
        Route::get('/{id}', [ExamServiceController::class, 'show'])->name('exam-services.show')
            ->middleware('role.permission:exam-services.show');
        Route::post('/{id}/toggle-status', [ExamServiceController::class, 'toggleStatus'])->name('exam-services.toggle-status')
            ->middleware('role.permission:exam-services.toggle-status');
        Route::delete('/{id}', [ExamServiceController::class, 'destroy'])->name('exam-services.destroy')
            ->middleware('role.permission:exam-services.destroy');
    });
});
//department-officials
Route::prefix('department-officials')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [DepartmentOfficialsController::class, 'index'])->name('department-officials.index')
            ->middleware('role.permission:department-officials.index');
        Route::get('/create', [DepartmentOfficialsController::class, 'create'])->name('department-officials.create')
            ->middleware('role.permission:department-officials.create');
        Route::post('/', [DepartmentOfficialsController::class, 'store'])->name('department-officials.store')
            ->middleware('role.permission:department-officials.create');
        Route::get('/{id}/edit', [DepartmentOfficialsController::class, 'edit'])->name('department-officials.edit')
            ->middleware('role.permission:department-officials.edit');
        Route::put('/{id}', [DepartmentOfficialsController::class, 'update'])->name('department-officials.update')
            ->middleware('role.permission:department-officials.edit');
        Route::get('/{id}', [DepartmentOfficialsController::class, 'show'])->name('department-officials.show')
            ->middleware('role.permission:department-officials.show');
        Route::post('/{id}/toggle-status', [DepartmentOfficialsController::class, 'toggleStatus'])->name('department-officials.toggle-status')
            ->middleware('role.permission:department-officials.toggle-status');
        Route::delete('/{id}', [DepartmentOfficialsController::class, 'destroy'])->name('department-officials.destroy')
            ->middleware('role.permission:department-officials.destroy');
    });
});

//current-exam
Route::prefix('current-exam')->group(function () {
    Route::middleware(['auth.multi','check.session','role.permission:current-exam.index'])->group(function () {
        Route::get('/', [CurrentExamController::class, 'index'])->name('current-exam.index')
            ->middleware('role.permission:current-exam.index');
        Route::get('/create', [CurrentExamController::class, 'create'])->name('current-exam.create')
            ->middleware('role.permission:current-exam.create');
        Route::post('/', [CurrentExamController::class, 'store'])->name('current-exam.store')
            ->middleware('role.permission:current-exam.create');
        Route::get('/{id}/edit', [CurrentExamController::class, 'edit'])->name('current-exam.edit')
            ->middleware('role.permission:current-exam.edit');
        Route::put('/{id}', [CurrentExamController::class, 'update'])->name('current-exam.update')
            ->middleware('role.permission:current-exam.edit');
        Route::get('/{id}', [CurrentExamController::class, 'show'])->name('current-exam.show')
            ->middleware('role.permission:current-exam.show');
        Route::post('/{id}/toggle-status', [CurrentExamController::class, 'toggleStatus'])->name('current-exam.toggle-status')
            ->middleware('role.permission:current-exam.toggle-status');
        Route::delete('/{id}', [CurrentExamController::class, 'destroy'])->name('current-exam.destroy')
            ->middleware('role.permission:current-exam.destroy');
        Route::post('/fetch-exam-details', [CurrentExamController::class, 'getExamByNotificationNo'])->name('current-exam.getExamByNotificationNo')
            ->middleware('role.permission:current-exam.getExamByNotificationNo');
    });
});
//myexam
Route::prefix('my-exam')->group(function () {
    Route::middleware('auth.multi')->group(function () {
        Route::get('/{examid}', [MyExamController::class, 'task'])->name('my-exam.examTask')
            ->middleware('role.permission:my-exam.examTask');
        Route::get('/ci-task/{examid}/{session}', [MyExamController::class, 'ciExamActivity'])->name('my-exam.ciExamActivity')
            ->middleware('role.permission:my-exam.ciExamActivity');
    });
});


//apd-candidates
Route::prefix('apd-candidates')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/download-sample-csv', [APDCandidatesController::class, 'downloadSampleCsv'])->name('apd-candidates.download-sample-csv')
            ->middleware('role.permission:apd-candidates.download-sample-csv');
        Route::post('/upload-candidates-csv', [APDCandidatesController::class, 'uploadCandidatesCsv'])->name('apd-candidates.upload-candidates-csv')
            ->middleware('role.permission:apd-candidates.upload-candidates-csv');
        Route::post('/finalize-csv', [APDCandidatesController::class, 'finalizeHalls'])->name('apd-candidates.finalize-csv')
            ->middleware('role.permission:apd-candidates.finalize-csv');
        Route::get('/download-finalize-halls-sample-csv', [APDCandidatesController::class, 'downloadFinalizeHallsSampleCsv'])->name('apd-candidates.download-finalize-halls-sample-csv')
            ->middleware('role.permission:apd-candidates.download-finalize-halls-sample-csv');
    });
});

//id-candidates
Route::prefix('id-candidates')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/update-percentage', [IDCandidatesController::class, 'updatePercentage'])->name('id-candidates.update-percentage')
            ->middleware('role.permission:id-candidates.update-percentage');
        Route::get('/download-updated-count-csv/{examId}', [IDCandidatesController::class, 'downloadUpdatedCountCsv'])->name('id-candidates.download-updated-count-csv')
            ->middleware('role.permission:id-candidates.download-updated-count-csv');
        Route::get('/intimateCollectorate/{examId}', [IDCandidatesController::class, 'showDistrictIntimationForm'])->name('id-candidates.intimateCollectorate')
            ->middleware('role.permission:id-candidates.intimateCollectorate');
        Route::post('/send-accommodation-email', [IDCandidatesController::class, 'sendAccommodationEmail'])->name('id-candidates.send-accommodation-email')
            ->middleware('role.permission:id-candidates.send-accommodation-email');
        Route::get('/show-venue-confirmation-form/{examId}', [IDCandidatesController::class, 'showVenueConfirmationForm'])->name('id-candidates.show-venue-confirmation-form')
            ->middleware('role.permission:id-candidates.show-venue-confirmation-form');
        Route::post('/save-venue-confirmation/{examId}', [IDCandidatesController::class, 'saveVenueConfirmation'])->name('id-candidates.save-venue-confirmation')
            ->middleware('role.permission:id-candidates.save-venue-confirmation');
        Route::get('/export-confirmed-halls/{examId}', [IDCandidatesController::class, 'exportToCSV'])->name('id-candidates.export-confirmed-halls')
            ->middleware('role.permission:id-candidates.export-confirmed-halls');
        Route::get('/send-mail', [IDCandidatesController::class, 'sendIdToCollectorateEmail'])->name('id-candidates.send-mail')
            ->middleware('role.permission:id-candidates.send-mail');
    });
});
//district-candidates
Route::prefix('district-candidates')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/show-venue-intimation-form/{examId}', [DistrictCandidatesController::class, 'showVenueIntimationForm'])->name('district-candidates.showVenueIntimationForm')
            ->middleware('role.permission:district-candidates.show-venue-intimation-form');
        Route::get('/review-venue-intimation-form/{examId}', [DistrictCandidatesController::class, 'reviewVenueIntimationForm'])->name('district-candidates.reviewVenueIntimationForm')
            ->middleware('role.permission:district-candidates.review-venue-intimation-form');
        Route::post('/process-venue-consent-email', [DistrictCandidatesController::class, 'processVenueConsentEmail'])->name('district-candidates.processVenueConsentEmail')
            ->middleware('role.permission:district-candidates.process-venue-consent-email');
        Route::post('/clear-saved-venues', [DistrictCandidatesController::class, 'clearSavedVenues'])->name('district-candidates.clearSavedVenues')
            ->middleware('role.permission:district-candidates.clear-saved-venues');
        Route::post('/generate-qrcode', [DistrictCandidatesController::class, 'generateQRCode'])->name('generate.qrcode')
            ->middleware('role.permission:generate.qrcode');
        Route::get('/generatePdf/{qrCodeId}', [DistrictCandidatesController::class, 'generatePdf'])->name('district-candidates.generatePdf')
            ->middleware('role.permission:district-candidates.generatePdf');
    });
});
//ci-meetings
Route::prefix('ci-meetings')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/attendance-QRcode-scan', [CIMeetingController::class, 'handleAttendanceQRCodeScan'])->name('ci-meetings.attendance-QRcode-scan')
            ->middleware('role.permission:ci-meetings.attendance-QRcode-scan');
        Route::post('/update-adequacy-check', [CIMeetingController::class, 'updateAdequacyCheck'])->name('ci-meetings.updateAdequacyCheck')
            ->middleware('role.permission:ci-meetings.updateAdequacyCheck');
    });
});
//ci-reports
Route::prefix('ci-reports')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/generate-utilization-certificate/{examid}', [UtilityController::class, 'generateUtilizationCertificate'])->name('download.utilireport')
            ->middleware('role.permission:ci-reports.generate-utilization-certificate');
        Route::get('/ci-consolidate-report/{examId}/{exam_date}/{exam_session}', [CIConsolidateController::class, 'generateReport'])->name('download.report')
            ->middleware('role.permission:ci-reports.generate-report');
    });
});
//qp-box-log
Route::prefix('qp-box-log')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/qp-box-open', [QpBoxlogController::class, 'saveTime'])->name('qp-box-open.save-time')
            ->middleware('role.permission:qp-box-log.save-time');
        Route::post('/qp-box-distribution', [QpBoxlogController::class, 'saveqpboxdistributiontimeTime'])->name('qp-box-distribution.save-time')
            ->middleware('role.permission:qp-box-distribution.save-time');
    });
});
//ci-paper-replacements
Route::prefix('ci-paper-replacements')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/save-replacement-details', [CIPaperReplacementsController::class, 'saveReplacementDetails'])->name('save.replacement.details')
            ->middleware('role.permission:ci-paper-replacements.save-replacement-details');
    });
});
//ci-candidates-log
Route::prefix('ci-candidates-log')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/ci-candidates-log', [CICandidateLogsController::class, 'saveAdditionalcandidates'])->name('ci-candidates-log.savecandidates')
            ->middleware('role.permission:ci-candidates-log.savecandidates');
        Route::post('/ci-candidates-remarks', [CICandidateLogsController::class, 'saveRemarkcandidates'])->name('ci-candidates-remark.saveremarks')
            ->middleware('role.permission:ci-candidates-remark.saveremarks');
        Route::post('/ci-candidates-omrremarks', [CICandidateLogsController::class, 'saveOMRRemark'])->name('ci-candidates-omrremarks.saveomrremarks')
            ->middleware('role.permission:ci-candidates-omrremarks.saveomrremarks');
        Route::post('/save-candidate-attendance', [CICandidateLogsController::class, 'saveCandidateAttendance'])->name('candidate.attendance.save')
            ->middleware('role.permission:candidate.attendance.save');
    });
});
//ci-checklist
Route::prefix('ci-checklist')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/save', [CIPreliminaryCheckController::class, 'saveChecklist'])->name('ci-checklist.save')
            ->middleware('role.permission:ci-checklist.save');
        Route::post('/ci-session-save', [CIPreliminaryCheckController::class, 'saveSessionChecklist'])->name('ci-session-checklist.save')
            ->middleware('role.permission:ci-session-checklist.save');
        Route::post('/save-videography-checklist', [CIPreliminaryCheckController::class, 'saveVideographyChecklist'])->name('saveVideographyChecklist')
            ->middleware('role.permission:saveVideographyChecklist');
        Route::post('/save-consolidate-certificate', [CIPreliminaryCheckController::class, 'saveConsolidateCertificate'])->name('saveConsolidateCertificate')
            ->middleware('role.permission:saveConsolidateCertificate');
        Route::post('/save-utilization-certificate', [CIPreliminaryCheckController::class, 'saveUtilizationCertificate'])->name('saveUtilizationCertificate')
            ->middleware('role.permission:saveUtilizationCertificate');
    });
});
//ci-staffalloment
Route::prefix('ci-staffalloment')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::post('/save-invigilator-details', [ExamStaffAllotmentController::class, 'saveInvigilatorDetails'])->name('staffalloment.save-invigilator-details')
            ->middleware('role.permission:staffalloment.save-invigilator-details');
        Route::post('/staffalloment.view-invigilator-allocate', [ExamStaffAllotmentController::class, 'allocateHallsRandomly'])->name('staffalloment.view-invigilator-allocate')
            ->middleware('role.permission:staffalloment.view-invigilator-allocate');
        Route::put('/update-invigilator-details/{examId}/{examDate}/{ciId}', [ExamStaffAllotmentController::class, 'updateScribeDetails'])->name('staffalloment.update-scribe-details')
            ->middleware('role.permission:staffalloment.update-scribe-details');
        Route::put('/update-ci-assistant-details/{examId}/{examDate}/{ciId}', [ExamStaffAllotmentController::class, 'updateCIAssistantDetails'])->name('staffalloment.update-ci-assistant-details')
            ->middleware('role.permission:staffalloment.update-ci-assistant-details');
    });
});
//exam-materials
Route::prefix('exam-materials')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/download-sample-csv', [ExamMaterialsDataController::class, 'downloadSampleCsv'])->name('exam-materials.download-sample-csv')
            ->middleware('role.permission:exam-materials.download-sample-csv');
        Route::post('/upload', [ExamMaterialsDataController::class, 'uploadCsv'])->name('exam-materials.upload')
            ->middleware('role.permission:exam-materials.upload');
        Route::get('/{examId}', [ExamMaterialsDataController::class, 'index'])->name('exam-materials.index')
            ->middleware('role.permission:exam-materials.index');
    });
});
//bundle-packaging
Route::prefix('bundle-packaging')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/ci-bundlepackaging/{examId}/{exam_date}/{exam_session}', [BundlePackagingController::class, 'ciBundlepackagingView'])->name('bundle-packaging.ci-view')
            ->middleware('role.permission:bundle-packaging.ci-view');
        Route::get('/ci-to-mobileteam-bundle-packaging/{examId}/{examDate}', [BundlePackagingController::class, 'CItoMobileTeam'])->name('bundle-packaging.ci-to-mobileteam')
            ->middleware('role.permission:bundle-packaging.ci-to-mobileteam');
        Route::get('/mobileteam-to-district-bundle-packaging/{examId}', [BundlePackagingController::class, 'MobileTeamtoDistrict'])->name('bundle-packaging.mobileteam-to-district')
            ->middleware('role.permission:bundle-packaging.mobileteam-to-district');
        Route::get('/mobileteam-to-center/{examId}', [BundlePackagingController::class, 'MobileTeamtoCenter'])->name('bundle-packaging.mobileteam-to-center')
            ->middleware('role.permission:bundle-packaging.mobileteam-to-center');
        Route::post('/scan-disitrct-exam-materials/{examId}', [BundlePackagingController::class, 'scanDistrictExamMaterials'])->name('bundle-packaging.scan-disitrct-exam-materials')
            ->middleware('role.permission:bundle-packaging.scan-disitrct-exam-materials');
        Route::post('/scan-chennai-disitrct-exam-materials/{examId}', [BundlePackagingController::class, 'scanVandutyHQExamMaterials'])->name('bundle-packaging.scan-chennai-disitrct-exam-materials')
            ->middleware('role.permission:bundle-packaging.scan-chennai-disitrct-exam-materials');
        Route::post('/save-used-otl-codes', [BundlePackagingController::class, 'saveUsedOTLCodes'])->name('bundle-packaging.save-used-otl-codes')
            ->middleware('role.permission:bundle-packaging.save-trunkbox-used-otl-codes');
        Route::get('/vds-to-headquarters/{examId}', [BundlePackagingController::class, 'vanDutyStafftoHeadquarters'])->name('bundle-packaging.vanduty-staff-to-headquarters')
            ->middleware('role.permission:bundle-packaging.vanduty-staff-to-headquarters');
        Route::post('/scan-hq-exam-materials', [BundlePackagingController::class, 'scanHQExamMaterials'])->name('bundle-packaging.scan-hq-exam-materials')
            ->middleware('role.permission:bundle-packaging.scan-hq-exam-materials');
        Route::post('/save-handover-details', [BundlePackagingController::class, 'saveHandoverDetails'])->name('bundle-packaging.save-handover-details')
            ->middleware('role.permission:bundle-packaging.save-handover-details');
        Route::get('/report-handover-details/{id}', [BundlePackagingController::class, 'reportHandoverDetails'])->name('bundle-packaging.report-handover-details')
            ->middleware('role.permission:bundle-packaging.report-handover-details');
    });
});

Route::prefix('receive-exam-materials')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/printer-to-disitrct-treasury/{examId}', [ReceiveExamMaterialsController::class, 'printerToDistrictTreasury'])->name('receive-exam-materials.printer-to-disitrict-treasury')
            ->middleware('role.permission:receive-exam-materials.printer-to-disitrict-treasury');
        Route::post('/scan-disitrct-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanDistrictExamMaterials'])->name('receive-exam-materials.scan-disitrct-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-disitrct-exam-materials');
        Route::get('/printer-to-hq-treasury/{examId}', [ReceiveExamMaterialsController::class, 'printerToHQTreasury'])->name('receive-exam-materials.printer-to-hq-treasury')
            ->middleware('role.permission:receive-exam-materials.printer-to-hq-treasury');
        Route::post('/scan-hq-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanHQExamMaterials'])->name('receive-exam-materials.scan-hq-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-hq-exam-materials');
        Route::get('/district-to-center/{examId}/', [ReceiveExamMaterialsController::class, 'districtTreasuryToCenter'])->name('receive-exam-materials.district-to-center')
            ->middleware('role.permission:receive-exam-materials.district-to-center');
        Route::post('/scan-center-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanCenterExamMaterials'])->name('receive-exam-materials.scan-center-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-center-exam-materials');
        Route::get('/sub-treasury-to-mobile-team/{examId}/{examDate}', [ReceiveExamMaterialsController::class, 'subTreasuryToMobileTeam'])->name('receive-exam-materials.sub-treasury-to-mobile-team')
            ->middleware('role.permission:receive-exam-materials.sub-treasury-to-mobile-team');
        Route::post('/scan-mobile-team-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanMobileTeamExamMaterials'])->name('receive-exam-materials.scan-mobile-team-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-mobile-team-exam-materials');
        Route::get('/headquarters-to-vanduty/{examId}/{examDate}', [ReceiveExamMaterialsController::class, 'headquartersToVanDuty'])->name('receive-exam-materials.headquarters-to-vanduty')
            ->middleware('role.permission:receive-exam-materials.headquarters-to-vanduty');
        Route::post('/scan-vandutystaff-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanVandutystaffExamMaterials'])->name('receive-exam-materials.scan-vandutystaff-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-vandutystaff-exam-materials');
        Route::get('/receive-exam-materials/{examId}/{exam_date}/{exam_session}', [ReceiveExamMaterialsController::class, 'ciReceiveMaterialsFromMobileTeam'])->name('receive-exam-materials.mobileTeam-to-ci-materials')
            ->middleware('role.permission:receive-exam-materials.mobileTeam-to-ci-materials');
        Route::post('/scan-ci-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanCIExamMaterials'])->name('receive-exam-materials.scan-ci-exam-materials')
            ->middleware('role.permission:receive-exam-materials.scan-ci-exam-materials');
    });
});

//exam-materials-route
Route::prefix('exam-materials-route')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/{examId}', [ExamMaterialsRouteController::class, 'index'])->name('exam-materials-route.index')
            ->middleware('role.permission:exam-materials-route.index');
        Route::get('/create/{examId}', [ExamMaterialsRouteController::class, 'createRoute'])->name('exam-materials-route.create')
            ->middleware('role.permission:exam-materials-route.create');
        Route::post('/store', [ExamMaterialsRouteController::class, 'storeRoute'])->name('exam-materials-route.store')
            ->middleware('role.permission:exam-materials-route.create');
        Route::get('/edit/{Id}', [ExamMaterialsRouteController::class, 'editRoute'])->name('exam-materials-route.edit')
            ->middleware('role.permission:exam-materials-route.edit');
        Route::put('/update/{Id}', [ExamMaterialsRouteController::class, 'updateRoute'])->name('exam-materials-route.update')
            ->middleware('role.permission:exam-materials-route.edit');
        Route::get('/view/{Id}', [ExamMaterialsRouteController::class, 'viewRoute'])->name('exam-materials-route.view')
            ->middleware('role.permission:exam-materials-route.view');
    });
});

//charted-vehicle-routes
Route::prefix('charted-vehicle-routes')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/', [ChartedVehicleRoutesController::class, 'index'])->name('charted-vehicle-routes.index')
            ->middleware('role.permission:charted-vehicle-routes.index');
        Route::get('/create', [ChartedVehicleRoutesController::class, 'createRoute'])->name('charted-vehicle-routes.create')
            ->middleware('role.permission:charted-vehicle-routes.create');
        Route::post('/store', [ChartedVehicleRoutesController::class, 'storeRoute'])->name('charted-vehicle-routes.store')
            ->middleware('role.permission:charted-vehicle-routes.create');
        Route::get('/edit/{Id}', [ChartedVehicleRoutesController::class, 'editRoute'])->name('charted-vehicle-routes.edit')
            ->middleware('role.permission:charted-vehicle-routes.edit');
        Route::put('/update/{Id}', [ChartedVehicleRoutesController::class, 'updateRoute'])->name('charted-vehicle-routes.update')
            ->middleware('role.permission:charted-vehicle-routes.edit');
        Route::get('/view/{Id}', [ChartedVehicleRoutesController::class, 'viewRoute'])->name('charted-vehicle-routes.view')
            ->middleware('role.permission:charted-vehicle-routes.view');
        Route::post('/get-districts-for-exam', [ChartedVehicleRoutesController::class, 'getDistrictsForExamIDs'])->name('charted-vehicle-routes.get-districts-for-exam')
            ->middleware('role.permission:charted-vehicle-routes.get-districts-for-exam');
        Route::get('/downward-journey-routes', [ChartedVehicleRoutesController::class, 'downwardJourneyRoutes'])->name('charted-vehicle-routes.downward-journey-routes')
            ->middleware('role.permission:charted-vehicle-routes.downward-journey-routes');
        Route::post('/save-otl-lock-used', [ChartedVehicleRoutesController::class, 'saveOTLLockUsed'])->name('charted-vehicle-routes.save-otl-lock-used')
            ->middleware('role.permission:charted-vehicle-routes.save-otl-lock-used');
        Route::post('/save-gps-lock-used', [ChartedVehicleRoutesController::class, 'saveGPSLockUsed'])->name('charted-vehicle-routes.save-gps-lock-used')
            ->middleware('role.permission:charted-vehicle-routes.save-gps-lock-used');
        Route::post('/charted-vehicle-verification', [ChartedVehicleRoutesController::class, 'chartedVehicleVerification'])->name('charted.vehicle.verification')
            ->middleware('role.permission:charted.vehicle.verification');
        Route::get('/vehicel-report/{id}', [ChartedVehicleRoutesController::class, 'generateVehicleReport'])->name('vehicel.report.download')
            ->middleware('role.permission:vehicel.report.download');
        Route::get('/view-trunk-boxes/{Id}', [ChartedVehicleRoutesController::class, 'viewTrunkboxes'])->name('viewTrunkboxes')
            ->middleware('role.permission:viewTrunkboxes');
        Route::get('/generate-trunkbox-order/{Id}', [ChartedVehicleRoutesController::class, 'generateTrunkboxOrder'])->name('generateTrunkboxOrder')
            ->middleware('role.permission:generateTrunkboxOrder');
        Route::get('/generate-annexure-1B-report/{Id}', [ChartedVehicleRoutesController::class, 'generateAnnexure1BReport'])->name('charted-vehicle-routes.generateAnnexure1BReport')
            ->middleware('role.permission:charted-vehicle-routes.generateAnnexure1BReport');
        Route::get('/get-cv-routes-report', [ChartedVehicleRoutesController::class, 'getCvRoutesReport'])->name('charted-vehicle-routes.getCvRoutesReport')
            ->middleware('role.permission:charted-vehicle-routes.getCvRoutesReport');
        Route::get('/generate-cv-routes-report', [ChartedVehicleRoutesController::class, 'generateCvRoutesReport'])->name('charted-vehicle-routes.generateCvRoutesReport')
            ->middleware('role.permission:charted-vehicle-routes.generateCvRoutesReport');
    });
});

//trunkbox-qr-otl-data
Route::prefix('exam-trunkbox-qr-otl-data')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        Route::get('/download-sample-csv', [ExamTrunkBoxOTLDataController::class, 'downloadSampleCsv'])->name('exam-trunkbox-qr-otl-data.download-sample-csv')
            ->middleware('role.permission:exam-trunkbox-qr-otl-data.download-sample-csv');
        Route::post('/upload', [ExamTrunkBoxOTLDataController::class, 'uploadCsv'])->name('exam-trunkbox-qr-otl-data.upload')
            ->middleware('role.permission:exam-trunkbox-qr-otl-data.upload');
        Route::get('/{examId}', [ExamTrunkBoxOTLDataController::class, 'index'])->name('exam-trunkbox-qr-otl-data.index')
            ->middleware('role.permission:exam-trunkbox-qr-otl-data.index');
    });
});


Route::prefix('alert-notification')->group(function () {
    Route::middleware(['auth.multi','check.session'])->group(function () {
        // Add your new alert notification routes
        Route::post('/save-emergency-alert', [AlertNotificationController::class, 'saveEmergencyAlert'])->name('alert-notification.emergency-alert')
            ->middleware('role.permission:alert-notification.emergency-alert');
        Route::post('/save-adequacy-check', [AlertNotificationController::class, 'saveAdequacyCheck'])->name('alert-notification.adequacy-check')
            ->middleware('role.permission:alert-notification.adequacy-check');
    });
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//TODO: Complete permission for reports and other routes below
//PDF
//attendance-report

//unwanted
Route::get('/bundle-receiving', [BundleReceivingReportController::class, 'generatebundlereceivingReport'])->name('bundle-receiving.report');

//Reports
Route::prefix('report')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
    Route::get('/api/get-dropdown-data', [AttendanceReportController::class, 'getDropdownData'])->name('attendance.dropdown')->middleware('role.permission:attendance.dropdown');
    Route::get('/attendance-report', [AttendanceReportController::class, 'index'])->name('attendance.report')->middleware('role.permission:attendance.report');
    Route::get('/attendance-report-overall', [AttendanceReportController::class, 'generatecategorysender'])->name('attendance.report.overall')->middleware('role.permission:attendance.report.overall');
    Route::get('/expenditure-statment', [ExpenditureStatmentController::class, 'index'])->name('expenditure-statment.report')->middleware('role.permission:expenditure-statment.report');
    Route::get('/filter-expenditure', [ExpenditureStatmentController::class, 'filterExpenditure'])->name('filter.expenditure')->middleware('role.permission:filter.expenditure');
    Route::get('/omr-account', [Omr_AccountController::class, 'index'])->name('omr-account.report')->middleware('role.permission:omr-account.report');
    Route::get('/omr-report-overall', [Omr_AccountController::class, 'generateReport'])->name('omr-report.report.overall')->middleware('role.permission:omr-report.report.overall');
    Route::get('/ci-attendace', [CiMeetingAttendanceController::class, 'index'])->name('ci-attendace.report')->middleware('role.permission:ci-attendace.report');
    Route::get('/ci-attendace-report-overall', [CiMeetingAttendanceController::class, 'generateCIMeetingReport'])->name('ci-attendace.report.overall')->middleware('role.permission:ci-attendace.report.overall');
    Route::get('/consolidated-statement', [ConsolidatedStatementController::class, 'index'])->name('consolidated-statement.report')->middleware('role.permission:consolidated-statement.report');
    Route::get('/candidate-remarks', [CandidateRemarksController::class, 'index'])->name('candidate-remarks.report')->middleware('role.permission:candidate-remarks.report');
    Route::get('/candidate-remarks-report-overall', [CandidateRemarksController::class, 'generateCandidateRemarksReportOverall'])->name('candidate-remarks.report.overall')->middleware('role.permission:candidate-remarks.report.overall');
    Route::get('/exam-material-discrepancy', [ExamMaterialsDiscrepancyController::class, 'index'])->name('exam-material-discrepancy.report')->middleware('role.permission:exam-material-discrepancy.report');
    Route::get('/emergency-alarm-notification', [EmergencyAlarmNotificationsController::class, 'index'])->name('emergency-alarm-notification.report')->middleware('role.permission:emergency-alarm-notification.report');
    });
}); 


Route::get('/view-consolidated-report', function (Request $request) {
    try {
        // Decrypt the file path
        $decryptedPath = Crypt::decryptString($request->encryptedUrl);
        // Get the absolute path
        $filePath = storage_path('app/public/' . str_replace('storage/', '', $decryptedPath));
       // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        // Custom filename for download
        $newFileName = 'Consolidated-Report-' . now()->format('Ymd') . '.pdf';
        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $newFileName . '"',
            'Content-Type' => 'application/pdf',
        ]);
    } catch (\Exception $e) {
        abort(404, 'Invalid URL');
    }
})->name('report.consolidated.view')->middleware('role.permission:report.consolidated.view');
Route::get('/view-report', function (Request $request) {
    try {
        // Decrypt the file path
        $decryptedPath = Crypt::decryptString($request->encryptedUrl);

        // Get the absolute path to the file
        $filePath = storage_path('app/public/' . str_replace('storage/', '', $decryptedPath));
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        // Set the new file name (e.g., "Exam-Utilization-Report.pdf")
        $newFileName = 'Utilization-Report-' . now()->format('Ymd') . '.pdf';
        // Return the file as a download with the new name
        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $newFileName . '"',
            'Content-Type' => 'application/pdf',
        ]);
    } catch (\Exception $e) {
        abort(404, 'Invalid URL');
    }
})->name('report.view')->middleware('role.permission:report.view');