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
        if(env('DB_CONNECTION') == 'mysql') {
            $backupFile = 'mysql_dump_'.date("Y-m-d_H:i:s").'.sql';
            $backupFilePath = storage_path('backup/db/'.$backupFile);
            if(!is_dir(storage_path('backup/db/'))) {
                mkdir(storage_path('backup/'));
                mkdir(storage_path('backup/db'));
            }
            $command = 'mysqldump -h '.env('DB_HOST').' -P '.env('DB_PORT').' -u '.env('DB_USERNAME').' -p'.env('DB_PASSWORD').' '.env('DB_DATABASE').' > '.$backupFilePath;
            echo exec($command);
            echo "DB backup done...\nMoving dump to S3...";
            $s3 = Storage::disk('s3');
            $status = $s3->put('backup/'.env('APP_ENV').'/db/'.$backupFile, file_get_contents($backupFilePath));
            if($status == 1) {
                echo "\nDump moved to S3. Deleting local dump...";
                unlink($backupFilePath);
            }
            echo "\nDone.\n";
        }


    }
}
