<?php
namespace App\Console\Commands;
use App\Chat;
use Illuminate\Console\Command;
class ChatGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChatGroup';
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


        Chat::select('*')->chunk(200,function($models){
            foreach($models as $model){
                \DB::table('chat_members')->where('chat_id',$model->id)->where('profile_id',$model->profile_id)->update(['is_admin'=>1]);
                \DB::table('chat_members')->where('chat_id',$model->id)->update(['is_single'=>0]);
                $chatIds = \DB::table('chat_members')->where('chat_id',$model->id)->count();
                if($chatIds == 2 && !isset($model->name))
                {
                    \DB::table('chat_members')->where('chat_id',$model->id)->update(['is_single'=>1]);
                }
            }
        });
    }
}