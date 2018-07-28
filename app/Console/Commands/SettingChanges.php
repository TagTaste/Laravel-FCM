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
        \DB::table('settings')->where('id',1)->update(["bell_description" => "Notify me when anyone likes, shares or comments on my posts.","email_description" => "Notify me when anyone likes, shares or comments on my posts.","push_description" => "Notify me when anyone likes, shares or comments on my posts.","title" => "Activities related to me","email_active" => 0, "email_value" => 1]);
        \DB::table('settings')->where('id',3)->update(["title" => "Followers","bell_description" => "Notify me when I gain a new follower.","email_description" => "Notify me when I gain a new follower.","push_description" => "Notify me when I gain a new follower."]);
        \DB::table('settings')->where('id',4)->update(["title" => "Company pages","email_description" => "Notify me when I’m added or removed as the administrator on a company’s page.","bell_description" => "Notify me when I’m added or removed as the administrator on a company’s page.","push_description" => "Notify me when I’m added or removed as the administrator on a company’s page."]);
        \DB::table('settings')->where('id',5)->update(["title" => "Company reviews"]);
        \DB::table('settings')->where('id',6)->update(["title" => "Jobs","bell_description" => "Notify me when someone applies to a job I posted.","email_description" => "Notify me when someone applies to a job I posted.","push_description" => "Notify me when someone applies to a job I posted."]);
        \DB::table('settings')->where('id',7)->update(["title" => "Collaborations","bell_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted.","email_description" => "Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted.","push_description" =>"Notify me when someone likes, shares, comments on or shows interest in a collaboration I posted." ]);
        \DB::table('settings')->where('id',8)->update(["title" => "Activity Summary","email_description" => "Send me a summary of my TagTaste activities – number of profile visits and followers, responses to my posts, jobs, collaborations etc.","bell_description"=>"Send me a summary of my TagTaste activities – number of profile visits and followers, responses to my posts, jobs, collaborations etc."]);
        \DB::table('settings')->where('id',9)->update(["title" => "Important Information","bell_description"=>"Send me newsletters about community updates, industry-related news and interesting professional opportunities.","email_description"=>"Send me newsletters about community updates, industry-related news and interesting professional opportunities."]);
        $this->info("All updation done successfully");
    }
}
