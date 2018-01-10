<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CapitalizeExpertise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capitalizeExpertise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capitalize the first letter of the expertise of all users.';

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
        \DB::table('profiles')->whereNotNull('expertise')->orderBy('id')->chunk(100, function ($models) {
           foreach ($models as $model) {
               $fixed = array();
               $expertise = explode(',',$model->expertise);
               foreach ($expertise as $exp) {
                   $fixed[] = ucfirst($exp);
               }
               $fixedExpertise = implode(',', $fixed);
               \DB::table('profiles')->where('id', $model->id)->update(['expertise'=>$fixedExpertise]);
               echo 'Profile id: '.$model->id." Done...\n";
           }
        });
    }
}
