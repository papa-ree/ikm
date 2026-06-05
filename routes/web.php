<?php

use Bale\Cms\Middleware\EnsureBaleSelected;
use Bale\Cms\Middleware\SwitchBaleConnection;
use Bale\Ikm\Livewire\Overview;
use Bale\Ikm\Livewire\Batch;
use Bale\Ikm\Livewire\Detail;
use Bale\Ikm\Livewire\Setting;
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
        Route::get('/overview', Overview::class)->middleware('permission:ikm.view')->name('overview');

        // Riwayat & Detail Batch
        Route::get('/batches', Batch\Index::class)->middleware('permission:ikm.view')->name('list');
        Route::get('/batches/{id}', Detail\Index::class)->middleware('permission:ikm.view')->name('detail');

        // Import Data IKM
        Route::get('/upload', Upload::class)->middleware('permission:ikm.upload')->name('upload');

        // Pengaturan Variabel IKM
        Route::get('/settings', Setting\Index::class)->middleware('permission:ikm.settings')->name('settings');
    });
});
