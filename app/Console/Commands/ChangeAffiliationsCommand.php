<?php
namespace App\Console\Commands;
use App\Company;
use Illuminate\Console\Command;
class ChangeAffiliationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChangeAffiliationsCommand';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change affiliation in company';
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
        Company\Affiliation::chunk(200,function ($models){
            $models = $models->groupBy('company_id');
            foreach ($models as $model)
            {
                $title = implode(",",$model->pluck('title')->toArray());
                Company::where('id',$model[0]->company_id)->update(['affiliations'=>$title]);
            }
        });
    }
}