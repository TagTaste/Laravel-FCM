<?php

namespace App\Console\Commands\Build\Suggestion\SuggestionToDatabase;

use Illuminate\Console\Command;

class Profile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:suggestiontodatabase:profile';

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
        \DB::table('profiles')->whereNull('deleted_at')->orderBy('id')->chunk(10, function ($models) {
            foreach ($models as $model) {
                $checkProfile = \DB::table('suggestion_engine')->where('type','=','profile')->where('profile_id',$model->id)->first();
                if(isset($checkProfile))
                {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=', $model->id);
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('profile_id');
                        foreach ($x as $y)
                        {
                            if(!\Redis::sIsMember('following:profile:'. $model->id, $y))
                            {
                                \Redis::sAdd('suggested:profile:'. $model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar profile ids
                    $profileids = \Redis::sMembers('suggested:profile:'. $model->id);
                    $profileidsCsv = '';
                    $index = 0;
                    foreach ($profileids as $profileid)
                    {
                        if($index > 20)
                            break;
                        $profileidsCsv = $profileid.','.$profileidsCsv;
                        $index++;
                    }
                    if($index < 20)
                    {
                        $profileids = \Redis::sMembers('following:profile:'.$model->id);
                        $profileids = \DB::table('profiles')->select('id')->whereNotIn('id',$profileids)->whereNull('deleted_at')->inRandomOrder()->take(20 - $index)->get();
                        foreach ($profileids as $profileid)
                        {
                            $profileidsCsv = $profileid->id.','.$profileidsCsv;
                        }
                    }
                    \DB::table('suggestion_engine')->where('profile_id', $model->id)->where('type','profile')->update(['suggested_id'=>$profileidsCsv]);
                }
                else {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=', $model->id);
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('profile_id');
                        foreach ($x as $y)
                        {
                            if(!\Redis::sIsMember('following:profile:'. $model->id, $y))
                            {
                                \Redis::sAdd('suggested:profile:'. $model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar profile ids
                    $profileids = \Redis::sMembers('suggested:profile:'. $model->id);
                    $profileidsCsv = '';
                    $index = 0;
                    foreach ($profileids as $profileid)
                    {
                        \Redis::sAdd('suggested:profile:'. $model->id,$profileid);
                        if($index > 20)
                            break;
                        $profileidsCsv = $profileid.','.$profileidsCsv;
                        $index++;
                    }
                    if($index < 20)
                    {
                        $profileids = \Redis::sMembers('following:profile:'.$model->id);

                        $profileids = \DB::table('profiles')->select('id')->whereNotIn('id',$profileids)->whereNull('deleted_at')->inRandomOrder()->take(20 - $index)->get();

                        foreach ($profileids as $profileid)
                        {
                            \Redis::sAdd('suggested:profile:'. $model->id,$profileid->id);
                            $profileidsCsv = $profileid->id.','.$profileidsCsv;
                        }
                    }
                    \DB::table('suggestion_engine')->insert(['profile_id'=>$model->id,'type'=>'profile','suggested_id'=>$profileidsCsv]);
                }
            }
        });

    }
}
