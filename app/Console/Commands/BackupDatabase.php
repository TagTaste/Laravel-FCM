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
    
        $backupFile = 'mysql_dump_'.date("Y-m-d_H:i:s").'.sql';
        $backupFilePath = storage_path('backup/db/'.$backupFile);
        
        if(!mkdir(storage_path('backup/db',true)) && !is_dir(storage_path('backup/db/'))) {
            $this->error("could not create local backup directory.");
            return;
        }
    
        $command = 'mysqldump -h '.env('DB_HOST').' -P '.env('DB_PORT').' -u '.escapeshellarg(env('DB_USERNAME')).' -p'.escapeshellarg(env('DB_PASSWORD')).' '.env('DB_DATABASE').' > '.$backupFilePath;
        
        echo exec($command);
        
        if(filesize($backupFilePath) == 0){
            $this->error("Could not backup sql");
            return;
        }
    
        $this->info("DB backup done at $now...\nMoving dump to S3...");
        
        $this->putToS3('backup/'.env('APP_ENV').'/db/'.$backupFile,$backupFilePath);
        
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
}
