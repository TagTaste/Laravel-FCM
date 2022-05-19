<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class UserPublicReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userPublicReview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds edges between profile and product';

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
        $counter = 1;
        \App\PublicReviewProduct::whereNull('deleted_at')->chunk(200, function($products) use($counter) {
            foreach($products as $model) {
                echo $counter." | product id: ".$model['id']."\n";
                $profileIds = \App\PublicReviewProduct\Review::where('product_id',$model['id'])
                ->where('current_status',2)
                ->distinct('profile_id')
                ->pluck('profile_id')->toArray();
                            
                foreach($profileIds as $pId){
                    $model->addReviewEdge($pId);
                }
                $counter = $counter + 1;
            }
        });
    }
}
