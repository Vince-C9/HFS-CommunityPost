<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Protected Routes
Route::prefix('auth')->name('auth.')->middleware('auth:sanctum')->group(function(){
    
});

//Unprotected routes
Route::prefix('auth')->name('auth.')->middleware('guest')->group(function(){
    Route::post('/auth/register', [AuthController::class, 'createUser'])->name('register');
    Route::post('/auth/login', [AuthController::class, 'loginUser'])->name('login');
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password'); 
});
