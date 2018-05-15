<?php

namespace App\Console\Commands\Build\Suggestion\SuggestionToDatabase;

use Illuminate\Console\Command;

class Company extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:suggestiontodatabase:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add profile Suggestion Data store in database.';

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
        //update for company suggestion
        \DB::table('profiles')->whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                $checkProfile = \DB::table('suggestion_engine')->where('type','=','company')->where('profile_id',$model->id)->first();
                if(isset($checkProfile))
                {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('company_filters')->select('company_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('company_id');
                        foreach ($x as $y)
                        {
                            if(!\Redis::sIsMember('following:profile:'.$model->id, "company".$y))
                            {
                                \Redis::sAdd('suggested:company:'.$model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar company ids
                    $companiesIds = \Redis::sMembers('suggested:company:'.$model->id);
                    $companiesIdsCsv = '';
                    $index = 0;
                    foreach ($companiesIds as $companiesId)
                    {
                        if($index > 20)
                            break;
                        $companiesIdsCsv = $companiesId.','.$companiesIdsCsv;
                        $index++;
                    }

                    if($index < 20)
                    {
                        $ids = \Redis::sMembers('following:profile:'.$model->id);
                        $companiesIds = [];
                        foreach ($ids as $id)
                        {
                            $followerId = explode('.',$id);
                            if($followerId[0] == 'company')
                            {
                                $companiesIds[] = $followerId[1];
                            }
                        }
                        $companiesIds = \DB::table('companies')->select('id')->whereNotIn('id',$companiesIds)->whereNull('deleted_at')->inRandomOrder()->take(20 - $index)->get();
                        foreach ($companiesIds as $companiesId)
                        {
                            $companiesIdsCsv = $companiesId->id.','.$companiesIdsCsv;
                        }
                    }
                    \DB::table('suggestion_engine')->where('profile_id', $model->id)->where('type','company')->update(['suggested_id'=>$companiesIdsCsv]);
                }
                else {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('company_filters')->select('company_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('company_id');
                        foreach ($x as $y)
                        {
                            if(!\Redis::sIsMember('following:profile:'.$model->id, "company".$y))
                            {
                                \Redis::sAdd('suggested:company:'.$model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar company ids
                    $companiesIds = \Redis::sMembers('suggested:company:'.$model->id);
                    $companiesIdsCsv = '';
                    $index = 0;
                    foreach ($companiesIds as $companiesId)
                    {
                        if($index > 20)
                            break;
                        $companiesIdsCsv = $companiesId.','.$companiesIdsCsv;
                        $index++;
                    }
                    if($index < 20)
                    {
                        $ids = \Redis::sMembers('following:profile:'.$model->id);
                        $companiesIds = [];
                        foreach ($ids as $id)
                        {
                            $followerId = explode('.',$id);
                            if($followerId[0] == 'company')
                            {
                                $companiesIds[] = $followerId[1];
                            }
                        }
                        $companiesIds = \DB::table('companies')->select('id')->whereNotIn('id',$companiesIds)->whereNull('deleted_at')->inRandomOrder()->take(20 - $index)->get();
                        foreach ($companiesIds as $companiesId)
                        {
                            $companiesIdsCsv = $companiesId->id.','.$companiesIdsCsv;
                        }
                    }
                    \DB::table('suggestion_engine')->insert(['profile_id'=>$model->id,'type'=>'company','suggested_id'=>$companiesIdsCsv]);
                }
            }
        });
    }
}
