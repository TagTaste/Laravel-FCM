<?php

namespace App\Console\Commands\Build\ProductReview;

use App\Collaborate;
use Illuminate\Console\Command;

class UserBatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:productreview:userbatches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild user batches according collaborate_id cache';

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
        \DB::table('collaborate_batches_assign')->orderBy('batch_id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                \Redis::sAdd("collaborate:$model->collaborate_id:profile:$model->profile_id:" ,$model->batch_id);
            }
        });;
    }
}
