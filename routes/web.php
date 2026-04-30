<?php

use Bale\Ikm\Livewire\Dashboard;
use Bale\Ikm\Livewire\IkmDetail;
use Bale\Ikm\Livewire\IkmList;
use Bale\Ikm\Livewire\Settings;
use Bale\Ikm\Livewire\Upload;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('ikm')->name('ikm.')->group(function () {
    
    // Dashboard - Perlu permission ikm.view
    Route::get('/', Dashboard::class)->name('dashboard');

    // List & Detail - Perlu permission ikm.view
    Route::get('/batches', IkmList::class)->name('list');
    Route::get('/batches/{batch}', IkmDetail::class)->name('detail');

    // Upload - Perlu permission ikm.upload
    Route::get('/upload', Upload::class)->name('upload');

    // Settings - Perlu permission ikm.settings
    Route::get('/settings', Settings::class)->name('settings');
});
