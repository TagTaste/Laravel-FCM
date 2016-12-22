<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchUserAvatar implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $avatarUrl)
    {
        $this->user = $user;
        $this->url = $avatarUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->user->addFoodieImage($this->url);
    }
}
