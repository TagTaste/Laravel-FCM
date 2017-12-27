<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CompanyDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Company';

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
        $companyId = $this->argument('companyId');
    
        $company = \App\Company::find($companyId);
        
        if($company){
            $company->delete();
        }
    }
}
