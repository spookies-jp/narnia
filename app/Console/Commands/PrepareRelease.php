<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PrepareRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:prepare-release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'バージョニングを行い、リリース準備を行う。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    
        //
    }
}
