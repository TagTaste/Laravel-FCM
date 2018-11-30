<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillTableRecepient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:message_recepient';

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
        $messages = \DB::table('chat_messages')->get();
        foreach ($messages as $message) {
            $chat_members = \DB::table('chat_members')->where('chat_id',$message->chat_id)->get();
            
            foreach ($chat_members as $member) {
                # code...
                if($member->profile_id != $message->profile_id)
                \DB::table('message_recepients')->insert(['recepient_id'=>$member->profile_id, 'message_id'=>$message->id, 'sent_on'=>$message->created_at, 'chat_id'=>$message->chat_id]);
            }
        }
    }
}
