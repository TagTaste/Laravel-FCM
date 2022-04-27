<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\CompanyUser;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CollaborationExpiresOnUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetExpireon:Collab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set expires_on in collaboration';

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

        Collaborate::where('expires_on', '<=', Carbon::now()->toDateTimeString())->whereNull('deleted_at')
            ->orderBy('id')->chunk(100, function ($models) {
                foreach ($models as $model) {
                    $updated_at =   strtotime('+1 month', strtotime($model->updated_at));
                    // dd($updated_at);
                    \DB::table('collaborates')->where('id', $model->id)->update(['expires_on' => $updated_at]);
                    \Log::info("updated expireson");
                }
            });
    }
}
