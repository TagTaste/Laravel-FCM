<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

\Artisan::command("config:generate {path} {prefix} {host}",function($path,$prefix,$host){
    $file = fopen($path,"ab");
    $count = 0;
    $host = "http://$host/v1/kv/";
    echo $host . "\n";
    foreach($_ENV as $key => $value){
        if(trim($value) == null){
           continue;
       }
       if(substr($value,0,1) == '@'){
          $value = substr($value,1);
          echo "$key has @\n : new value $value";
       }
        //write the template
        fwrite($file,$key . '={{ key "' . $prefix . $key . "\"}}\n");
        echo "running:\n";
        echo "curl -s --request PUT --data $value $host" . $prefix . $key ."\n";
        $status = shell_exec("curl -s --request PUT --data $value $host" . $prefix . $key);
        if($status){
            echo $status . "\n";
            $count++;
        }else{
            echo "Couldnt write $key : $value\n";
        }
    }
    echo "wrote: " . $count;
    fclose($file);
});
