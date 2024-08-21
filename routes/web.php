<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\Treasury_OfficersController;
use App\Http\Controllers\MobileTeamStaffsController;
use App\Http\Controllers\Escort_staffsController;
use App\Http\Controllers\Incpection_officersController;
use App\Http\Controllers\Cheif_invigilatorsController;
use App\Http\Controllers\Invigilators_Controller;
use App\Http\Controllers\Scribe_Controller;
use App\Http\Controllers\CI_AssistantsController;
use App\Http\Controllers\District_CollectoratesController;
use App\Http\Controllers\VenuesController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Department_OfficialsController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\CollectorateController;
use App\Http\Controllers\ExamServiceController;

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


// Protected routes (require user to be logged in) 
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/change-password', [ChangePasswordController::class, 'showchangePassword'])->name('change-password');
    Route::get('/myaccount', [MyAccountController::class, 'index'])->name('myaccount');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/centers', [CenterController::class, 'index'])->name('centers.index');
    Route::get('/add-center', [CenterController::class, 'create'])->name('centers.create');
    Route::get('/edit-center/{center}', [CenterController::class, 'edit'])->name('centers.edit');
    Route::post('/add-center', [CenterController::class, 'store'])->name('centers.store');
    Route::put('/update-center/{center}', [CenterController::class, 'update'])->name('centers.update');
    Route::get('/treasury', [Treasury_OfficersController::class, 'index'])->name('treasury');
    Route::get('/add-treasury_officers', [Treasury_OfficersController::class, 'create'])->name('treasury_officers.create');
    Route::get('/edit-treasury_officers', [Treasury_OfficersController::class, 'edit'])->name('treasury_officers.edit');
    Route::get('/van_duty', [MobileTeamStaffsController::class, 'index'])->name('van_duty');
    Route::get('/add-van_duty', [MobileTeamStaffsController::class, 'create'])->name('van_duty.create');
    Route::get('/edit-van_duty', [MobileTeamStaffsController::class, 'edit'])->name('van_duty.edit');
    Route::get('/escort_staff', [Escort_staffsController::class, 'index'])->name('escort_staff');
    Route::get('/add-escort_staffs', [Escort_staffsController::class, 'create'])->name('escort_staffs.create');
    Route::get('/edit-escort_staffs', [Escort_staffsController::class, 'edit'])->name('escort_staffs.edit');
    Route::get('/incpection', [Incpection_officersController::class, 'index'])->name('incpection');
    Route::get('/incpection/create', [Incpection_officersController::class, 'create'])->name('incpection_officers.create');
    Route::get('/incpection/edit', [Incpection_officersController::class, 'edit'])->name('incpection_officers.edit');
    Route::get('/cheif_invigilator', [Cheif_invigilatorsController::class, 'index'])->name('cheif_invigilator');
    Route::get('/add-cheif_invigilator', [Cheif_invigilatorsController::class, 'create'])->name('cheif_invigilator.create');
    Route::get('/edit-cheif_invigilator', [Cheif_invigilatorsController::class, 'edit'])->name('cheif_invigilator.edit');
    Route::get('/invigilator', [Invigilators_Controller::class, 'index'])->name('invigilator');
    Route::get('/add-invigilator', [Invigilators_Controller::class, 'create'])->name('invigilator.create');
    Route::get('/edit-invigilator', [Invigilators_Controller::class, 'edit'])->name('invigilator.edit');
    Route::get('/scribe', [Scribe_Controller::class, 'index'])->name('scribe');
    Route::get('/add-scribe', [Scribe_Controller::class, 'create'])->name('scribe.create');
    Route::get('/edit-scribe', [Scribe_Controller::class, 'edit'])->name('scribe.edit');
    Route::get('/ci_assistants', [CI_AssistantsController::class, 'index'])->name('ci_assistants');
    Route::get('/add-ci_assistants', [CI_AssistantsController::class, 'create'])->name('ci_assistants.create');
    Route::get('/edit-ci_assistants', [CI_AssistantsController::class, 'edit'])->name('ci_assistants.edit');
    Route::get('/district_collectorates', [District_CollectoratesController::class, 'index'])->name('district_collectorates');
    Route::get('/add-district_collectorates', [District_CollectoratesController::class, 'create'])->name('district_collectorates.create');
    Route::get('/edit-district_collectorates', [District_CollectoratesController::class, 'edit'])->name('district_collectorates.edit');
    Route::get('/show-collectorate', [CollectorateController::class, 'show'])->name('collectorate.show');
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::get('/venue', [VenuesController::class, 'index'])->name('venue');
    Route::get('/add-venue', [VenuesController::class, 'create'])->name('venue.create');
    Route::get('/edit-venue', [VenuesController::class, 'edit'])->name('venue.edit');
    Route::get('/department', [Department_OfficialsController::class, 'index'])->name('department');
    Route::get('/add-department', [Department_OfficialsController::class, 'create'])->name('department.create');
    Route::get('/edit-department', [Department_OfficialsController::class, 'edit'])->name('department.edit');
    Route::get('/exam-service', [ExamServiceController::class, 'index'])->name('exam-service.index');
    Route::get('/exam-service/create', [ExamServiceController::class, 'create'])->name('exam-service.create');
    // Add other protected routes here
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
