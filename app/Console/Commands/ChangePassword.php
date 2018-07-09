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
        User::select('profiles.id','users.email')->join('profiles','profiles.user_id','=','users.id')
            ->whereNotIn('users.id',[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,165,44,32,160,161,176,27,253,254,255])
            ->chunk(100,function($users) use (&$data) {

                foreach ($users as $user)
                {
                    $password = sprintf("%06d", random_int(1, 999999));
                    User::where('email',$user->email)->update(['password'=>bcrypt($password)]);
                    $data[] = $user->id .",". $user->email ."," . $password ."\n";

                }
            });
        Storage::disk('local')->put('file.txt', $data);
    }
}
