<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\TicketController;

// Register
Route::post('register', [ApiController::class, 'register']);

//Login
Route::post('login', [ApiController::class, 'login']);

Route::group([

    "middleware" => ["auth:sanctum"]
],function(){

    //profile
    Route::get('profile', [ApiController::class, 'profile']);



    //Logout
    Route::get('logout', [ApiController::class, 'logout']);

    // 

Route::apiResource('tickets', TicketController::class);

    

});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
