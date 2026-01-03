<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::get('/recipes', [RecipeController::class, 'index']); // Homepage recipes list
Route::get('/recipes/{slug}', [RecipeController::class, 'show']); // Recipe details
Route::get('/categories', [CategoryController::class, 'index']); // Categories list
Route::get('/categories/{slug}/recipes', [CategoryController::class, 'recipes']); // Recipes by category
Route::get('/recipes/{slug}/comments', [CommentController::class, 'index']); // View comments

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password reset routes
Route::post('/password/forgot', [\App\Http\Controllers\Api\PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [\App\Http\Controllers\Api\PasswordResetController::class, 'resetPassword']);

// Google OAuth routes
Route::get('/auth/google', [\App\Http\Controllers\Api\GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\Api\GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/google/test', [\App\Http\Controllers\Api\GoogleAuthController::class, 'testConfig']); // Route de test

// Protected routes (require authentication with Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    
    // Likes (require authentication)
    Route::post('/recipes/{slug}/like', [LikeController::class, 'toggle']);
    Route::get('/recipes/{slug}/like/check', [LikeController::class, 'check']);
    
    // Comments (posting requires authentication)
    Route::post('/recipes/{slug}/comments', [CommentController::class, 'store']);

    // Uploads (require authentication)
    Route::post('/upload/image', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadImage']);
    Route::post('/upload/video', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadVideo']);
});
