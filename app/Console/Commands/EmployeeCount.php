<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class EmployeeCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EmployeeCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Employee Count ';

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
        Company::select('*')->chunk(200,function($models){
            foreach($models as $model){
                if($model->employee_count == 0)
                {
                    $model->update(['employee_count'=>1]);
                }
                else if($model->employee_count == 1)
                {
                    $model->update(['employee_count'=>2]);
                }
                else if($model->employee_count == 2)
                {
                    $model->update(['employee_count'=>3]);
                }
                else if($model->employee_count == 3)
                {
                    $model->update(['employee_count'=>4]);
                }
                else if($model->employee_count == 4)
                {
                    $model->update(['employee_count'=>5]);
                }
                else if($model->employee_count == 5)
                {
                    $model->update(['employee_count'=>6]);
                }
                else if($model->employee_count == 6)
                {
                    $model->update(['employee_count'=>7]);
                }
                else
                {
                    $model->update(['employee_count'=>8]);
                }
            }
        });
    }
}
