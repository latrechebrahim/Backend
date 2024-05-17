<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::get('/LoginDoctors', [AuthController::class, 'LoginDoctors']);
Route::get('/login', [AuthController::class, 'Login']);
Route::post('/login-phone', [AuthController::class, 'login_phone']);
Route::post('/sendVerificationCode', [AuthController::class, 'sendVerificationCode']);
Route::post('/changePassword', [AuthController::class, 'changePassword']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);





Route::get('/showAllUsers', [AdminController::class, 'showAllUsers']);
Route::post('/RegisterDoctors', [AdminController::class, 'RegisterDoctors']);
Route::get('/showAllDoctors', [AdminController::class, 'showAllDoctors']);
Route::delete('/deleteDoctor',[AdminController::class, 'deleteDoctor']);
Route::delete('/deletePatient',[AdminController::class, 'deletePatient']);
Route::get('/count',[AdminController::class, 'count']);
Route::get('/showAllAppointments',[AdminController::class, 'showAllAppointments']);
Route::get('/showappointment/{doctorId}/{patientId}',[AdminController::class, 'showappointment']);
Route::post('/RegisterHospital_info',[AdminController::class, 'RegisterHospital_info']);
Route::get('/showHospital_info/{id}',[AdminController::class, 'showHospital_info']);
Route::post('/updateHospital_info',[AdminController::class, 'updateHospitalInfo']);







Route::post('/update/{status}', [UserController::class, 'updateInfo']);
Route::delete('/deleteAppointment',[UserController::class, 'deleteAppointment']);
Route::post('/register', [UserController::class, 'Register']);
Route::get('/show/{Id}', [UserController::class, 'showInfo']);
Route::post('/CreateAppointment/{doctorId}/{patientId}', [UserController::class, 'CreateAppointment']);
Route::get('/showAppointments/{Id}/{status}',[UserController::class, 'showAppointments']);
Route::get('/countAppointmentsDoctor/{DoctorId}',[UserController::class, 'countAppointmentsDoctor']);
Route::post('/updateAppointments',[UserController::class, 'updateAppointments']);
Route::get('/showappointment/{patientId}/{doctorId}',[UserController::class, 'showappointment']);
Route::get('/showInfoDoctor/{Id}',[UserController::class, 'showInfoDoctor']);
Route::post('/ResetPass/{Id}/{status}',[UserController::class, 'ResetPass']);
Route::get('/showDoctors', [UserController::class, 'showDoctors']);
Route::get('/showDoctorsB', [UserController::class, 'getSpecialtyAndFirstname']);
