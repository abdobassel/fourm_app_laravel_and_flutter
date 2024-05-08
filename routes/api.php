<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::post('posts/store', [PostController::class, 'store'])->middleware('auth:sanctum');

Route::get('posts', [PostController::class, 'index'])->middleware('auth:sanctum'); //x
Route::post('posts/like/{post_id}', [LikeController::class, 'likePost'])->middleware('auth:sanctum');
