<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PacientController;

//Routes that do not require Authentication
Route::post("/v1/auth/login", [AuthController::class,"login"]);

Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {

    //Routes that require Authentication go here
    // TODO: CRUD users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched user'], 404));;
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched user'], 404));;
    Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched user'], 404));;

    // TODO: CRUD Schedule para médicos
    // For updating, the doctor must be informed that previous appointments can not be changed, but future ones could be
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/{id}', [ScheduleController::class, 'show'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched schedule'], 404));;
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched schedule'], 404));;
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched schedule'], 404));;

    // TODO: CRUD Appointment for pacients
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched appointment'], 404));;
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched appointment'], 404));;
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched appointment'], 404));;
    
    //Authtentication routes
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class,'logout']);

    //Pacient routes
    Route::get('pacient',[PacientController::class,'index']);
    Route::post('pacient',[PacientController::class,'store']);
    Route::get('pacient/{pacient}',[PacientController::class,'show'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched patient'], 404));;
    Route::patch('pacient/{pacient}',[PacientController::class,'update']);

    //Medical History routes
    Route::get('medical-history',[MedicalHistoryController::class,'index']);
    Route::post('medical-history',[MedicalHistoryController::class,'store']);
    Route::get('medical-history/{medicalHistory}',[MedicalHistoryController::class,'show'])
    ->missing(fn () => response()
    ->json(['message' => 'There are no matches for the searched medical history'], 404));;
    Route::patch('medical-history/{medicalHistory}',[MedicalHistoryController::class,'update']);

});