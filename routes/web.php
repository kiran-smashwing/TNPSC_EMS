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
use App\Http\Controllers\EscortStaffsController;
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
use App\Http\Controllers\Expenditure_StatmentController;
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
use App\Http\Controllers\ExamMaterialsDiscrepancyController;
use App\Http\Controllers\EmergencyAlarmNotificationsController;

//PDF
//center_attenance_report
Route::get('/attendance-report', [AttendanceReportController::class, 'generateAttendanceReport'])->name('download.attendanceReport');
//district_attenance_report
Route::get('/attendance-report-district', [AttendanceReportController::class, 'generateAttendanceReportDistrict'])->name('download.attendanceReportdistrict');
//Overall_attenance_report
Route::get('/attendance-report-overall', [AttendanceReportController::class, 'generateAttendanceReportOverall'])->name('download.attendanceReportoverall');
//attendance-report
Route::get('/api/get-dropdown-data', [AttendanceReportController::class, 'getDropdownData'])->name('attendance.dropdown');
// Route::get('/attendance-report/filter', [AttendanceReportController::class, 'filterAttendanceReport'])->name('attendance-report.filter');


// Route::get('/omr-account', [Omr_AccountController::class, 'generateOmraccountReport'])->name('omr-account.report');
// Expenditure_StatmentController
// Route::get('/expenditure-statment', [Expenditure_StatmentController::class, 'generateexpenditureReport'])->name('expenditure-statment.report');
// Expenditure_StatmentController
Route::get('/bundle-receiving', [BundleReceivingReportController::class, 'generatebundlereceivingReport'])->name('bundle-receiving.report');


// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard'); // Redirect to the dashboard
});
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
});

// Protected routes (require user to be logged in) 
Route::middleware(['auth.multi'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // MyAccount routes 
    Route::get('/myaccount', [MyAccountController::class, 'index'])->name('myaccount');
    // Change Password routes
    Route::get('/change-password', [ChangePasswordController::class, 'showchangePassword'])->name('change-password');
    // todo: -change in the perfixes
    // Route::post('/check-old-password', [ChangePasswordController::class, 'checkOldPassword'])->name('password.check');
    Route::post('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.update');


    // Escort Staffs routes
    Route::get('/escort-staff', [EscortStaffsController::class, 'index'])->name('escort-staff');
    Route::get('/escort-staff/add', [EscortStaffsController::class, 'create'])->name('escort-staff.create');
    Route::get('/escort-staff/edit', [EscortStaffsController::class, 'edit'])->name('escort-staff.edit');
    // Inspection Officers routes
    

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
    Route::get('/current-exam/venueConsent', [CurrentExamController::class, 'venueConsent'])->name('current-exam.venueConsent');
    Route::get('/current-exam/confirmVenues', [CurrentExamController::class, 'confirmVenues'])->name('current-exam.confirmVenues');
    Route::get('/current-exam/ciReceiveMaterials', [CurrentExamController::class, 'ciReceiveMaterials'])->name('current-exam.ciReceiveMaterials');
    Route::get('/current-exam/treasury-mobTeam', [CurrentExamController::class, 'mobileTeamReceiveMaterialsFromTreasury'])->name('current-exam.treasury-mobTeam');

    Route::get('/current-exam/bundlePackaging', [CurrentExamController::class, 'bundlePackaging'])->name('current-exam.bundlePackaging');
    Route::get('/current-exam/bundlePackagingverfiy', [CurrentExamController::class, 'bundlePackagingverfiy'])->name('current-exam.bundlePackagingverfiy');
    Route::get('/current-exam/vandutyBundlePackagingverfiy', [CurrentExamController::class, 'vandutyBundlePackagingverfiy'])->name('current-exam.vandutyBundlePackagingverfiy');
    Route::get('/current-exam/vdstotreasuryofficer', [CurrentExamController::class, 'vdstotreasuryofficer'])->name('current-exam.vdstotreasuryofficer');
    Route::get('/current-exam/routeView', [CurrentExamController::class, 'routeView'])->name('current-exam.routeView');
    Route::get('/current-exam/routeCreate', [CurrentExamController::class, 'routeCreate'])->name('current-exam.routeCreate');
    Route::get('/current-exam/routeEdit', [CurrentExamController::class, 'routeEdit'])->name('current-exam.routeEdit');
    Route::get('/current-exam/updateMaterialScanDetails', [CurrentExamController::class, 'updateMaterialScanDetails'])->name('current-exam.updateMaterialScanDetails');
    //Current Exam 
    Route::prefix('support')->group(function () {
        Route::middleware(['auth.multi'])->group(function () {
            Route::get('/support', [SupportController::class, 'index'])->name('support');
        });
    });
    Route::prefix('completed-exam')->group(function () {
        Route::middleware(['auth.multi'])->group(function () {
            Route::get('/', [CompletedExamController::class, 'index'])->name('completed-exam');
            Route::get('/completed-exam/task', [CompletedExamController::class, 'task'])->name('completed-exam.task');
            Route::get('/completed-exam/edit', [CompletedExamController::class, 'edit'])->name('completed-exam.edit');
        });
    });

    Route::get('/test-mail', [TestMailController::class, 'sendTestEmail']);
    Route::get('/password/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
    // Add other protected routes here
});

// email verification
Route::get('/district/verify/{token}', [DistrictController::class, 'verifyEmail'])->name('district.verify');
Route::get('/center/verify/{token}', [CenterController::class, 'verifyEmail'])->name('center.verifyEmail');
Route::get('/department-official/verify/{token}', [DepartmentOfficialsController::class, 'verifyEmail'])->name('department-official.verifyEmail');
Route::get('mobile-team/verify/{token}', [MobileTeamStaffsController::class, 'verifyEmail'])->name('mobile_team.verifyEmail');
Route::get('chief-invigilator/verify/{token}', [ChiefInvigilatorsController::class, 'verifyEmail'])->name('chief-invigilator.verifyEmail');
Route::get('/treasury-officers/verify-email/{token}', [TreasuryOfficerController::class, 'verifyEmail'])->name('treasury-officers.verifyEmail');
//District Route::prefix('district')->group(function () {
Route::prefix('report')->group(function () {
    // Route::middleware(['auth.multi'])->group(function () {
    Route::get('/attendance-report', [AttendanceReportController::class, 'index'])->name('attendance.report');
    Route::get('/attendance-report-overall', [AttendanceReportController::class, 'generatecategorysender'])->name('attendance.report.overall');
    Route::get('/expenditure-statment', [Expenditure_StatmentController::class, 'index'])->name('expenditure-statment.report');
    Route::get('/filter-expenditure', [Expenditure_StatmentController::class, 'filterExpenditure'])->name('filter.expenditure');
    Route::get('generate-exapenditure-certificate/{examid}', [Expenditure_StatmentController::class, 'generateexapenditureCertificate'])->name('download.expenditure.report');
    Route::get('/expenditure-statment-overall', [Expenditure_StatmentController::class, 'generateexpenditureReport'])->name('expenditure-statment.report.overall');
    Route::get('/omr-account', [Omr_AccountController::class, 'index'])->name('omr-account.report');
    Route::get('/omr-report-overall', [Omr_AccountController::class, 'generateReport'])->name('omr-report.report.overall');
    Route::get('/ci-attendace', [CiMeetingAttendanceController::class, 'index'])->name('ci-attendace.report');
    Route::get('/ci-attendace-report-overall', [CiMeetingAttendanceController::class, 'generateCIMeetingReport'])->name('ci-attendace.report.overall');
    Route::get('/consolidated-statement', [ConsolidatedStatementController::class, 'index'])->name('consolidated-statement.report');
    Route::get('/download-consolidated-statement/{exam_id}/{exam_date}/{session}/{ci_id}', [ConsolidatedStatementController::class, 'generateconsolidatedReport'])->name('download-consolidated-statement.report');
    Route::get('/consolidated-statement-overall', [ConsolidatedStatementController::class, 'filterConsolidatedStatement'])->name('consolidated-statement.report.overall');
    Route::get('/candidate-remarks', [CandidateRemarksController::class, 'index'])->name('candidate-remarks.report');
    Route::get('/candidate-remarks-report-overall', [CandidateRemarksController::class, 'generateCandidateRemarksReportOverall'])->name('candidate-remarks.report.overall');
    Route::get('/exam-material-discrepancy', [ExamMaterialsDiscrepancyController::class, 'index'])->name('exam-material-discrepancy.report');
    Route::get('/emergency-alarm-notification', [EmergencyAlarmNotificationsController::class, 'index'])->name('emergency-alarm-notification.report');
    // });
});
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
        Route::get('/verify-email/{token}', [TreasuryOfficerController::class, 'verifyEmail'])->name('treasury-officer.verifyEmail');
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
        Route::get('/', action: [VenuesController::class, 'index'])->name('venues.index');
        Route::get('/json', [VenuesController::class, 'getVenuesJson'])->name('venues.json');
        Route::get('/create', [VenuesController::class, 'create'])->name('venues.create');
        Route::post('/', [VenuesController::class, 'store'])->name('venues.store');
        Route::get('/{id}/edit', [VenuesController::class, 'edit'])->name('venues.edit');
        Route::put('/{id}', [VenuesController::class, 'update'])->name('venues.update');
        Route::get('/{id}', [VenuesController::class, 'show'])->name('venues.show');
        Route::post('/{id}/toggle-status', [VenuesController::class, 'toggleStatus'])->name('venues.toggle-status');
        Route::delete('/{id}', [VenuesController::class, 'destroy'])->name('venues.destroy');
        Route::get('/{id}/venue-consent', action: [VenueConsentController::class, 'showVenueConsentForm'])->name('venues.venue-consent');
        Route::post('/{id}/venue-consent', [VenueConsentController::class, 'submitVenueConsentForm'])->name('venues.submit-venue-consent');
        Route::get('/{id}/show-venue-consent', action: [VenueConsentController::class, 'showVenueConsentForm'])->name('venues.show-venue-consent');
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
        Route::get('/json', [ChiefInvigilatorsController::class, 'getChiefInvigilatorsJson'])->name('chief-invigilators.json');
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
    });
});
//myexam Route::prefix('my-exam')->group(function(){
Route::prefix('my-exam')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/exam-task', action: [MyExamController::class, 'index'])->name('my-exam.index');
        Route::get('/center', action: [MyExamController::class, 'centerTask'])->name('my-exam.centerexamTask');
        Route::get('/mobile-team/{examid}', action: [MyExamController::class, 'mobileTeamTask'])->name('my-exam.mobileTeamTask');
        Route::get('/ci-task/{examid}', action: [MyExamController::class, 'ciTask'])->name('my-exam.ciTask');
        Route::get('/ci-task/{examid}/{session}', [MyExamController::class, 'ciExamActivity'])->name('my-exam.ciExamActivity');
        Route::get('/{examid}', action: [MyExamController::class, 'task'])->name('my-exam.examTask');
    });
});
//apd-candidates Route::prefix('apd-candidates')->group(function(){
Route::prefix('apd-candidates')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/download-sample-csv', [APDCandidatesController::class, 'downloadSampleCsv'])->name('apd-candidates.download-sample-csv');
        Route::post('/upload-candidates-csv', [APDCandidatesController::class, 'uploadCandidatesCsv'])->name('apd-candidates.upload-candidates-csv');
        Route::post('/finalize-csv', [APDCandidatesController::class, 'finalizeHalls'])->name('apd-candidates.finalize-csv');
        Route::get('/download-finalize-halls-sample-csv', [APDCandidatesController::class, 'downloadFinalizeHallsSampleCsv'])->name('apd-candidates.download-finalize-halls-sample-csv');
    });
});
//id-candidates Route::prefix('id-candidates')->group(function(){
Route::prefix('id-candidates')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::post('/update-percentage', [IDCandidatesController::class, 'updatePercentage'])->name('id-candidates.update-percentage');
        Route::get('/download-updated-count-csv/{examId}', [IDCandidatesController::class, 'downloadUpdatedCountCsv'])->name('id-candidates.download-updated-count-csv');
        Route::get('/intimateCollectorate/{examId}', [IDCandidatesController::class, 'showDistrictIntimationForm'])->name('id-candidates.intimateCollectorate');
        Route::post('/send-accommodation-email', [IDCandidatesController::class, 'sendAccommodationEmail'])->name('id-candidates.send-accommodation-email');
        Route::get('/show-venue-confirmation-form/{examId}', [IDCandidatesController::class, 'showVenueConfirmationForm'])->name('id-candidates.show-venue-confirmation-form');
        Route::post('/save-venue-confirmation/{examId}', [IDCandidatesController::class, 'saveVenueConfirmation'])->name('id-candidates.save-venue-confirmation');
        Route::get('/export-confirmed-halls/{examId}', [IDCandidatesController::class, 'exportToCSV'])->name('id-candidates.export-confirmed-halls');
    });
});
//disitrict-candidates Route::prefix('district-candidates')->group(function(){
Route::prefix('district-candidates')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/show-venue-intimation-form/{examId}', [DistrictCandidatesController::class, 'showVenueIntimationForm'])->name('district-candidates.showVenueIntimationForm');
        Route::post('/process-venue-consent-email', [DistrictCandidatesController::class, 'processVenueConsentEmail'])->name('district-candidates.processVenueConsentEmail');
        Route::post('/clear-saved-venues', [DistrictCandidatesController::class, 'clearSavedVenues'])->name('district-candidates.clearSavedVenues');
        Route::post('/generate-qrcode', [DistrictCandidatesController::class, 'generateQRCode'])->name('generate.qrcode');
        Route::get('/generatePdf/{qrCodeId}', [DistrictCandidatesController::class, 'generatePdf'])->name('district-candidates.generatePdf');
        Route::get('/generat-ci-meeting-report', [DistrictCandidatesController::class, 'generateCIMeetingReport'])->name('district-candidates.generat-ci-meeting-report');
    });
});
//CIMeeting Route::prefix ('ci-meetings')->group(function(){
Route::prefix('ci-meetings')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [CIMeetingController::class, 'index'])->name('ci-meetings.index');
        Route::post('/attendance-QRcode-scan', [CIMeetingController::class, 'handleAttendanceQRCodeScan'])->name('ci-meetings.attendance-QRcode-scan');
        Route::post('/update-adequacy-check', [CIMeetingController::class, 'updateAdequacyCheck'])->name('ci-meetings.updateAdequacyCheck');
    });
});
Route::prefix('ci-reports')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('generate-utilization-certificate/{examid}', [UtilityController::class, 'generateUtilizationCertificate'])->name('download.utilireport');
        Route::get('/ci-consolidate-report/{examId}/{exam_date}/{exam_session}', [CIConsolidateController::class, 'generateReport'])->name('download.report');
    });
});
Route::prefix('qp-box-log')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::post('/qp-box-open', [QpBoxlogController::class, 'saveTime'])->name('qp-box-open.save-time');
        Route::post('/qp-box-distribution', [QpBoxlogController::class, 'saveqpboxdistributiontimeTime'])->name('qp-box-distribution.save-time');
    });
});
Route::prefix('ci-paper-replacements')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::post('/save-replacement-details', [CIPaperReplacementsController::class, 'saveReplacementDetails'])->name('save.replacement.details');
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
})->name('report.consolidated.view');
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
})->name('report.view');
Route::prefix('ci-candidate-log')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::post('/ci-candidates-log', [CICandidateLogsController::class, 'saveAdditionalcandidates'])->name('ci-candidates-log.savecandidates');
        Route::post('/ci-candidates-remarks', [CICandidateLogsController::class, 'saveRemarkcandidates'])->name('ci-candidates-remark.saveremarks');
        Route::post('/ci-candidates-omrremarks', [CICandidateLogsController::class, 'saveOMRRemark'])->name('ci-candidates-omrremarks.saveomrremarks');
        Route::post('/save-candidate-attendance', [CICandidateLogsController::class, 'saveCandidateAttendance'])->name('candidate.attendance.save');
    });
});
Route::prefix('ci-checklist')->middleware(['auth.multi'])->group(function () {
    Route::post('/save', [CIPreliminaryCheckController::class, 'saveChecklist'])->name('ci-checklist.save'); // To save checklist
    Route::post('/ci-session-save', [CIPreliminaryCheckController::class, 'saveSessionChecklist'])->name('ci-session-checklist.save'); // To save checklist
    Route::post('/save-videography-checklist', [CIPreliminaryCheckController::class, 'saveVideographyChecklist'])->name('saveVideographyChecklist');
    Route::post('/save-consolidate-certificate', [CIPreliminaryCheckController::class, 'saveConsolidateCertificate'])->name('saveConsolidateCertificate');
    Route::post('/save-utilization-certificate', [CIPreliminaryCheckController::class, 'saveUtilizationCertificate'])->name('saveUtilizationCertificate');
});
Route::prefix('ci-staffalloment')->middleware(['auth.multi'])->group(function () {
    Route::post('/save-invigilator-details', [ExamStaffAllotmentController::class, 'saveInvigilatorDetails'])->name('save-invigilator.details');
    Route::post('/view-invigilator-allocate', [ExamStaffAllotmentController::class, 'allocateHallsRandomly'])->name('view-invigilator-allocate');
    Route::put('/ci-staffalloment/update-invigilator-details/{examId}/{examDate}/{ciId}', [ExamStaffAllotmentController::class, 'updateScribeDetails'])->name('update.scribe.details');
    Route::put('/update-ci-assistant-details/{examId}/{examDate}/{ciId}', [ExamStaffAllotmentController::class, 'updateCIAssistantDetails'])->name('update.ci-assistant-details');
});
// Route::prefix('ci-meetings')->group(function () {
//     Route::middleware(['auth.multi', 'role.permission:ci-meetings.index'])->group(function () {
//         Route::get('/', [CIMeetingController::class, 'index'])
//             ->name('ci-meetings.index');

//         Route::post('/attendance-QRcode-scan', [CIMeetingController::class, 'handleAttendanceQRCodeScan'])
//             ->name('ci-meetings.attendance-QRcode-scan')
//             ->middleware('role.permission:ci-meetings.attendance-QRcode-scan');

//         Route::post('/update-adequacy-check', [CIMeetingController::class, 'updateAdequacyCheck'])
//             ->name('ci-meetings.update-adequacy-check')
//             ->middleware('role.permission:ci-meetings.update-adequacy-check');
//     });
// });

//ExamMaterialsController Route::prefix('exam-materials')->group(function(){
Route::prefix('exam-materials')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/download-sample-csv', [ExamMaterialsDataController::class, 'downloadSampleCsv'])->name('exam-materials.download-sample-csv');
        Route::post('/upload', [ExamMaterialsDataController::class, 'uploadCsv'])->name('exam-materials.upload');
        Route::get('/{examId}', [ExamMaterialsDataController::class, 'index'])->name('exam-materials.index');
    });
});
Route::prefix('bundle-packaging')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/ci-bundlepackaging/{examId}/{exam_date}/{exam_session}', [BundlePackagingController::class, 'ciBundlepackagingView'])->name('ci.bundlepackaging.view');
        Route::get('/ci-to-mobileteam-bundle-packaging/{examId}/{examDate}', [BundlePackagingController::class, 'CItoMobileTeam'])->name('bundle-packaging.ci-to-mobileteam');
        Route::get('/mobileteam-to-district-bundle-packaging/{examId}', [BundlePackagingController::class, 'MobileTeamtoDistrict'])->name('bundle-packaging.mobileteam-to-district');
        Route::get('/mobileteam-to-center/{examId}', [BundlePackagingController::class, 'MobileTeamtoCenter'])->name('bundle-packaging.mobileteam-to-center');
        Route::post('/scan-disitrct-exam-materials/{examId}', [BundlePackagingController::class, 'scanDistrictExamMaterials'])->name('bundle-packaging.scan-disitrct-exam-materials');
        Route::post('/scan-chennai-disitrct-exam-materials/{examId}', [BundlePackagingController::class, 'scanVandutyHQExamMaterials'])->name('bundle-packaging.scan-chennai-disitrct-exam-materials');
        Route::post('/save-used-otl-codes', [BundlePackagingController::class, 'saveUsedOTLCodes'])->name('bundle-packaging.save-used-otl-codes');
        Route::get('/charted-vehicle-to-headquarters/{examId}', [BundlePackagingController::class, 'chartedVehicletoHeadquarters'])->name('bundle-packaging.charted-vehicle-to-headquarters');
        Route::get('/vds-to-headquarters/{examId}', [BundlePackagingController::class, 'vanDutyStafftoHeadquarters'])->name('bundle-packaging.vanduty-staff-to-headquarters');
        Route::post('/scan-hq-exam-materials', [BundlePackagingController::class, 'scanHQExamMaterials'])->name('bundle-packaging.scan-hq-exam-materials');
        Route::post('/save-handover-details', [BundlePackagingController::class, 'saveHandoverDetails'])->name('bundle-packaging.save-handover-details');
        Route::get('/report-handover-details/{id}', [BundlePackagingController::class, 'reportHandoverDetails'])->name('bundle-packaging.report-handover-details');
    });
});
//ReceiveExamMaterialsController Route::prefix('receive-exam-materials')->group(function(){
Route::prefix('receive-exam-materials')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/printer-to-disitrct-treasury/{examId}', [ReceiveExamMaterialsController::class, 'printerToDistrictTreasury'])->name('receive-exam-materials.printer-to-disitrict-treasury');
        Route::post('/scan-disitrct-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanDistrictExamMaterials'])->name('receive-exam-materials.scan-disitrct-exam-materials');
        Route::get('/printer-to-hq-treasury/{examId}', [ReceiveExamMaterialsController::class, 'printerToHQTreasury'])->name('receive-exam-materials.printer-to-hq-treasury');
        Route::post('/scan-hq-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanHQExamMaterials'])->name('receive-exam-materials.scan-hq-exam-materials');
        Route::get('/district-to-center/{examId}/', [ReceiveExamMaterialsController::class, 'districtTreasuryToCenter'])->name('receive-exam-materials.district-to-center');
        Route::post('/scan-center-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanCenterExamMaterials'])->name('receive-exam-materials.scan-center-exam-materials');
        Route::get('/sub-treasury-to-mobile-team/{examId}/{examDate}', [ReceiveExamMaterialsController::class, 'subTreasuryToMobileTeam'])->name('receive-exam-materials.sub-treasury-to-mobile-team');
        Route::post('/scan-mobile-team-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanMobileTeamExamMaterials'])->name('receive-exam-materials.scan-mobile-team-exam-materials');
        Route::get('/headquarters-to-vanduty/{examId}/{examDate}', [ReceiveExamMaterialsController::class, 'headquartersToVanDuty'])->name('receive-exam-materials.headquarters-to-vanduty');
        Route::post('/scan-vandutystaff-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanVandutystaffExamMaterials'])->name('receive-exam-materials.scan-vandutystaff-exam-materials');
        Route::get('/receive-exam-materials/{examId}/{exam_date}/{exam_session}', [ReceiveExamMaterialsController::class, 'ciReceiveMaterialsFromMobileTeam'])->name('receive-exam-materials.mobileTeam-to-ci-materials');
        Route::post('/scan-ci-exam-materials/{examId}', [ReceiveExamMaterialsController::class, 'scanCIExamMaterials'])->name('receive-exam-materials.scan-ci-exam-materials');
    });
});
//ExamMaterialsRouteController Route::prefix('exam-materials-route')->group(function(){
Route::prefix('exam-materials-route')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        // Route::get('/samplePdf', [ExamMaterialsRouteController::class, 'samplePdf'])->name('exam-materials-route.sample-pdf');
        Route::get('/{examId}', [ExamMaterialsRouteController::class, 'index'])->name('exam-materials-route.index');
        Route::get('/create/{examId}', [ExamMaterialsRouteController::class, 'createRoute'])->name('exam-materials-route.create');
        Route::get('/edit/{Id}', [ExamMaterialsRouteController::class, 'editRoute'])->name('exam-materials-route.edit');
        Route::post('/store', [ExamMaterialsRouteController::class, 'storeRoute'])->name('exam-materials-route.store');
        Route::put('/update/{Id}', [ExamMaterialsRouteController::class, 'updateRoute'])->name('exam-materials-route.update');
        Route::get('/view/{Id}', [ExamMaterialsRouteController::class, 'viewRoute'])->name('exam-materials-route.view');
    });
});

//ChartedVehicleRoutesController Route::prefix('charted-vehicle-routes')->group(function(){
Route::prefix('charted-vehicle-routes')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/', [ChartedVehicleRoutesController::class, 'index'])->name('charted-vehicle-routes.index');
        Route::get('/create', [ChartedVehicleRoutesController::class, 'createRoute'])->name('charted-vehicle-routes.create');
        Route::get('/edit/{Id}', [ChartedVehicleRoutesController::class, 'editRoute'])->name('charted-vehicle-routes.edit');
        Route::post('/store', [ChartedVehicleRoutesController::class, 'storeRoute'])->name('charted-vehicle-routes.store');
        Route::put('/update/{Id}', [ChartedVehicleRoutesController::class, 'updateRoute'])->name('charted-vehicle-routes.update');
        Route::get('/view/{Id}', [ChartedVehicleRoutesController::class, 'viewRoute'])->name('charted-vehicle-routes.view');
        Route::post('/get-districts-for-exam', [ChartedVehicleRoutesController::class, 'getDistrictsForExamIDs'])->name('charted-vehicle-routes.get-districts-for-exam');
        Route::get('/downward-journey-routes', [ChartedVehicleRoutesController::class, 'downwardJourneyRoutes'])->name('charted-vehicle-routes.downward-journey-routes');
        Route::post('/save-otl-lock-used', [ChartedVehicleRoutesController::class, 'saveOTLLockUsed'])->name('charted-vehicle-routes.save-otl-lock-used');
        Route::post('/save-gps-lock-used', [ChartedVehicleRoutesController::class, 'saveGPSLockUsed'])->name('charted-vehicle-routes.save-gps-lock-used');
        Route::post('/charted-vehicle-verification', [ChartedVehicleRoutesController::class, 'chartedVehicleVerification'])->name('charted.vehicle.verification');
        Route::get('/vehicel-report/{id}', [ChartedVehicleRoutesController::class, 'generateVehicleReport'])->name('vehicel.report.download');
        Route::get('/view-trunk-boxes/{Id}', [ChartedVehicleRoutesController::class, 'viewTrunkboxes'])->name('viewTrunkboxes');
        Route::get('/generate-trunkbox-order/{Id}', [ChartedVehicleRoutesController::class, 'generateTrunkboxOrder'])->name('generateTrunkboxOrder');
        Route::post('/scan-trunkbox-order', [ChartedVehicleRoutesController::class, 'scanTrunkboxOrder'])->name('scanTrunkboxOrder');
        Route::get('/generate-annexure-1B-report/{Id}', [ChartedVehicleRoutesController::class, 'generateAnnexure1BReport'])->name('charted-vehicle-routes.generateAnnexure1BReport');
        Route::get('/get-cv-routes-report', [ChartedVehicleRoutesController::class, 'getCvRoutesReport'])->name('charted-vehicle-routes.getCvRoutesReport');
        Route::get('/generate-cv-routes-report', [ChartedVehicleRoutesController::class, 'generateCvRoutesReport'])->name('charted-vehicle-routes.generateCvRoutesReport');
    });
});

//ReceiveExamMaterialsController Route::prefix('receive-exam-materials')->group(function(){
Route::prefix('exam-trunkbox-qr-otl-data')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        Route::get('/download-sample-csv', [ExamTrunkBoxOTLDataController::class, 'downloadSampleCsv'])->name('exam-trunkbox-qr-otl-data.download-sample-csv');
        Route::post('/upload', [ExamTrunkBoxOTLDataController::class, 'uploadCsv'])->name('exam-trunkbox-qr-otl-data.upload');
        Route::get('/{examId}', [ExamTrunkBoxOTLDataController::class, 'index'])->name('exam-trunkbox-qr-otl-data.index');
    });
});
Route::prefix('alert-notification')->group(function () {
    Route::middleware(['auth.multi'])->group(function () {
        // Add your new alert notification routes
        Route::post('/save-emergency-alert', [AlertNotificationController::class, 'saveEmergencyAlert'])->name('alert-notification.emergency-alert');
        Route::post('/save-adequacy-check', [AlertNotificationController::class, 'saveAdequacyCheck'])->name('alert-notification.adequacy-check');
    });
});
// Route::get('/run-function', [DataController::class, 'addData']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
