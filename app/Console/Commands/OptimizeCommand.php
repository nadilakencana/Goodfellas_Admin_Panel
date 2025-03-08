<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimaze:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan php artisan optimize';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Menjalankan perintah optimize
        $this->call('optimize');

        // Output ke terminal
        $this->info('Optimize command berhasil dijalankan.');
    }
}
