<?php
namespace App\Console\Commands;
use App\Chat;
use Illuminate\Console\Command;
class ChatMergeMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChatMergeMessage';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'single chats between two woll be merge into 1 chat ids';
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


        Chat::orderBy('id')->chunk(200,function($models){
            foreach ($models as $model)
            {
                $memberIds = \DB::table('chat_members')->where('chat_id',$model->id)->where('is_single',1)->get()->pluck('profile_id');
                $chatIds = \DB::table('chat_members')->whereIn('profile_id',$memberIds)->where('is_single',1)->get()->pluck('chat_id');
                $messageChatId = \DB::table('chat_messages')->whereIn('chat_id',$chatIds)->orderBy('created_at','desc')->first();
                foreach ($chatIds as $chatId)
                {
                    $count = 0;
                    echo "chat id is here ".$chatId."\n";
                    $checkChat = Chat::where('id',$chatId)->whereNull('name')->exists();
                    if($checkChat)
                    {
//                        $count = \DB::table('chat_messages')->where('chat_id',$chatId)->update(['chat_id'=>$messageChatId]);
                        echo "new count is here ".$count."\n";
                    }
                }

            }

        });

    }
}