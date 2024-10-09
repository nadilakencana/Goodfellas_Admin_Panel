<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDataServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ServerUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Server local';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Route::dispatch(request()->create('/api/getData', 'get'));
        $this->info('POST request executed successfully.');
        
        return Command::SUCCESS;
    }
}
