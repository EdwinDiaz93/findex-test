<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Edwin\Onboarding\Http\Controllers\UsersController;
/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/users', [UsersController::class, 'index']);
Route::post('/users', [UsersController::class, 'store']);
Route::delete('/users/{user}',[UsersController::class,'destroy']);
Route::put('/users/{user}',[UsersController::class,'update']);
