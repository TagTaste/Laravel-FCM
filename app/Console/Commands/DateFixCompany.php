<?php
namespace App\Console\Commands;
use App\Company;
use Illuminate\Console\Command;
class DateFixCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DateFixCompany';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Date Format in company';
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
        //because in db date format store in yyyy-mm-dd hh:mm:ss so
        //we need to first to revert string and after date remove day from date
//        Book::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
//            foreach($models as $model){
//                if(strlen($model->release_date)>8){
//                    $model->update(['release_date'=>date("m-Y", strtotime($model->release_date))]);
//                }
//            }
//        });
//        Award::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
//            foreach($models as $model){
//                if(strlen($model->date)>8){
//                    $model->update(['date'=>date("m-Y", strtotime($model->date))]);
//                }
//            }
//        });
//        Patent::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
//            foreach($models as $model){
//                if(strlen($model->awarded_on)>8){
//                    $model->update(['awarded_on'=>date("m-Y", strtotime($model->awarded_on))]);
//                }
//            }
//        });
        //traning is not live from back so we don't need command for training

        //this is for again command for EstablishedOn to reverse the string

        Company::select('*')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->established_on)>8){
                    $model->update(['established_on'=>date("d-m-Y", strtotime($model->established_on))]);
                }
            }
        });
    }
}