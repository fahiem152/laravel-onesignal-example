<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// update subscription id
Route::put('/user/{id}/subscription', [App\Http\Controllers\Api\UserController::class, 'updateSubscriptionId']);

// test notification one signal by request
Route::post('/user/{id}/test-notification', [App\Http\Controllers\Api\UserController::class, 'testNotification']);
