<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\SendCollabEvent;

class SendCollabSuggestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:collabSuggestion';

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
        $users = \App\User::whereNull('deleted_at')->get();
        event(new SendCollabEvent($users));
        $this->info("mail sent");
    }
}
