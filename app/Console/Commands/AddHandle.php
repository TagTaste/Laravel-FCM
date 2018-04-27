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
        Profile::whereNull('deleted_at')->chunk(1000,function($models){
            foreach ($models as $model)
            {
                $handle = $model->handle;
                echo "handle ".$handle ."\n";
                $handle = strtolower($handle);
                echo "new handle ".$handle."\n";

                Profile::where('id',$model->id)->update(['handle'=>$handle]);
            }
        });
    }
}
