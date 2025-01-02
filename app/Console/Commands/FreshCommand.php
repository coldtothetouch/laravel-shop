<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FreshCommand extends Command
{
    protected $signature = 'project:fresh';

    protected $description = 'Regenerate database data and delete old pictures';

    public function handle(): int
    {
        if (Storage::disk('public')->deleteDirectory('images')) {
            $this->components->info('Folder \'storage/app/images\' was successfully removed');
        }

        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);

        return self::SUCCESS;
    }
}
