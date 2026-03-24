<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PacientController;

//Routes that do not require Authentication
Route::post("/v1/auth/login", [AuthController::class,"login"]);

Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {

    //Routes that require Authentication go here
    
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