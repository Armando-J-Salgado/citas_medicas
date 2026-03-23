<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Routes that do not require Authentication
Route::post("/v1/auth/login", [AuthController::class,"login"]);

Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {

    //Routes that require Authentication go here
    
    //Authtentication routes
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class,'logout']);

});