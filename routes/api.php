<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authentication;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);


Route::middleware([Authentication::class])->group(function () {
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('/users/delete/{id}', [UserController::class, 'logout']);

    // Route::todos management
    Route::post('/todos', [TodoController::class, 'create']); // finished 
    Route::get('/todos', [TodoController::class, 'getAllbyUser']); // finished
    Route::get('/todos/{id}', [TodoController::class, 'getById']);
    Route::put('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'delete']);
    Route::get('/todos/search', [TodoController::class, 'search']);
});
