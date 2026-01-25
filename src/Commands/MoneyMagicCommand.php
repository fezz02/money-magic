<?php

namespace Fezz\MoneyMagic\Commands;

use Illuminate\Console\Command;

class MoneyMagicCommand extends Command
{
    public $signature = 'money-magic';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
