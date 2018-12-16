<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChatFileModify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateFileURL';

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

    /**https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/profile/3/chat/129/file/Screen Shot 2017-10-09 at 1.57.34 PM.png
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $messages = \DB::table('chats')->get();
        foreach ($messages as $message) {
            \DB::table('chats')->where('id',$message->id)->update(['image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/'.$message->image]);
        }
    }
}
