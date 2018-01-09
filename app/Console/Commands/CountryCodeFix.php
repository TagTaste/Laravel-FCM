<?php

namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Command;

class CountryCodeFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countryCodeFix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove country code from phone numbers of all registered users as we are handling country code separate from the number now.';

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
        \DB::table('profiles')->whereRaw('LENGTH(phone) > 10')->whereNotNull('phone')->orderBy('id')->chunk(100, function ($models) {
           foreach ($models as $model) {
               $phone = substr($model->phone,-10);
               $countryCode = substr($model->phone, 0, strlen($model->phone)-10);
               \DB::table('profiles')->where('id', $model->id)->update(['phone'=>$phone, 'country_code'=>$countryCode]);
               echo "Profile id: ".$model->id." Done..\n";
           }
        });
    }
}
