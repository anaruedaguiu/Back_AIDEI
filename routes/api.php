<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AbsenceController;

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
    Route::post('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('index', [AbsenceController::class, 'index'])->name('index');
    Route::delete('deleteAbsence/{id}', [AbsenceController::class, 'deleteAbsence'])->name('deleteAbsence');

});

Route::group([
    'middleware' => [
        'isadmin',
        'api'
    ],
], function () {

    // for all admins
    Route::post('registerEmployee', [UserController::class, 'registerEmployee']);
    Route::delete('deleteEmployee/{id}', [UserController::class, 'deleteEmployee'])->name('deleteEmployee');
    Route::put('updateEmployee/{id}', [UserController::class, 'updateEmployee'])->name('updateEmployee');
});




