<?php
namespace App\Console\Commands;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
class ExpireonCollaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collaboration:delete_expired';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when collaboration is expired';
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
        //this run only once after that remove from kernel.php this file
        \DB::table("collaborates")->where('expires_on','<=',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
            ->update(['deleted_at'=>Carbon::now()->toDateTimeString()]);
    }
}
