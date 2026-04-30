<?php

namespace Bale\Ikm;

use Bale\Ikm\Console\InstallIkm;
use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Policies\IkmPolicy;
use Bale\Ikm\Services\IkmCalculatorService;
use Bale\Ikm\Services\IkmImportService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Symfony\Component\Finder\Finder;

class IkmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ikm.php', 'ikm');
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Daftarkan service classes ke Laravel service container.
     * Singleton agar instance di-reuse dalam satu request.
     */
    protected function registerServices(): void
    {
        $this->app->singleton(IkmCalculatorService::class);

        $this->app->singleton(IkmImportService::class, function ($app) {
            return new IkmImportService(
                $app->make(IkmCalculatorService::class),
            );
        });
    }

    protected function registerCommands(): void
    {
        $this->commands([
            InstallIkm::class,
        ]);
    }

    /**
     * Daftarkan policy IkmBatch ke Laravel Gate.
     * Dipanggil dari boot() agar Gate sudah siap.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(IkmBatch::class, IkmPolicy::class);
    }

    /**
     * Method boot()
     * 
     * Dipanggil setelah semua service diregistrasi.
     * Digunakan untuk load resource seperti:
     * - view
     * - migration
     * - konfigurasi
     * - Livewire component
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->registerViews();
        $this->loadMigrations();
        $this->offerPublishing();
        $this->registerPolicies();
        $this->registerLivewireComponents();
    }

    /**
     * Load migration langsung dari package.
     * 
     * Dengan ini, user bisa langsung menjalankan migration
     * tanpa harus publish file ke aplikasi utama.
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'ikm'
        );
    }

    /**
     * Publish file agar bisa diubah oleh user.
     */

    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/ikm.php' => config_path('ikm.php'),
        ], 'ikm:config');

        $this->publishes($this->getMigrations(), 'ikm:migrations');
    }

    /**
     * Mengambil semua file migration dari direktori package.
     */
    protected function getMigrations(): array
    {
        $migrations = [];
        $sourcePath = __DIR__ . '/../database/migrations/';

        // Pastikan direktori ada
        if (!is_dir($sourcePath)) {
            return $migrations;
        }

        // Loop semua file migration (baik .php maupun .stub)
        foreach (glob($sourcePath . '*.{php,stub}', GLOB_BRACE) as $file) {
            $filename = basename($file);

            // Jika file stub, ganti menjadi nama migration yang benar di aplikasi
            $targetFile = $this->getMigrationFileName($filename);

            $migrations[$file] = $targetFile;
        }

        return $migrations;
    }

    /**
     * Membuat nama file migration yang sesuai dengan timestamp laravel.
     */
    protected function getMigrationFileName(string $filename): string
    {
        $timestamp = date('Y_m_d_His');
        $migrationName = str_replace('.php.stub', '.php', $filename);

        return database_path('migrations/' . $timestamp . '_' . $migrationName);
    }

    /**
     * Registrasi semua Livewire Component yang ada di folder src/Livewire.
     * 
     * Mekanisme:
     * - Cari semua file PHP di dalam folder Livewire
     * - Pastikan class tersebut adalah turunan Livewire\Component
     * - Buat alias secara otomatis dari struktur folder
     * 
     * Contoh:
     *   src/Livewire/Dashboard.php
     *     => <livewire:ikm.dashboard />
     *
     */
    protected function registerLivewireComponents(): void
    {
        $namespace = "Bale\\Ikm\\Livewire";
        $basePath = __DIR__ . "/Livewire";

        // Jika folder Livewire tidak ada, hentikan proses
        if (!is_dir($basePath)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.php');

        foreach ($finder as $file) {
            $relativePathname = $file->getRelativePathname();

            // Normalisasi path (Windows/Linux)
            $nsPath = str_replace(['/', '\\'], '\\', $relativePathname);

            // Konversi ke FQCN (Fully Qualified Class Name)
            $class = $namespace . '\\' . Str::beforeLast($nsPath, '.php');

            // Skip jika class tidak ditemukan
            if (!class_exists($class)) {
                continue;
            }

            // Skip jika bukan turunan Livewire\Component
            if (!is_subclass_of($class, LivewireComponent::class)) {
                continue;
            }

            // Buat alias berdasarkan struktur folder (kebab-case)
            $withoutExt = Str::replaceLast('.php', '', $relativePathname);
            $segments = preg_split('#[\\/\\\\]#', $withoutExt);
            $kebab = array_map(fn($s) => Str::kebab($s), $segments);

            $alias = 'ikm.' . implode('.', $kebab);

            // Registrasi komponen ke Livewire
            LivewireComponent::component($alias, $class);
        }
    }
}
