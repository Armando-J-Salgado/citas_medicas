<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

//Routes that do not require Authentication
Route::post("/v1/auth/login", [AuthController::class,"login"]);

Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {

    //Routes that require Authentication go here
    // TODO: CRUD users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // TODO: CRUD Schedule para médicos
    // For updating, the doctor must be informed that previous appointments can not be changed, but future ones could be

    // TODO: CRUD Appointment for pacients
    
    //Authtentication routes
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class,'logout']);

});