<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MonsterController;
use App\Http\Controllers\Api\NewsController;

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

// Monster API routes
Route::get('/monsters', [MonsterController::class, 'index']);
Route::get('/monsters/{id}', [MonsterController::class, 'show']);

// News API resource routes
Route::apiResource('news', NewsController::class)->names([
    'index' => 'api.news.index',
    'store' => 'api.news.store',
    'show' => 'api.news.show',
    'update' => 'api.news.update',
    'destroy' => 'api.news.destroy',
]);