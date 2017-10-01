<?php

namespace App\Console\Commands;

use App\Education;
use App\Profile\Book;
use App\Profile\Experience;
use App\Profile\Patent;
use App\Profile\Project;
use App\Profile\Show;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DateFixProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DateFixProfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Date Format in Profile';

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
        Book::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->release_date)>8){
                    $model->update(['release_date'=>date("m-Y", strtotime($model->release_date))]);
                }
            }
        });
        Experience::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->start_date)>8){
                    $model->update(['start_date'=>date("m-Y", strtotime($model->start_date))]);
                }
                if(strlen($model->end_date)>8){
                    $model->update(['end_date'=>date("m-Y", strtotime($model->end_date))]);
                }
            }
        });
        Education::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->start_date)>8){
                    $model->update(['start_date'=>date("m-Y", strtotime($model->start_date))]);
                }
                if(strlen($model->end_date)>8){
                    $model->update(['end_date'=>date("m-Y", strtotime($model->end_date))]);
                }
            }
        });
        Patent::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->publish_date)>8){
                    $model->update(['publish_date'=>date("m-Y", strtotime($model->publish_date))]);
                }
            }
        });

        Project::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->completed_on)>8){
                    $model->update(['completed_on'=>date("m-Y", strtotime($model->completed_on))]);
                }
            }
        });

        Show::select('*')->where('created_at','>=','2016-09-29 16:59:59')->chunk(200,function($models){
            foreach($models as $model){
                if(strlen($model->date)>8){
                    $model->update(['date'=>date("m-Y", strtotime($model->date))]);
                }
            }
        });

        //traning is not live from back so we don't need command for training

    }
}
