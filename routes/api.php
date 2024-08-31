<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VocabularyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/users', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('change_password', [UserController::class, 'changePassword']);
    Route::post('reset_password', [UserController::class, 'resetPassword']);
    Route::apiResource('/vocabularies', VocabularyController::class);
});

Route::post('/login', [UserController::class, 'login']);
Route::apiResource('/users', UserController::class);