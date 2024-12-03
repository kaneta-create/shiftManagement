<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunDuskTestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:dusk-tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dusk test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        $this->info('Running Dusk tests...');
        Artisan::call('dusk');
    }
}
