<?php

namespace App\Jobs;

use App\Deeplink;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SuperAdminMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $new_super_admin;
    public $old_super_admin;

    public function __construct($old_super_admin, $new_super_admin)
    {
        $this->new_super_admin = $new_super_admin;
        $this->old_super_admin = $old_super_admin;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->old_super_admin;
        $data['new_super_admin_url'] = Deeplink::getShortLink('profile',$data['new_super_admin_id']);
        //mail to old super admin
        Mail::send('emails.super-admin-access', ["data" => $data], function($message) use($data){
            $message->to($data['email'], $data['name'])->subject('Super admin access transferred');
        });
        
        $data = $this->new_super_admin;
        $data['old_super_admin_url'] = Deeplink::getShortLink('profile',$data['old_super_admin_id']);
        $data['company_url'] = Deeplink::getShortLink('company',$data['company_id']);
        //mail to new super admin
        Mail::send('emails.super-admin-user', ["data" => $data], function($message) use($data){
            $message->to($data['email'], $data['name'])->subject('Super admin access');
        });       
    }
}
