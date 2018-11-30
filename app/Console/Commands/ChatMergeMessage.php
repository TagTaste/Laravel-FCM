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
                $chatIds = \DB::table('chat_members as m1')->join('chat_members as m2','m2.chat_id','=','m1.chat_id')
                    ->where('m1.profile_id',$memberIds[0])->where('m2.profile_id',$memberIds[1])->where('m1.is_single',1)->where('m2.is_single',1)
                    ->get()->pluck('m1.chat_id');

                $messageChatId = \DB::table('chat_messages')->whereIn('chat_id',$chatIds)->orderBy('created_at','desc')->first();
                foreach ($chatIds as $chatId)
                {
                    $count = 1;
                    echo "chat id is here ".$chatId."\n";
                    $checkChat = Chat::where('id',$chatId)->whereNull('name')->exists();
                    if($checkChat)
                    {
                        echo "count is here ".$count."\n";
//                        $count = \DB::table('chat_messages')->where('chat_id',$chatId)->update(['chat_id'=>$messageChatId]);
                        echo "new count is here ".$count."\n";
                    }
                }

            }

        });

    }
}