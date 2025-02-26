<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TaskController;
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

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});  

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(PostController::class)->group(function(){
        Route::post('post/store', 'store');
        Route::get('post/{id}', 'show');
        Route::get('posts', 'index');
    });

    Route::controller(TaskController::class)->group(function(){
        Route::post('task/store', 'store');
        Route::put('task/update/{id}', 'update');
        Route::get('get/pending/task', 'pendingTask');
    });
});
