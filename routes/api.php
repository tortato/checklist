<?php

use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);      
    Route::get('/trash', [TaskController::class, 'trash']);      
    Route::post('/', [TaskController::class, 'store']);          
    Route::get('/{task}', [TaskController::class, 'show']);      
    Route::put('/{task}', [TaskController::class, 'update']);    
    Route::delete('/{task}', [TaskController::class, 'destroy']); 
    Route::post('/{task}/restore', [TaskController::class, 'restore']); 
});
