<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/posts/{post:slug}/related', [PostController::class, 'related']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag:slug}', [TagController::class, 'show']);
Route::get('/authors/{author}', [AuthorController::class, 'show']);

// Comments
Route::get('/posts/{post:slug}/comments', [CommentController::class, 'index']);
Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store']);

// Engagement
Route::post('/posts/{post:slug}/like', [LikeController::class, 'toggle']);

// Forms
Route::post('/contact', [ContactController::class, 'store']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Admin routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/uploads/images', [UploadController::class, 'store']);

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    Route::get('/admin/posts', [PostController::class, 'indexAdmin']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});
