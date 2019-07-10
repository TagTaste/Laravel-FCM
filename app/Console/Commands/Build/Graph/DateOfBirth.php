<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class DateOfBirth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:dateOfBirth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds profile cache';

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
        // Declare two dates 
        $Date1 = '01-01-2020'; 
        $Date2 = '31-12-2020'; 
        
        // Use strtotime function 
        $Variable1 = strtotime($Date1); 
        $Variable2 = strtotime($Date2); 
        
        // Use for loop to store dates into array 
        // 86400 sec = 24 hrs = 60*60*24 = 1 day 
        for ($currentDate = $Variable1; $currentDate <= $Variable2;  
                                        $currentDate += (86400)) { 
            $dob = date('d-m', $currentDate); 
            $name = date('d-M', $currentDate);
            
            $date_exist = \App\Neo4j\DateOfBirth::where('dob', $dob)->where('name',$name)->first();
            if (!$date_exist) {
                echo "DOB: ".$dob." and name:".$name." not exist. \n";
                \App\Neo4j\DateOfBirth::create([
                    "dob" => $dob,
                    "name" => $name
                ]);
            } else {
                echo "DOB: ".$dob." and name:".$name." already exist. \n";
            }
        }
    }
}
