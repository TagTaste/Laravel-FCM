<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RegisterFromGoogle extends Command
{
    private $value;
    private $profileId;
    
    private $email;
    private $password = 'tagtaste@1234';
    private $token;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register from Google Sheet';

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
        $this->googleregister();
    }
    
    public function googleregister()
    {
        $values = \Cache::remember('values',120,function(){
            $sheetId = '1o1oMHDN33_uzqSXioEmij9U139AX8y5Z8nSIgdlB4rw';
            \Sheets::setService(\Google::make('sheets'));
            \Sheets::spreadsheet($sheetId);
            return \Sheets::sheet('Sheet1')->get();
        });
        $values->pull(0);
        $bar = $this->output->createProgressBar(count($values));
        
        foreach($values as $value){
            if(empty($value[4])){
                continue;
            }
            $this->value = $value;
            $status = $this->registerUser();
            if(!$status){
                $message = "Could not register " . $this->email;
                \Log::info($message);
                $this->error($message);
                $bar->advance();
                continue;
            }
            $this->login(); //get token
            $this->getProfileId();
            $this->uploadPhoto();
            $this->updateProfile();
            $bar->advance();
        }
        $bar->finish();
    }
    
    private function registerUser(){
        $this->email = $this->value[5];
        //$this->email = str_random(4) . "@gmail.com";
        $data = [
            'user'=> [
                'name' =>$this->value[3],
                'email'=>$this->email,
                'password'=>$this->password,
                'password_confirmation'=>$this->password
            ]
        ];
        $data = $this->getResponse(url('/api/user/register'),'post',['form_params'=>$data]);
        $data = json_decode($data);
        return $data->status == 'success';
    }
    
    private function login(){
        $data = ['email'=>$this->email,'password'=>$this->password];
        $response = $this->getResponse(url('/api/login'),'post',['form_params'=>$data]);
        $response = json_decode($response);
        $this->token = $response->token;
    }
    
    private function getProfileId(){
        $data = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]];
        $response = $this->getResponse(url('/api/profile/'),'get',$data);
        $response = json_decode($response);
        $this->profileId = $response->profile->id;
    }
    
    private function uploadPhoto(){
        if(empty($this->value[4])){
            return;
        }
        $data = [
            'multipart' => [
                [ 'name'=> 'image',
                    'contents' => fopen($this->value[4],'r')],
                ['name'=>'_method','contents'=>'patch']
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]
        ];
        $this->getResponse(url('/api/profile/' . $this->profileId),'post',$data);
    }
    
    private function updateProfile(){
        $data = [
            'form_params' => [
                '_method' => 'patch',
                'profile' => [
                    'country_code'  => $this->getValue(6),
                    'phone'  => $this->getValue(7),
                    'dob'  => $this->getValue(8),
                    'about'  => $this->getValue(9),
                    'address'  => $this->getValue(10),
                    'city'  => $this->getValue(11),
                    'country'  => $this->getValue(13),
                    'pincode'  => $this->getValue(14),
                    'keywords'  => $this->getValue(15)
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ]
        
        ];
        $response = $this->getResponse(url('/api/profile/' . $this->profileId),'post',$data);
    }
    
    private function getValue($index){
        return !empty($this->value[$index]) ? $this->value[$index] : null;
    }
    private function getResponse($url, $method = 'post', $data)
    {
        $client = new Client();
        $response = $client->request($method,$url,$data);
        if($response->getStatusCode() != 200){
            \Log::error("Could not complete $method request for $url");
            \Log::error($response->getBody());
            return;
        }
        return $response->getBody();
    }
}
