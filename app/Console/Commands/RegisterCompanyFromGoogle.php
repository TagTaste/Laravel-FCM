<?php

namespace App\Console\Commands;

use App\Company\Status;
use App\Company\Type;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RegisterCompanyFromGoogle extends Command
{
    private $value;
    
    private $types;
    private $statuses;
    
    private $email = 'admin@tagtaste.com';
    private $password = 'qwerty';
    
    private $companyId;
    
    private $token = null;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:company:google {file} {skip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register company from Google Sheet';

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
        $this->types = $this->fetchTypes();
        $this->statuses = $this->fetchStatuses();
        \Cache::forget("company_values");
        $file = $this->argument('file');
        $values = \Cache::remember('company_values',120,function() use ($file){
            \Sheets::setService(\Google::make('sheets'));
            \Sheets::spreadsheet($file);
            return \Sheets::sheet('Sheet1')->get();
        });
        $values->pull(0);
        $bar = $this->output->createProgressBar(count($values));
    
        $this->login();
        
        foreach($values as $value){
            if($value[0] < $this->argument("skip")){
                continue;
            }
            if(!empty($value[2])){
                continue;
            }
            $this->value = $value;
            try {
                
                $status = $this->createCompany();
                if(!$status){
                    continue;
                }
                $this->addMember();
                
                $this->login();
//                $this->companyId = $this->value[2];
                $this->updateLogo();
            } catch (\Exception $e){
                $this->error($e->getMessage());
            }
        }
        
        $bar->finish();
    }
    
    private function fetchStatuses()
    {
        \Cache::forget("company_statuses");
        return \Cache::remember("company_statuses",120,function(){
            return Status::select('id','name')->get()->keyBy('name');
        });
    }
    private function login(){
        $data = ['email'=>$this->email,'password'=>$this->password];
        $response = $this->getResponse(url('/api/login'),'post',['form_params'=>$data]);
        $response = json_decode($response);
        $this->token = $response->token;
    }
    
    private function fetchTypes(){
        \Cache::forget("company_types");
        return \Cache::remember("company_types",120,function(){
          return   Type::select("id",'name')->get()->keyBy('name');
        });
    }
    
    private function updateLogo()
    {
        $data = [];
        
        try {
            if(!empty($this->value[5])){
                $data = [
                    'multipart' => [
                        [ 'name'=> 'logo',
                            'contents' => fopen($this->value[5],'r')],
                    ]];
            } else {
                $this->info("no image for " . $this->companyId);
            }
//            if(!empty($this->value[44])){
//                $data = [
//                    'multipart' => [
//                        [ 'name'=> 'hero_image',
//                            'contents' => fopen($this->value[44],'r')],
//                    ]];
//            } else {
//                $this->info("no banner image for " . $this->companyId);
//
//            }
            
            if(empty($data)){
                return;
            }
            $data['multipart'][] = ['name'=>'_method','contents'=>'patch'];
        
        } catch (\Exception $e){
            $this->error($e->getMessage());
        }
    
    
        $response = $this->getResponse(url('/api/profiles/227/companies/' . $this->companyId),'post',$data);
        $this->info($response);
        $response = json_decode($response);
    }
    
    private function createCompany()
    {
        $name = $this->value[4];
        $exists = \App\Company::where('name',$name)->exists();
        if($exists){
            return false;
        }
        $map = [
            //index in company array => index in sheets
            'name' => 4,
            'about' => 19,
            'phone' => 8,
            'email' => 6,
            'registered_address' => 13,
            'established_on' => 9,
//            'employee_count' => 12,
            'facebook_url' => 22,
            'linkedin_url' => 23,
            'twitter_url' => 24,
            'youtube_url' => 25,
            'user_id' => 1,
        ];
        if(isset($this->value[11])){
            $map['type'] =  $this->types->get($this->value[11]) ? $this->types->get($this->value[11])->id : null;
        }
        if(isset($this->value[10])){
            $map['status_id'] = $this->statuses->get($this->value[10]) ? $this->statuses->get($this->value[10])->id : null;

        }
        
        $data = [];
        try {
            $data = [
                'multipart' => [
                    [ 'name'=> 'logo',
                        'contents' => !empty($this->value[5]) ? fopen($this->value[5],'rb') :
                            fopen('http://placehold.it/200x200&text=' . $this->value[4],'r')],
                ]];
    
            
        } catch (\Exception $e){
            $this->error($e->getMessage());
        }
        
        
        $data['headers'] =  [
        'Authorization' => 'Bearer ' . $this->token
        ];

        foreach($map as $name => $value){
            if(is_null($value) || !isset($this->value[$value])){
                continue;
            }
            $data['multipart'][] = [
                'name' => $name,
                'contents' => $this->value[$value]
                ];
        }
    
        $response = $this->getResponse(url('/api/profiles/1/companies'),'post',$data);
        $response = json_decode($response);
    
        if(!isset($response->data->id)){
            $this->error("Could not create company: " . $this->value[6]);
            return false;
        }
        
        $this->companyId = $response->data->id;
        return true;
    }
    
    private function makeRequestMember($imageValue,&$map)
    {
        if(empty($this->value[$map['name']]) || empty($this->value[$map['about']])){
            return;
        }
        if(!isset($this->value[$imageValue]) || empty($this->value[$imageValue])){
            $this->error("No image for " . $this->value[$map['name']]);
            return;
        }
        $data = [];
        try {
            $data = [
                'multipart' => [
                    [ 'name'=> 'image',
                        'contents' => !is_null($this->value[$imageValue]) ? fopen($this->value[$imageValue],'r') : null],
                ]
            ];
        } catch (\Exception $e){
            \Log::warning($e->getMessage());
            return;
        }
        
        $data['headers'] = [
        'Authorization' => 'Bearer ' . $this->token
         ];
        foreach($map as $name => $index){
    
            if(empty($this->value[$index])){
                continue;
            }
            $data['multipart'][] = [
                'name' => $name,
                'contents' => $this->value[$index]
            ];
        }
    
        $member = $this->getResponse(url('/api/profiles/1/companies/' . $this->companyId . "/coreteam"),'post',$data);
        
    }
    private function addMember(){
        
        $memberMap1 =
           [
               'name' => 26,
               'designation'=> 27,
               'about'=> 28
        ];
    
        $this->makeRequestMember(29,$memberMap1);
        
        $memberMap2 = [
            'name' => 30,
            'designation' => 31,
            'about' => 32
        ];
    
        $this->makeRequestMember(33,$memberMap2);
        
        $memberMap3 = [
            'name' => 34,
            'designation' => 35,
            'about' => 36
        ];
    
        $this->makeRequestMember(37,$memberMap3);
    
    }
    
    private function getResponse($url, $method = 'post', $data)
    {
        usleep(100000);
    
        $client = new Client();
    
        $data['headers'] =  [
            'Authorization' => 'Bearer ' . $this->token
        ];
        $response = $client->request($method,$url,$data);
        if($response->getStatusCode() != 200){
            \Log::error("Could not complete $method request for $url");
            \Log::error($response->getBody());
            return;
        }
        return $response->getBody();
    }
}
