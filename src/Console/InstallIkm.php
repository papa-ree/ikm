<?php

namespace Bale\Ikm\Console;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmSetting;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

/**
 * Command installer package bale/ikm.
 *
 * Menjalankan:
 *  1. Migrasi tabel ikm_*
 *  2. Seed permissions Spatie
 *  3. Buat 2 role default (ikm-admin-pusat, ikm-admin-opd)
 *  4. Seed ikm_settings dengan nilai kategori default
 */
class InstallIkm extends Command
{
    protected $signature = 'ikm:install';

    protected $description = 'Install package bale/ikm: jalankan migrasi, seed permissions, dan seed settings kategori IKM.';

    public function handle(): int
    {
        $this->renderHeader();

        $permCount   = 0;
        $rolesCount  = 0;
        $settingCount = 0;

        try {

            // ── Step 1: Migrasi ──────────────────────────────
            $this->task('Menjalankan migrasi tabel ikm_*', function () {
                $this->callSilently('migrate', ['--force' => true]);
            });

            // ── Step 2: Seed Permissions ──────────────────────
            $this->task('Menyiapkan permissions', function () use (&$permCount) {
                $permCount = $this->seedPermissions();
            });

            // ── Step 3: Seed Roles ────────────────────────────
            $this->task('Membuat role default', function () use (&$rolesCount) {
                $rolesCount = $this->seedRoles();
            });

            // ── Step 4: Seed Settings ─────────────────────────
            $this->task('Menyimpan konfigurasi kategori IKM', function () use (&$settingCount) {
                $settingCount = $this->seedSettings();
            });

            // ── Ringkasan ─────────────────────────────────────
            $this->newLine();
            $this->line('  <fg=green>✓</> Migrasi        selesai');
            $this->line("  <fg=green>✓</> Permissions    <fg=yellow>{$permCount}</> permission dibuat/diperbarui");
            $this->line("  <fg=green>✓</> Roles          <fg=yellow>{$rolesCount}</> role dibuat/diperbarui");
            $this->line("  <fg=green>✓</> Settings       <fg=yellow>{$settingCount}</> konfigurasi disimpan");
            $this->newLine();
            $this->info('  Package bale/ikm siap digunakan. 🎉');
            $this->newLine();

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->newLine();
            $this->error('  [ERROR] Instalasi gagal: ' . $e->getMessage());
            $this->line('  <fg=gray>' . $e->getFile() . ':' . $e->getLine() . '</>');
            $this->newLine();

            return self::FAILURE;
        }
    }

    // ─────────────────────────────────────────────
    // Steps
    // ─────────────────────────────────────────────

    /**
     * Seed seluruh permission IKM ke tabel permissions Spatie.
     *
     * @return int Jumlah permission yang diproses
     */
    protected function seedPermissions(): int
    {
        $count = 0;

        foreach (IkmPermissions::ALL as $perm) {
            Permission::firstOrCreate(
                ['name'       => $perm],
                ['guard_name' => 'web'],
            );
            $count++;
        }

        // Bersihkan cache permission Spatie agar langsung aktif
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Berikan semua permission IKM ke role 'root' jika ada
        $root = Role::where('name', 'root')->first();
        if ($root) {
            $ikmPerms = Permission::whereIn('name', IkmPermissions::ALL)->get();
            $root->givePermissionTo($ikmPerms);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $count;
    }

    /**
     * Buat dua role default IKM jika belum ada.
     *
     * @return int Jumlah role yang diproses
     */
    protected function seedRoles(): int
    {
        $count = 0;

        // Role 1: Admin Pusat — semua permission IKM
        $adminPusat = Role::firstOrCreate(
            ['name'       => 'ikm-admin-pusat'],
            ['guard_name' => 'web'],
        );
        $adminPusat->syncPermissions(
            Permission::whereIn('name', IkmPermissions::ALL)->get()
        );
        $count++;

        // Role 2: Admin OPD — permission terbatas
        $adminOpd = Role::firstOrCreate(
            ['name'       => 'ikm-admin-opd'],
            ['guard_name' => 'web'],
        );
        $adminOpd->syncPermissions(
            Permission::whereIn('name', IkmPermissions::OPD)->get()
        );
        $count++;

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $count;
    }

    /**
     * Seed ikm_settings dengan nilai batas kategori dari config.
     *
     * Contoh key yang di-generate:
     *   kategori_a_min, kategori_a_max, kategori_a_label
     *   kategori_b_min, kategori_b_max, kategori_b_label
     *   dst.
     *
     * @return int Jumlah setting yang disimpan
     */
    protected function seedSettings(): int
    {
        $kategori = config('ikm.kategori', []);
        $count    = 0;

        foreach ($kategori as $kode => $values) {
            $kodeLC = strtolower($kode); // 'A' → 'a'

            $entries = [
                "kategori_{$kodeLC}_min"   => [
                    'value' => (string) $values['min'],
                    'label' => "Batas Bawah Kategori {$kode}",
                    'group' => 'kategori',
                ],
                "kategori_{$kodeLC}_max"   => [
                    'value' => (string) $values['max'],
                    'label' => "Batas Atas Kategori {$kode}",
                    'group' => 'kategori',
                ],
                "kategori_{$kodeLC}_label" => [
                    'value' => $values['label'],
                    'label' => "Label Kategori {$kode}",
                    'group' => 'kategori',
                ],
            ];

            foreach ($entries as $key => $attrs) {
                IkmSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $attrs['value'],
                        'label' => $attrs['label'],
                        'group' => $attrs['group'],
                    ]
                );
                $count++;
            }
        }

        return $count;
    }

    // ─────────────────────────────────────────────
    // UI Helpers
    // ─────────────────────────────────────────────

    /**
     * Tampilkan ASCII art header installer.
     */
    protected function renderHeader(): void
    {
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>┌─────────────────────────────────┐</>');
        $this->line('  <fg=cyan;options=bold>│        bale/ikm  installer      │</>');
        $this->line('  <fg=cyan;options=bold>│   Indeks Kepuasan Masyarakat    │</>');
        $this->line('  <fg=cyan;options=bold>└─────────────────────────────────┘</>');
        $this->newLine();
    }

    /**
     * Jalankan sebuah step dengan tampilan status ✓ / ✗.
     */
    protected function task(string $label, callable $callback): void
    {
        $this->line("  <fg=gray>→</> {$label}...");

        try {
            $callback();
            $this->line("  <fg=green>✓</> {$label}");
        } catch (Throwable $e) {
            $this->line("  <fg=red>✗</> {$label}");
            throw $e; // bubble up ke handler utama
        }
    }
}
