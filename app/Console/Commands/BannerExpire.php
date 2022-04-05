<?php

namespace App\Console\Commands;

use App\Company;
use App\Events\ExpirePoll;
use App\Notify\Profile;
use App\Recipe\Company as RecipeCompany;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BannerExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:banner';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when banner is expired';
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

        DB::table('landing_banner')->where('expires_on','<=',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
        ->orderBy('id')->chunk(100, function ($models) {

                foreach ($models as $model) {

                    $mData = $model;
                  
                    DB::table('landing_banner')->where('id', $mData->id)->update(['is_active' => 0, 'deleted_at' => null]);
         
                }
            });
    }
}
