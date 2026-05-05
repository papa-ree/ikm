<?php

namespace Bale\Ikm\Commands;

use Bale\Ikm\Models\IkmSetting;
use Bale\Cms\Models\BaleList;
use Bale\Cms\Services\TenantManager;
use Illuminate\Console\Command;
use Throwable;

class SeedIkm extends Command
{
    protected $signature = 'ikm:seed {--tenant= : The slug of the tenant}';

    protected $description = 'Seed IKM settings for a specific tenant database';

    public function handle(): int
    {
        $tenantSlug = $this->option('tenant');

        if (!$tenantSlug) {
            $tenants = BaleList::all();
            if ($tenants->isEmpty()) {
                $this->error('No tenants found in bale_lists table.');
                return self::FAILURE;
            }

            $tenantSlug = $this->choice(
                'Which tenant database do you want to seed?',
                $tenants->pluck('slug')->toArray()
            );
        }

        try {
            $tenant = BaleList::where('slug', $tenantSlug)->firstOrFail();
            
            // Initialize tenant connection specifically for CLI
            $this->info("Initializing connection for tenant: {$tenant->slug}");
            TenantManager::initializeFromBaleUuid($tenant->id);
            $connection = TenantManager::getActiveConnection();

            if (!$connection) {
                throw new \Exception("Failed to activate connection for tenant {$tenant->slug}");
            }

            $this->info("Seeding IKM settings for tenant: {$tenant->slug} (DB: {$tenant->database_name})");

            $count = $this->seedSettings($connection);

            $this->info("Successfully seeded {$count} IKM settings.");

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error("Seeding failed: " . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function seedSettings(string $connection): int
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
                IkmSetting::on($connection)->updateOrCreate(
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
}
