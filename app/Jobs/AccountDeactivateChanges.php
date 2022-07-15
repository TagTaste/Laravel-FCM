<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AccountDeactivateChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $profile_id;
    public function __construct($profile_id)
    {
        $this->profile_id = $profile_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //deactivate feed
        file_put_contents(storage_path("logs") . "/nikhil_delete.txt", $this->profile_id, FILE_APPEND); 
        echo $this->profile_id;
    }
}
