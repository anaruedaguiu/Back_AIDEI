<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\HolidayController;

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
    Route::post('profile/{id}', [AuthController::class, 'profile'])->name('profile');
    Route::post('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('absences', [AbsenceController::class, 'absences'])->name('absences');
    Route::delete('deleteAbsence/{id}', [AbsenceController::class, 'deleteAbsence'])->name('deleteAbsence');
    Route::post('createAbsence', [AbsenceController::class, 'createAbsence'])->name('createAbsence');
    Route::put('updateAbsence/{id}', [AbsenceController::class, 'updateAbsence'])->name('updateAbsence');
    Route::post('showAbsence/{id}', [AbsenceController::class, 'showAbsence'])->name('showAbsence');
    Route::post('holidays', [HolidayController::class, 'holidays'])->name('holidays');
    Route::post('createHoliday', [HolidayController::class, 'createHoliday'])->name('createHoliday');
    Route::post('showHoliday/{id}', [HolidayController::class, 'showHoliday'])->name('showHoliday');
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