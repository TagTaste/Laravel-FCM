<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ChangePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changePassword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Password of All user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data =[];
        \DB::table('users')->where('updated_at','<','2018-06-26')->whereNotIn('id',[1,2,7,10,44,685,521,570,27,530,334])->orderBy('id')
            ->chunk(100,function ($models) {
            foreach ($models as $model) {
                $dumpUsers = \DB::table('dump_users')->where('updated_at','<','2018-06-26')->where('id',$model->id)->first();
                echo $dumpUsers->updated_at." id is ".$dumpUsers->id." password is .".$dumpUsers->password ."\n";
                echo $model->updated_at." id is ".$model->id." password is .".$model->password ."\n";
            }
        });
    }
}
