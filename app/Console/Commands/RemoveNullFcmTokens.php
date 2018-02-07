<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveNullFcmTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveNullFcmTokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes null FCM tokens from app_info table';

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
        DB::table('app_info')->whereNull('fcm_token')->delete();
        $this->info('Done...');

    }
}
