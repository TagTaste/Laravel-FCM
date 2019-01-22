<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;

class PublicReviewProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search product';

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
        \App\PublicReviewProduct::whereNull('deleted_at')->where('is_active',1)->chunk(100,function($models){
            foreach($models as $model){
                if(!isset($model->id))
                    continue;
                $this->info("Building " . $model->id);

                \App\Documents\PublicReviewProduct::create($model);
            }
        });
    }
}
