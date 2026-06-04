<?php

namespace Bale\Ikm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishIkmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ikm:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish IKM migrations and config from package to application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->publishConfig();
        $this->publishMigrations();

        return self::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $source = __DIR__ . '/../../config/ikm.php';
        $target = config_path('ikm.php');

        if (!File::exists($source)) {
            $this->error("Config source not found: {$source}");
            return;
        }

        if (File::exists($target)) {
            $this->line("Config already exists: <comment>config/ikm.php</comment>");
            return;
        }

        File::copy($source, $target);
        $this->info("Published: <info>config/ikm.php</info>");
    }

    protected function publishMigrations(): void
    {
        $sourcePath = __DIR__ . '/../../database/migrations';
        $targetPath = database_path('migrations/ikm');

        if (!File::isDirectory($sourcePath)) {
            $this->error("Source directory not found: {$sourcePath}");
            return;
        }

        if (!File::isDirectory($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
            $this->info("Created directory: {$targetPath}");
        }

        $files = File::files($sourcePath);
        $count = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            
            // Only process .php.stub files
            if (!str_ends_with($filename, '.php.stub')) {
                continue;
            }

            $targetName = str_replace('.php.stub', '.php', $filename);
            $destination = $targetPath . DIRECTORY_SEPARATOR . $targetName;

            if (File::exists($destination)) {
                $this->line("Migration already exists, skipping: <comment>{$targetName}</comment>");
                continue;
            }

            File::copy($file->getPathname(), $destination);
            $this->info("Published: <info>{$targetName}</info>");
            $count++;
        }

        if ($count > 0) {
            $this->info("Successfully published {$count} migration(s).");
        } else {
            $this->info("No new migrations were published.");
        }
    }
}
