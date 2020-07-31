<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Sms\Controllers\AdminTemplatesController;
use OZiTAG\Tager\Backend\Sms\Controllers\AdminLogsController;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/sms/logs', [AdminLogsController::class, 'index']);

    Route::get('/sms/templates', [AdminTemplatesController::class, 'index']);
    Route::get('/sms/templates/{id}', [AdminTemplatesController::class, 'view']);
    Route::put('/sms/templates/{id}', [AdminTemplatesController::class, 'update']);
});
