<?php

namespace App\Console\Commands;

use App\Polling;
use Illuminate\Console\Command;

class syncPollElasticSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $getData = Polling::whereNull("deleted_at")->get();

        foreach($getData as $v){
            echo "syncing ".$v->id." ".substr($v->title,0,20)."<br/>";
            \App\Documents\Poll::create($v);
        }

        return "sync completed";
    }
}
