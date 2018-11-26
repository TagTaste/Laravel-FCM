<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteDuplicateChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicate_chat';

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
        $chats = \DB::table('chats')->where('chat_type',1)->get();
        foreach ($chats as $chat) {

            \DB::table('chat_members')->whereIn('chat_id',$chats->id)
        }
    }
}
