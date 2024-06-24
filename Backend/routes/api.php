<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("v1")->group(function(){

    //Authentication
    Route::post("/login", [LoginController::class, "login"])->name("api.login");
    Route::post("/login/verify", [LoginController::class, "verify"])->name("api.login.verify");
    

    Route::middleware('auth:sanctum')->group(function(){
        //Driver Endpoints
       Route::get('/driver',[DriverController::class, "show"])->name("api.driver");
       Route::post('/driver',[DriverController::class, "update"])->name("api.driver.store");

       //Trip Endpionts
       Route::post("/trip", [TripController::class,"store"])->name("api.trip.store");
       Route::get("/trip/{trip}", [TripController::class,"show"])->name("api.trip");
       Route::post("/trip/{trip}/accept", [TripController::class,"accept"])->name("api.trip.accept");
       Route::post("/trip/{trip}/status", [TripController::class,"status"])->name("api.trip.status");
       Route::post("/trip/{trip}/driverLocation", [TripController::class,"driverLocation"])->name("api.trip.driverLocation");


    });
});



Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Resource not found'
    ], 404);
});
