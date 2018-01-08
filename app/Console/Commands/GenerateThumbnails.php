<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates thumbnails for profiles and companies';

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
        
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(50,function($profiles){
            $profiles->each(function($profile){
                try {
                    echo "Trying $profile->id\n";
                    if(trim($profile->image) !== null){
                        
                        $path = \App\Profile::getImagePath($profile->id) . "/" . str_random(20) . ".jpg";
                        $thumbnail = \Image::make($profile->imageUrl)->resize(180, null,function ($constraint) {
                            $constraint->aspectRatio();
                        })->stream('jpg',70);
                        
                        if($thumbnail){
                            \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
                            $profile->image = $path;
                            $status = $profile->update();
                            if($status){
                                $profile->addToCache();
                                echo "updated $profile->id\n $profile->imageUrl";
                            }
                        }
                        
                    }
                } catch (\Exception $e){
                    \Log::warning("Could not create thumbnail for profile " . $profile->id);
                    \Log::error($e->getMessage());
                }
            });
        });
        
        
        \App\Company::whereNull('deleted_at')->chunk(50,function($companies){
            $companies->each(function($company){
                try {
                    echo "Trying $company->id";
                    
                    if(trim($company->logo) !== null){
                        $path = \App\Company::getLogoPath($company->profileId, $company->id) . "/" . str_random(20) . ".jpg";
                        $thumbnail = \Image::make($company->logo)->resize(180, null,function ($constraint) {
                            $constraint->aspectRatio();
                        })->stream('jpg',70);
                        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
                        $company->logo = $path;
                        $status = $company->update();
                        if($status){
                            $company->addToCache();
                            echo "updated $company->id $company->logo";
                        }
                    }
                    
                } catch (\Exception $e){
                    \Log::warning("Could not create thumbnail for company " . $company->id);
                    \Log::error($e->getMessage());
                }
            });
        });
    }
}
