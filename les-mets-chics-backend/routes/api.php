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

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication with Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Recipes
    Route::get('/recipes/{slug}', [RecipeController::class, 'show']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}/recipes', [CategoryController::class, 'recipes']);
    
    // Likes
    Route::post('/recipes/{slug}/like', [LikeController::class, 'toggle']);
    Route::get('/recipes/{slug}/like/check', [LikeController::class, 'check']);
    
    // Comments
    Route::get('/recipes/{slug}/comments', [CommentController::class, 'index']);
    Route::post('/recipes/{slug}/comments', [CommentController::class, 'store']);

    // Uploads
    Route::post('/upload/image', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadImage']);
    Route::post('/upload/video', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadVideo']);
});
