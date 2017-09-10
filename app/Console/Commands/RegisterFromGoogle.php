<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RegisterFromGoogle extends Command
{
    private $value;
    private $profileId;
    
    private $email;
    private $password = 'tagtaste1234';
    private $token;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:google {file} {skip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register from Google Sheet {file}';

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
        \Cache::forget("values");
        $values = \Cache::remember('values',120,function(){
            $sheetId = $this->argument('file');
            \Sheets::setService(\Google::make('sheets'));
            \Sheets::spreadsheet($sheetId);
            return \Sheets::sheet('Sheet1')->get();
        });
        $values->pull(0);
        $bar = $this->output->createProgressBar(count($values));
        $skip = $this->argument('skip');
        foreach($values as $value){
            if($value[0] <= $skip){
                $this->error("skipping " . $value[0]);
                continue;
            }
            if(empty($value[4])){
                continue;
            }
            $this->value = $value;
//            $status = $this->registerUser();
//            if(!$status){
//                $message = "Could not register " . $this->email;
//                \Log::info($message);
//                $this->error($message);
//                $bar->advance();
//                continue;
//            }
            try {
                $this->login(); //get token
                $this->getProfileId();
                //$this->uploadPhoto();
                //$this->updateProfile();
                $this->updateExperience();
                $this->updateEducation();
                $this->updateBooks();
                $this->updateTV();
            } catch (\Exception $e){
                \Log::error($e->getMessage());
                $this->error($e->getMessage());
                $this->error("Could not create profile for " . $this->value[3]);
            }
            
            
            $bar->advance();
        }
        $bar->finish();
    }
    
    private function setTV($array)
    {
        $url = "/api/profiles/" . $this->profileId . "/shows";
        $data = [
            'title' => $array[0],
            'channel' => $array[1],
            'appeared_as' => $array[2],
            'url'=>$array[3],
            'start_date' => $array[4],
            'end_date' => $array[5],
            'description' => $array[6]
        ];
        $response = $this->getResponse(url($url),'post',['form_params'=>$data]);
        $this->info($response);
    }
    
    private function updateTV()
    {
        $this->setTV([89,90,91,92,93,94,95]);
    }
    private function setBook($array)
    {
        $url = "/api/profiles/" . $this->profileId . "/books";
        $data = [
            'title' => $array[0],
            'publisher' => $array[1],
            'isbn' => $array[2],
            'url'=>$array[3],
            'release_date' => $array[4],
            'description' => $array[5]
        ];
        $response = $this->getResponse(url($url),'post',['form_params'=>$data]);
        $this->info($response);
    }
    
    private function updateBooks()
    {
        $this->setBook([77,78,79,80,81,82]);
        $this->setBook([83,84,85,86,87,88]);
    }
    
    private function setEducation($array)
    {
        $url = "/api/profiles/" . $this->profileId . "/education";
        $data = [
            'degree' => $array[0],
            'college' => $array[1],
            'location' => $array[2] . ", " . $array[3] . ", " . $array[4],
            'fields'=>$array[5],
            'start_date' => $array[6],
            'end_date'=> strtolower($array[7]) == 'present' ? null : $array[7],
            'description' => $array[8]
        ];
        $response = $this->getResponse(url($url),'post',['form_params'=>$data]);
        $this->info($response);
    }
    
    private function updateEducation()
    {
        $this->setEducation([51,52,53,54,55,56,57,58,59]);
        $this->setEducation([60,61,62,63,64,65,66,67,68]);
    }
    
    private function setExperience($exp)
    {
        $url = "/api/profiles/" . $this->profileId . "/experiences";
        $data = [
            'company' => $exp[0],
            'designation' => $exp[1],
            'location' => $exp[2] . ", " . $exp[3] . ", " . $exp[4],
            'start_date' => $exp[5],
            'end_date'=> strtolower($exp[6]) == 'present' ? null : $exp[6],
            'description' => $exp[7]
        ];
        $response = $this->getResponse(url($url),'post',['form_params'=>$data]);
        $this->info($response);
    }
    private function updateExperience()
    {
        $this->setExperience([27,28,29,30,31,32,33,34]);
        $this->setExperience([35,36,37,38,39,40,41,42]);
        $this->setExperience([43,44,45,46,47,48,49,50]);
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
        $this->info($data);
        $data = json_decode($data);
        return $data->status == 'success';
    }
    
    private function login(){
        $data = ['email'=>$this->value[5],'password'=>$this->password];
        $response = $this->getResponse(url('/api/login'),'post',['form_params'=>$data]);
        $this->info($response);
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
        
        if(isset($this->value[96])){
            $data['multipart'][] =  [ 'name'=> 'hero_image',
                'contents' => fopen($this->value[96],'r')];
        }
        
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
        if(!isset($data['headers']['Authorization'])){
            $data['headers']['Authorization'] = 'Bearer ' . $this->token;
        }
        $response = $client->request($method,$url,$data);
        if($response->getStatusCode() != 200){
            \Log::error("Could not complete $method request for $url");
            \Log::error($response->getBody());
            return;
        }
        return $response->getBody();
    }
}
