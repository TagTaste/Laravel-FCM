<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillSettingsTable extends Command
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
        DB::table('settings')->insert([
            [
                'title' => 'Activities related to you',
                'subtitle' => 'Notify me for all the activities that are directly related to me such as likes, shares or comments on my posts etc.',
            ],
            [
                'title' => 'Friends onboarded',
                'subtitle' => 'Notify me when people whom I have invited join TagTaste.',
            ],
            [
                'title' => 'Follow notifications',
                'subtitle' => 'Notify me when someone follows me.',
            ],
            [
                'title' => 'Company admin',
                'subtitle' => 'Notify me when I\'m added or removed as an admin of some company',
            ],
            [
                'title' => 'Review a company',
                'subtitle' => 'You will always get an email whenever you rate & review a company.',
            ],
            [
                'title' => 'Job notifications',
                'subtitle' => 'You\'ll always be notified when there is an action like someone applied to the job that you opened.',
            ],
            [
                'title' => 'Collaboration notifications',
                'subtitle' => 'You\'ll always be notified when there is an action like someone had shown interest to the collaboration that you opened.',
            ],
            [
                'title' => 'Weekly newsletter',
                'subtitle' => 'Send me a weekly newsletter containing the summary of my activities on TagTaste such as my visitor count, followers count, applicants count for my jobs and collaborations etc.',
            ],

        ]);
    }
}
