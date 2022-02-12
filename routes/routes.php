<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Sms\Enums\SmsScope;
use OZiTAG\Tager\Backend\Rbac\Facades\AccessControlMiddleware;
use OZiTAG\Tager\Backend\Sms\Controllers\AdminTemplatesController;
use OZiTAG\Tager\Backend\Sms\Controllers\AdminLogsController;

Route::group(['prefix' => 'admin/sms', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/logs', [AdminLogsController::class, 'index'])->middleware([
        AccessControlMiddleware::scopes(SmsScope::ViewLogs->value)
    ]);

    Route::get('/templates', [AdminTemplatesController::class, 'index'])->middleware([
        AccessControlMiddleware::scopes(SmsScope::ViewTemplates->value)
    ]);

    Route::get('/templates/{id}', [AdminTemplatesController::class, 'view'])->middleware([
        AccessControlMiddleware::scopes(SmsScope::ViewTemplates->value)
    ]);

    Route::put('/templates/{id}', [AdminTemplatesController::class, 'update'])->middleware([
        AccessControlMiddleware::scopes(SmsScope::EditTemplates->value)
    ]);
});
