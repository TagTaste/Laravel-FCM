<?php
namespace App\Console\Commands;
use App\Chat;
use Illuminate\Console\Command;
class ChatProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChatProfile';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change is_single in chat_member';
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
        \DB::table("chat_members")->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $dataExists = \DB::table('chat_profiles')->where('chat_id',$model->chat_id)->where('profile_id',$model->profile_id)->exists();
                if(!$dataExists)
                {
                    \DB::table('chat_profiles')->insert(['chat_id'=>$model->chat_id,'profile_id'=>$model->profile_id,'created_at'=>$model->created_at]);
                }
            }
        });
    }
}