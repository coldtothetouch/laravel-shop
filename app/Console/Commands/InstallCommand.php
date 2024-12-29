<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{

    protected $signature = 'project:install';

    protected $description = 'Installation of project';

    public function handle(): int
    {
        $this->call('storage:link');
        $this->call('migrate', ['--seed' => true]);

        return self::SUCCESS;
    }

}