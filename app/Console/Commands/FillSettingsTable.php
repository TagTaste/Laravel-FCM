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
                'bell_description' => 'You will always be notified for all the activities that are directly related to you such as likes, shares or comments on your posts etc.',
                'push_description' => 'You will always be notified for all the activities that are directly related to you such as likes, shares or comments on your posts etc.',
                'email_description' => 'Notify me for all the activities that are directly related to me such as likes, shares or comments on my posts etc.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => false,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => true,
            ],
            [
                'title' => 'Friends onboarded',
                'bell_description' => 'Notify me when people whom I have invited join TagTaste.',
                'push_description' => 'Notify me when people whom I have invited join TagTaste.',
                'email_description' => 'Notify me when people whom I have invited join TagTaste.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => true,
            ],
            [
                'title' => 'Follow notifications',
                'bell_description' => 'Notify me when someone follows me.',
                'push_description' => 'Notify me when someone follows me.',
                'email_description' => 'Notify me when someone follows me.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => true,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => true,
            ],
            [
                'title' => 'Company admin',
                'bell_description' => 'You will always be notified when you are added or removed as an admin of some company.',
                'push_description' => 'Notify me when I\'m added or removed as an admin of some company.',
                'email_description' => 'Notify me when I\'m added or removed as an admin of some company.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
            ],
            [
                'title' => 'Review a company',
                'email_description' => 'You will always get an email whenever you rate & review a company.',
                'bell_visibility' => false,
                'email_visibility' => true,
                'push_visibility' => false,
                'email_active' => false,
                'email_value' => true,
            ],
            [
                'title' => 'Job notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone applied to the job that you opened.',
                'push_description' => 'You\'ll always be notified when there is an action like someone applied to the job that you opened.',
                'email_description' => 'You\'ll always be notified when there is an action like someone applied to the job that you opened.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => true,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
            ],
            [
                'title' => 'Collaboration notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone had shown interest to the collaboration that you opened.',
                'push_description' => 'You\'ll always be notified when there is an action like someone had shown interest to the collaboration that you opened.',
                'email_description' => 'You\'ll always be notified when there is an action like someone had shown interest to the collaboration that you opened.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => true,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
            ],
            [
                'title' => 'Weekly newsletter',
                'bell_description' => 'Send me a weekly newsletter containing the summary of my activities on TagTaste such as my visitor count, followers count, applicants count for my jobs and collaborations etc.',
                'push_description' => 'Send me a weekly newsletter containing the summary of my activities on TagTaste such as my visitor count, followers count, applicants count for my jobs and collaborations etc.',
                'email_description' => 'Send me a weekly newsletter containing the summary of my activities on TagTaste such as my visitor count, followers count, applicants count for my jobs and collaborations etc.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => true,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
            ],

        ]);
    }
}
