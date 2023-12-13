<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UploadLogsToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:storage:logs {--all : Upload all log files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload logs to S3 bucket';

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
        try {
            $allOption = $this->option('all');

            $logs = storage_path('logs');
            $server = (env('APP_ENV') === 'test') ? 'server1' : 'server2';

            if ($allOption) {
                $this->info('Uploading all log files..');

                $files = scandir($logs);

                foreach ($files as $file) {
                    $weeklyFilePattern = '/^\d{4}-\d{2}-\d{2}-\d{4}-\d{2}-\d{2}\.txt$/';
                    if (preg_match($weeklyFilePattern, $file)) 
                    {
                        // Upload log file to S3 bucket
                        $this->putToS3('laravel_logs/'.$server.'/'.$file, $logs.'/'.$file);
                    } 
                }

            } else {
                $this->info('Uploading weekly log file..');
                
                $currentDate = Carbon::now();
                $monday = $currentDate->startOfWeek()->format('Y-m-d');
                $sunday = $currentDate->endOfWeek()->format('Y-m-d');

                if($currentDate->format('l') == "Monday")
                {
                    // Subtracts a week and finds the start & end of the week
                    $lastWeek = $currentDate->subWeek();
                    $lastWeekMonday = $lastWeek->startOfWeek()->format('Y-m-d');
                    $lastWeekSunday = $lastWeek->endOfWeek()->format('Y-m-d');
                    $lastWeekFile = $lastWeekMonday."-".$lastWeekSunday.".txt";

                    // Upload log file to S3 bucket
                    $this->putToS3('laravel_logs/'.$server.'/'.$lastWeekFile, $logs.'/'.$lastWeekFile);
                }

                $currentWeekFile = $monday."-".$sunday.".txt";
                
                // Upload log file to S3 bucket
                $this->putToS3('laravel_logs/'.$server.'/'.$currentWeekFile, $logs.'/'.$currentWeekFile);
            }
            $this->info('Logs uploaded to S3 successfully!');
        } catch (\Exception $e) {
            \Log::error('Error uploading logs to S3: ' . $e->getMessage());
        }
    }

    private function putToS3($path, $file)
    {
       Storage::disk('s3')->put($path, file_get_contents($file));
    }
}
