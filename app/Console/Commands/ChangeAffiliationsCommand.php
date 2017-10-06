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
        Company::select('*')->chunk(200,function($models){
            foreach($models as $model){
                $affiliations = Company\Affiliation::where('company_id',$model->id)->get()->pluck('title');
                $affiliations = $affiliations->toArray();
                $affiliations = implode(",", $affiliations);
                Company::where('id',$model->id)->update(['affiliations'=>$affiliations]);
            }
        });
    }
}