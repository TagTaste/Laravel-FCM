<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveSpecialCharsHandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveSpecialCharsHandle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Strip whitespaces and special chars from handle';

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
//        $res = preg_replace("/[^a-zA-Z0-9]/", "", $string);
        \DB::table('profiles')->whereNotNull('handle')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                $fixedHandle = preg_replace("/[^a-zA-Z0-9]/", "", $model->handle);
                \DB::table('profiles')->where('id',$model->id)->update(['handle'=>$fixedHandle]);
                echo 'Profile id: '.$model->id." Done...\n";
            }
        });
    }
}
