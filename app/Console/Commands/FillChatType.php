<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillChatType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:chat_type';

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
        $chatsWithoutChatType = \App\Chat::whereNull('chat_type')->get();
        foreach ($chatsWithoutChatType as $chat) {
            $member = \App\Chat\Member::where('chat_id',$chat->id)->first();
            if($member)
            {
                \App\Chat::where('id',$chat->id)->update(['chat_type'=>$member->is_single]); 
            }
        }
        $this->info("done");
    }
}
