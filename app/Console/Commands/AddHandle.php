<?php

namespace App\Console\Commands;

use App\Profile;
use App\User;
use Illuminate\Console\Command;

class AddHandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addHandleOfProfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add handle of profile';

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
        Profile::whereNull('handle')->whereNull('deleted_at')->chunk(50,function($models){
            foreach ($models as $model)
            {
                $name = $model->name;
                $name = str_replace(' ', '.', $name);

                echo "handle is $name for profile id $model->id \n\n";
                $name = rtrim($name,'.');

                $hanleExist = Profile::where('handle',$name)->exists();
                if($hanleExist)
                {
                    $name = $name.'.'.mt_rand(100,999);
                }
                echo "new handle is $name for profile id $model->id \n\n";
                Profile::where('id',$model->id)->update(['handle'=>$name]);
            }
        });
    }
}
