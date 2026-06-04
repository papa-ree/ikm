<?php

namespace Bale\Ikm\Commands;

use Bale\Ikm\IkmPermissions;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

class InstallIkm extends Command
{
    protected $signature = 'ikm:install';

    protected $description = 'Main installer for bale/ikm with interactive options';

    public function handle(): int
    {
        $this->renderHeader();

        $option = $this->choice(
            'What would you like to do?',
            [
                0 => 'Publish Assets',
                1 => 'Role & Permission Only',
                2 => 'Migration Only',
                3 => 'Seed Only',
                4 => 'Exit',
            ],
            0
        );

        try {
            switch ($option) {
                case 'Publish Assets':
                case 0:
                    $this->task('Publishing migrations and config', function () {
                        $this->call('ikm:publish');
                    });
                    break;

                case 'Role & Permission Only':
                case 1:
                    $this->task('Seeding permissions and roles to Main DB', function () {
                        $this->seedPermissions();
                        $this->seedRoles();
                    });
                    break;

                case 'Migration Only':
                case 2:
                    $this->call('ikm:migrate');
                    break;

                case 'Seed Only':
                case 3:
                    $this->call('ikm:seed');
                    break;

                case 'Exit':
                case 4:
                    $this->info('Exit installation.');
                    return self::SUCCESS;
            }

            $this->newLine();
            $this->info('  Installation step(s) completed successfully. 🎉');
            $this->newLine();

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->newLine();
            $this->error('  [ERROR] Installation failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function seedPermissions(): void
    {
        $this->info('Seeding permissions...');

        foreach (IkmPermissions::ALL as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        $this->info('Permissions seeded and updated.');

        // Force sync to root role if exists
        $rootRole = Role::where('name', 'root')->first();
        if ($rootRole instanceof Role) {
            $this->info('Force syncing IKM permissions to root role...');
            $rootRole->givePermissionTo(IkmPermissions::ALL);

            // Clear cache
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            $this->info('Permissions force synced and cache cleared for root role.');
        }
    }

    protected function seedRoles(): void
    {
        $this->info('Creating default roles...');

        // Role 1: Admin Pusat — semua permission IKM
        $adminPusat = Role::firstOrCreate(
            ['name'       => 'ikm-admin-pusat'],
            ['guard_name' => 'web'],
        );
        $adminPusat->syncPermissions(IkmPermissions::ALL);

        // Role 2: Admin OPD — permission terbatas
        $adminOpd = Role::firstOrCreate(
            ['name'       => 'ikm-admin-opd'],
            ['guard_name' => 'web'],
        );
        $adminOpd->syncPermissions(IkmPermissions::OPD);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Default roles created and permissions synced.');
    }

    protected function renderHeader(): void
    {
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>┌─────────────────────────────────┐</>');
        $this->line('  <fg=cyan;options=bold>│        bale/ikm  installer      │</>');
        $this->line('  <fg=cyan;options=bold>│   Indeks Kepuasan Masyarakat    │</>');
        $this->line('  <fg=cyan;options=bold>└─────────────────────────────────┘</>');
        $this->newLine();
    }

    protected function task(string $label, callable $callback): void
    {
        $this->line("  <fg=gray>→</> {$label}...");

        try {
            $callback();
            $this->line("  <fg=green>✓</> {$label}");
        } catch (Throwable $e) {
            $this->line("  <fg=red>✗</> {$label}");
            throw $e;
        }
    }
}
