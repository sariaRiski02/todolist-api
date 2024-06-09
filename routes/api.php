<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware('Authentication')->group(function () {
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);
});
