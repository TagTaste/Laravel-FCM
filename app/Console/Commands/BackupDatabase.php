<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make DB dump using mysqldump';

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
        if(env('DB_CONNECTION') != 'mysql') {
            $this->error("Incompatible db. Cannot create backup.");
            return;
        }
        
        $this->mysqlBackup();
    }
    
    private function mysqlBackup(){
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $message = "DB backup done at ".$now;

        $backupFile = 'mysql_dump_'.date("Y-m-d_H:i:s").'.sql';
        $backupFilePath = storage_path('backup/db/'.$backupFile);
        
        if(!is_dir(storage_path('backup/db/')) && !mkdir(storage_path('backup/db',true))) {
            $this->error("could not create local backup directory.");
            $message = 'Failed to backup DB: could not create local backup directory.';
            return;
        }
    
        $command = 'mysqldump -h '.env('DB_HOST').' -P '.env('DB_PORT').' -u '.escapeshellarg(env('DB_USERNAME')).' -p'.escapeshellarg(env('DB_PASSWORD')).' '.env('DB_DATABASE').' > '.$backupFilePath;
        
        echo exec($command);
        
        if(filesize($backupFilePath) == 0){
            $this->error("Could not backup sql");
            $message = 'Failed to backup DB: Dump file invalid.';
            return;
        }
    
        $this->info("DB backup done at $now...\nMoving dump to S3...");
        
        $this->putToS3('backup/'.env('APP_ENV').'/db/'.$backupFile,$backupFilePath);

        $this->notifyUsingSlack($message);
        
    }
    
    private function putToS3($path,$backupFilePath)
    {
        $s3 = Storage::disk('s3');
        $status = $s3->put($path, file_get_contents($backupFilePath));
        if($status == 1) {
            $this->info("\nDump moved to S3. Deleting local dump...");
            unlink($backupFilePath);
        }
    }

    private function notifyUsingSlack($message){
        $hook = env('SLACK_HOOK');
        $client =  new \GuzzleHttp\Client();
        $client->request('POST', $hook,
            [
                'json' =>
                    [
                        "channel" => "#backup",
                        "username" => "ramukaka",
                        "icon_emoji" => ":older_man::skin-tone-3:",
                        "text" => $message
                    ]
            ]);
    }

}
