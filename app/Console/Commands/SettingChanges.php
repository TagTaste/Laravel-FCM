<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SettingChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for updating the setting options';

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
        \DB::table('settings')->where('id',2)->update(["bell_visibility" => 0, "email_visibility" => 0,"push_visibility" => 0]);
        \DB::table('settings')->where('id',1)->update(["bell_description" => "You will be always notified when anyone likes, shares or comments on your posts.","email_description" => "You will be always notified when anyone likes, shares or comments on your posts.","push_description" => "You will be always notified when anyone likes, shares or comments on your posts.","title" => "Activities related to me","email_active" => 0, "email_value" => 1]);
        \DB::table('settings')->where('id',3)->update(["title" => "Followers","bell_description" => "Notify me when I gain a new follower.","email_description" => "Notify me when I gain a new follower.","push_description" => "Notify me when I gain a new follower."]);
        \DB::table('settings')->where('id',4)->update(["title" => "Company pages","email_description" => "Notify me when I’m added or removed as the administrator on a company’s page.","bell_description" => "Notify me when I’m added or removed as the administrator on a company’s page.","push_description" => "Notify me when I’m added or removed as the administrator on a company’s page."]);
        \DB::table('settings')->where('id',5)->update(["title" => "Company reviews"]);
        \DB::table('settings')->where('id',6)->update(["title" => "Jobs","bell_description" => "Notify me when someone applies to a job I posted.","email_description" => "Notify me when someone applies to a job I posted.","push_description" => "Notify me when someone applies to a job I posted."]);
        \DB::table('settings')->where('id',7)->update(["title" => "Collaborations","bell_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted.","email_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted.","push_description" =>"Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted." ]);
        \DB::table('settings')->where('id',8)->update(["title" => "Activity Summary","email_description" => "Send me a summary of my TagTaste activities – number of profile visits and followers, responses to my posts, jobs, collaborations etc.","bell_description"=>"Send me a summary of my TagTaste activities – number of profile visits and followers, responses to my posts, jobs, collaborations etc."]);
        \DB::table('settings')->where('id',9)->update(["title" => "Important Information","bell_description"=>"Send me newsletters about community updates, industry-related news and interesting professional opportunities.","email_description"=>"Send me newsletters about community updates, industry-related news and interesting professional opportunities."]);
        \DB::table('settings')->where('id',10)->update(["bell_value" => 0,"email_value" => 0,"push_value" => 0,"title" => "Activities initiated by me","email_description" => "Notify me about only those activities (posts, photos, jobs and collaborations) that were initiated by me.", "bell_description" => "Notify me about only those activities (posts, photos, jobs and collaborations) that were initiated by me.","push_description" => "Notify me about only those activities (posts, photos, jobs and collaborations) that were initiated by me."]);
        \DB::table('settings')->where('id',11)->update(["bell_visibility" => 0, "email_visibility" => 0, "push_visibility" => 0]);
        \DB::table('settings')->where('id',12)->update(["bell_value" => 1,"email_value" => 1,"push_value" => 1,"title" => "Followers","email_description" => "Notify me when this company gains a new follower.", "bell_description" => "Notify me when this company gains a new follower.","push_description" => "Notify me when this company gains a new follower."]);
        \DB::table('settings')->where('id',15)->update(["bell_value" => 1,"email_value" => 1,"push_value" => 1,"title" => "Jobs","email_description" => "Notify me when someone applies to a job posted by this company.", "bell_description" => "Notify me when someone applies to a job posted by this company.","push_description" => "Notify me when someone applies to a job posted by this company."]);
        \DB::table('settings')->where('id',16)->update(["bell_value" => 1,"email_value" => 1,"push_value" => 1,"title" => "Collaborations","email_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration posted by this company.", "bell_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration posted by this company.","push_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration posted by this company."]);
        \DB::table('settings')->insert([
            'title' => 'Activity Summary',
            'bell_description' => 'Send me a summary of activities on this company page.',
            'push_description' => 'Send me a summary of activities on this company page.',
            'email_description' => 'Send me a summary of activities on this company page.',
            'bell_visibility' => false,
            'email_visibility' => true,
            'push_visibility' => false,
            'bell_active' => false,
            'email_active' => true,
            'push_active' => false,
            'bell_value' => true,
            'email_value' => true,
            'push_value' => true,
            'belongs_to' => 'company',
            'group_name' => 'newsletter',
        ]);
        $this->info("All updation done successfully");
    }
}
