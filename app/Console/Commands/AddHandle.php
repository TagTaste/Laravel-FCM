<?php

namespace App\Console\Commands;

use App\Profile;
use App\User;
use Illuminate\Console\Command;

class AddHandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addHandleOfProfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add handle of profile';

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
        $file_n = storage_path('import_file.csv');
        $file = fopen($file_n, "r");
        while ( ($data = fgetcsv($file, 200, ",")) !==FALSE) {
            echo $data[0]." id and hanlde is ".$data[3] . "\n";
            \DB::table('profiles')->where('id',$data[0])->update(['handle'=>$data[3]]);
        }
        fclose($file);
    }
}
