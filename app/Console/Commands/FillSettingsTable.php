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
    protected $signature = 'FillSettingsTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate settings table';

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
        $data = [
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
                'belongs_to' => 'profile',
                'group_name' => 'default',
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
                'belongs_to' => 'profile',
                'group_name' => 'default',
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
                'belongs_to' => 'profile',
                'group_name' => 'default',
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
                'belongs_to' => 'profile',
                'group_name' => 'default',
            ],
            [
                'title' => 'Review a company',
                'email_description' => 'You will always get an email whenever you rate & review a company.',
                'bell_visibility' => false,
                'email_visibility' => true,
                'push_visibility' => false,
                'email_active' => false,
                'email_value' => true,
                'belongs_to' => 'profile',
                'group_name' => 'default',
            ],
            [
                'title' => 'Job notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone applied to the job that you opened.',
                'push_description' => 'Notify me when there is an action like someone applied to my job.',
                'email_description' => 'Notify me when there is an action like someone applied to my job.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'profile',
                'group_name' => 'default',
            ],
            [
                'title' => 'Collaboration notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone has shown interest to the collaboration that you opened.',
                'push_description' => 'Notify me when there is an action like someone has shown interest to my collaboration',
                'email_description' => 'Notify me when there is an action like someone has shown interest to my collaboration',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'profile',
                'group_name' => 'default',
            ],
            [
                'title' => 'Weekly newsletter',
                'email_description' => 'Send me a weekly newsletter containing the summary of my activities on TagTaste such as my visitor count, followers count, applicants count for my jobs and collaborations etc.',
                'bell_visibility' => false,
                'email_visibility' => true,
                'push_visibility' => false,
                'email_active' => true,
                'email_value' => true,
                'belongs_to' => 'profile',
                'group_name' => 'newsletter',
            ],
            [
                'title' => 'Informative newsletters',
                'email_description' => 'Send me some occasional newsletters containing the community updates, news updates, and opportunities of my interest.',
                'bell_visibility' => false,
                'email_visibility' => true,
                'push_visibility' => false,
                'email_active' => true,
                'email_value' => true,
                'belongs_to' => 'profile',
                'group_name' => 'newsletter',
            ],
            [
                'title' => 'Activities by you',
                'email_description' => 'Notify me about activities i.e. posts, photos, jobs and collaborations of this company that were initiated by me.',
                'push_description' => 'Notify me about activities i.e. posts, photos, jobs and collaborations of this company that were initiated by me.',
                'bell_description' => 'You\'ll always be notified about those activities like posts, photos, jobs and collaborations of your company that were initiated by you.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => false,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Activities by other Admins',
                'bell_description' => 'You will always be notified for the activities i.e. posts, photos, jobs and collaborations of this company which were initiated by other Admins (if any).',
                'push_description' => 'You will always be notified for the activities i.e. posts, photos, jobs and collaborations of this company which were initiated by other Admins (if any).',
                'email_description' => 'Notify me about activities i.e. posts, photos, jobs and collaborations of this company which were initiated by other Admins (if any).',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => false,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Follow notifications',
                'bell_description' => 'Notify me when someone follows my company',
                'push_description' => 'Notify me when someone follows my company',
                'email_description' => 'Notify me when someone follows my company',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => true,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => false,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Company admin',
                'bell_description' => 'You will always be notified when someone is added or removed as an admin of your company.',
                'push_description' => 'Notify me when someone is added or removed as an admin of my company.',
                'email_description' => 'Notify me when someone is added or removed as an admin of my company.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Company rating',
                'bell_description' => 'You\'ll always be notified when someone rates & reviews your company.',
                'push_description' => 'Notify me when someone rates & reviews my company.',
                'email_description' => 'Notify me when someone rates & reviews my company.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Job notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone applied to the job that your company opened.',
                'push_description' => 'Notify me when there is an action like someone applied to the job that my company opened.',
                'email_description' => 'Notify me when there is an action like someone applied to the job that my company opened.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],
            [
                'title' => 'Collaboration notifications',
                'bell_description' => 'You\'ll always be notified when there is an action like someone had shown interest to the collaboration that your company opened.',
                'push_description' => 'Notify me when there is an action like someone has shown interest to the collaboration that my company opened.',
                'email_description' => 'Notify me when there is an action like someone has shown interest to the collaboration that my company opened.',
                'bell_visibility' => true,
                'email_visibility' => true,
                'push_visibility' => true,
                'bell_active' => false,
                'email_active' => true,
                'push_active' => true,
                'bell_value' => true,
                'email_value' => true,
                'push_value' => true,
                'belongs_to' => 'company',
                'group_name' => 'default',
            ],

        ];
        foreach ($data as $d) {
            \DB::table('settings')->insert($d);
        }

        $actions = [
            [
                'setting_id' => 1,
                'action' => 'like',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 1,
                'action' => 'comment',
                'belongs_to' => 'profile',

            ],
            [
                'setting_id' => 1,
                'action' => 'share',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 1,
                'action' => 'tag',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 2,
                'action' => 'joinfriend',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 3,
                'action' => 'follow',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 4,
                'action' => 'admin',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 5,
                'action' => 'rating',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 6,
                'action' => 'apply',
                'model' => 'job',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 6,
                'action' => 'expire',
                'model' => 'job',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 6,
                'action' => 'reopen',
                'model' => 'job',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 6,
                'action' => 'expire model',
                'model' => 'job',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 7,
                'action' => 'apply',
                'model' => 'collaborate',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 7,
                'action' => 'expire',
                'model' => 'collaborate',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 7,
                'action' => 'reopen',
                'model' => 'collaborate',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 7,
                'action' => 'expire model',
                'model' => 'collaborate',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 8,
                'action' => 'newsletter',
                'sub_action' => 'weekly',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 9,
                'action' => 'newsletter',
                'sub_action' => 'informative',
                'belongs_to' => 'profile',
            ],
            [
                'setting_id' => 10,
                'action' => 'like',
                'sub_action' => 'onlyme',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 10,
                'action' => 'comment',
                'sub_action' => 'onlyme',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 10,
                'action' => 'share',
                'sub_action' => 'onlyme',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 11,
                'action' => 'like',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 11,
                'action' => 'comment',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 11,
                'action' => 'share',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 12,
                'action' => 'follow',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 13,
                'action' => 'admin',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 14,
                'action' => 'rating',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 15,
                'action' => 'apply',
                'model' => 'job',
                'belongs_to' => 'company',
            ],
            [
                'setting_id' => 16,
                'action' => 'apply',
                'model' => 'collaborate',
                'belongs_to' => 'company',
            ],

        ];

        foreach ($actions as $a) {
            \DB::table('settings_action')->insert($a);
        }
        echo "\nDONE...\n";
    }
}
