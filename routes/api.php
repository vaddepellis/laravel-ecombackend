<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/',[UserController::class,'index']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

Route::middleware('jwt')->group(function () {
    Route::post('/add-category', [CategoryController::class,'addCategory']);
});











//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
