<?php

namespace App\Console\Commands\Api;

use Illuminate\Console\Command;

class Version extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version {compatible} {latest?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets current API version';

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
        $version = \App\Version::first();
        
        if(!$version){
            $version = new \App\Version();
        }
        
        $version->compatible_version = $this->argument('compatible');
        $version->latest_version = $this->argument('latest');
        $version->save();
        
    }
}
