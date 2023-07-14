<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserService;

class ForceLogout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:logout {--profile_id=} {--platform=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'forcefully logout users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $profile_id = $this->option('profile_id');
        $platform = $this->option('platform');

        // Conditional array to get the data based on the condition
        $condition = array('profile_id' => $profile_id, 'platform' => $platform);
    
        // Force log out users based on specific condition
        $force_logout = $this->userService->forceLogoutUser($condition);
        $this->info("Logged-out successfully!");

        if($force_logout == false)
        {
            $this->info("Something went wrong!");
        }
    }
}
