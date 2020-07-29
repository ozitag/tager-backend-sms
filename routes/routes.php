<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Sms\Controllers\AdminController;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/sms/logs', [AdminController::class, 'logs']);
    Route::get('/sms/templates', [AdminController::class, 'templates']);
    Route::get('/sms/templates/{id}', [AdminController::class, 'view']);
    Route::put('/sms/templates/{id}', [AdminController::class, 'update']);
});
