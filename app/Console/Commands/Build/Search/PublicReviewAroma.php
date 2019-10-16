<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;
use App\SearchClient;

class PublicReviewProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:product:Aroma';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search product';

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
        $client =  SearchClient::get();
        $aromas = \DB::table('public_review_global_nested_option')->where('is_active',1)->get();
        foreach ($aromas as $aroma) {
            $document = [];
            $document['type'] = "private_aroma";
            $document['index'] = "index";
            $documet['']
            $client->index($document->toArray());
        }
    }
}
