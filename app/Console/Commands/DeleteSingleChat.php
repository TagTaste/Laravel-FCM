<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteSingleChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $chatsWithoutChatType = \App\V1\Chat::where('chat_type',1)->get();
        foreach ($chatsWithoutChatType as $chat) {
            $member =\DB::table('chat_members')->where('chat_id',$chat->id)->update(['exited_on'=>null]);
            $member = \App\Chat\Member::where('chat_id',$chat->id)->get();
            $memberCount = $member->count();

            if($memberCount == 1)
            {
                echo "chat id ".$chat->id."\n";
//                \DB::table('chats')->where('id',$chat->id)->delete();
            }
            elseif($memberCount == 2)
            {
                continue;
            }
            else
            {
                echo "chat id ".$chat->id."\n";
//                \DB::table('chats')->where('id',$chat->id)->delete();
            }
        }
    }
}
