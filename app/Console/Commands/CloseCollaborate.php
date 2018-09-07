<?php
namespace App\Console\Commands;
use App\Collaborate;
use App\CompanyUser;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
class CloseCollaborate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:collaboration';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set state in when collaboration is close';
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
        \App\Collaborate::with([])->where('id', 208)
            ->orderBy('id')->chunk(100, function ($models) {
                foreach ($models as $model) {
                    event(new \App\Events\DeleteFilters(class_basename($model), $model->id));
                    $model->update(['deleted_at' => Carbon::now()->toDateTimeString(), 'state' => Collaborate::$state[4]]);
                    event(new DeleteFeedable($model));

                }
            });
    }
}
