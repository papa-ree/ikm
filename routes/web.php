<?php

use Bale\Cms\Middleware\EnsureBaleSelected;
use Bale\Cms\Middleware\SwitchBaleConnection;
use Bale\Ikm\Livewire\Overview;
use Bale\Ikm\Livewire\IkmDetail;
use Bale\Ikm\Livewire\IkmList;
use Bale\Ikm\Livewire\Settings;
use Bale\Ikm\Livewire\Upload;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| IKM Routes
|--------------------------------------------------------------------------
|
| Diintegrasikan ke dalam prefix /cms agar sesuai dengan navigasi sidebar Bale CMS.
| Menggunakan middleware tenant agar koneksi database sesuai dengan bale yang aktif.
|
*/

Route::middleware(['web', 'auth'])->prefix('cms/ikm')->name('ikm.')->group(function () {

    Route::middleware([EnsureBaleSelected::class, SwitchBaleConnection::class])->group(function () {

        // Overview IKM
        Route::get('/overview', Overview::class)->name('overview');

        // Riwayat & Detail Batch
        Route::get('/batches', IkmList::class)->name('list');
        Route::get('/batches/{id}', IkmDetail::class)->name('detail');

        // Import Data IKM
        Route::get('/upload', Upload::class)->name('upload');

        // Pengaturan Variabel IKM
        Route::get('/settings', Settings::class)->name('settings');
    });
});
