<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UitilisateurController;
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

Route::post('/register', [AuthController::class,'register'])->name('register');
Route::post('/login', [AuthController::class,'login'])->name('register');
// Routes pour les questions
Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::post('/add-questions', [QuestionController::class, 'store']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

// Routes pour les réponses
Route::get('/reponses', [AnswerController::class, 'index']);
Route::get('/reponses/{id}', [AnswerController::class, 'show']);
Route::post('/reponses', [AnswerController::class, 'store']);
Route::put('/reponses/{id}', [AnswerController::class, 'update']);
Route::delete('/reponses/{id}', [AnswerController::class, 'destroy']);

// Routes pour les utilisateurs
Route::get('/user', [UitilisateurController::class, 'index']);
Route::get('/user/{id}', [UitilisateurController::class, 'show']);
Route::post('/add-user', [UitilisateurController::class, 'store']);
Route::put('/user/{id}', [UitilisateurController::class, 'update']);
Route::delete('/user/{id}', [UitilisateurController::class, 'destroy']);
// Routes pour les utilisateurs
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::post('/add-roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

// Routes pour les thèmes
Route::get('/themes', [ThemeController::class, 'index']);
Route::get('/themes/{id}', [ThemeController::class, 'show']);
Route::post('/themes', [ThemeController::class, 'store']);
Route::put('/themes/{id}', [ThemeController::class, 'update']);
Route::delete('/themes/{id}', [ThemeController::class, 'destroy']);
