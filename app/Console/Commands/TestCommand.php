<?php

namespace App\Console\Commands;

use Faker\Generator;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'project:test';

    protected $description = 'Command for a testing purposes';

    public function handle()
    {
        //
    }
}
