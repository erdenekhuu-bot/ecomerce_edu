<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Dump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        echo "Creating database backup...\n";
        system('cd public && pg_dump -d laravel -U admin -h localhost -p 5432 > backup.dump');
        echo "Database backup created successfully: public/backup.dump\n";
    }
}
