<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('register', [UserController::class, 'register'])->middleware('isadmin');
    Route::post('home', [UserController::class, 'home'])->name('home');
    Route::delete('deleteUser/{id}', [UserController::class, 'destroy'])->name('deleteUser')->middleware('isadmin');
    Route::put('update/{id}', [UserController::class, 'update'])->name('update')->middleware('isadmin');

});



