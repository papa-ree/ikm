<?php

namespace Bale\Ikm\Commands;

use Illuminate\Console\Command;

class IkmCommand extends Command
{
    public $signature = 'ikm';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
