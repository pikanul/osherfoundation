<?php

use App\Http\Controllers\Updater\UpdaterController;
use App\Http\Controllers\Updater\UpdaterSettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/system-information', [UpdaterSettingsController::class, 'systemInformation'])->name('system-information.index');
    Route::put('/system-information', [UpdaterSettingsController::class, 'systemInformationUpdate'])->name('system-information.update');
    Route::post('/system-information/check-update', [UpdaterSettingsController::class, 'systemInformationCheckUpdate'])->name('system-information.check-update');
    Route::post('/system-information/confirm-update', [UpdaterSettingsController::class, 'systemInformationConfirmUpdate'])->name('system-information.confirm-update');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin/system', 'as' => 'admin.system.'], function () {
    Route::get('/check-update', [UpdaterController::class, 'check'])->name('check-update');
    Route::match(['get', 'post'], '/run-update', [UpdaterController::class, 'run'])->name('run-update');
});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'system'], function () {
    Route::get('/check-update', [UpdaterController::class, 'check']);
    Route::match(['get', 'post'], '/run-update', [UpdaterController::class, 'run']);
});
