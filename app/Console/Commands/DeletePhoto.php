<?php

namespace App\Console\Commands;

use App\Education;
use App\Photo;
use App\Profile\Book;
use App\Profile\Experience;
use App\Profile\Patent;
use App\Profile\Project;
use App\Profile\Show;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeletePhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeletePhoto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "delete photo from company_photo and profile_photo";

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
        $models = \DB::table('photos')->whereNotNull('deleted_at')->get();
        foreach ($models as $model)
        {
            \DB::table('profile_photos')->where('photo_id',$model->id)->delete();
            \DB::table('company_photos')->where('photo_id',$model->id)->delete();
        }
    }
}
