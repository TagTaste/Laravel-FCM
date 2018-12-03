<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class PreviewChangesMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:PreviewChangesMessage';

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
        $messages = \DB::table('chat_messages')->whereNotNull('preview')->whereNull('deleted_at')->get();
        foreach ($messages as $message)
        {
            echo "message id is here ".$message->id."\n";
            $preview = $message->preview;
            $preview1 = trim($message->preview);
            if($preview1 == "" || empty($preview1))
                \DB::table('chat_messages')->where('id',$message->id)->update(['preview'=>null]);
            else
            {
                $preview = json_decode($preview);
                if(is_object($preview))
                {
                    echo "message id is here ".$message->id."\n";

                }
                else
                {
                    \DB::table('chat_messages')->where('id',$message->id)->update(['preview'=>$preview]);
                }
            }

        }
    }
}
