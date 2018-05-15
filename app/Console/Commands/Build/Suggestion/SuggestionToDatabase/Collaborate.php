<?php

namespace App\Console\Commands\Build\Suggestion\SuggestionToDatabase;

use Illuminate\Console\Command;

class Collaborate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:suggestiontodatabase:collaborate';

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
                $checkProfile = \DB::table('suggestion_engine')->where('type','=','collaborate')->where('profile_id',$model->id)->first();
                if(isset($checkProfile))
                {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('collaborate_filters')->select('collaborate_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('collaborate_id');
                        foreach ($x as $y)
                        {
                            \Redis::sAdd('suggested:collaborate:'.$model->id,$y);
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar collaborate ids
                    $collaborateIds = \Redis::sMembers('suggested:collaborate:'.$model->id);
                    $collaborateIdsCsc = '';
                    $index = 0;
                    foreach ($collaborateIds as $collaborateId)
                    {
                        if($index > 20)
                            break;
                        $collaborateIdsCsc = $collaborateIdsCsc.','.$collaborateId;
                        $index++;
                    }
                    if($index < 20)
                    {
                        $companiesIds = \DB::table('company_users')->where('profile_id',$model->id)->get()->pluck('company_id');
                        $jobIds = \DB::table('collaborates')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$model->id)
                            ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                        foreach ($jobIds as $jobId)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                $collaborateIdsCsc = $jobId->id.','.$collaborateIdsCsc;
                            }

                        }
                    }
                    \DB::table('suggestion_engine')->where('profile_id',$model->id)->where('type','collaborate')->update(['suggested_id'=>$collaborateIdsCsc]);
                }
                else {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('collaborate_filters')->select('collaborate_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('collaborate_id');
                        foreach ($x as $y)
                        {
                            \Redis::sAdd('suggested:collaborate:'.$model->id,$y);
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar collaborate ids
                    $collaborateIds = \Redis::sMembers('suggested:collaborate:'.$model->id);
                    $collaborateIdsCsc = '';
                    $index = 0;
                    foreach ($collaborateIds as $collaborateId)
                    {
                        if($index > 20)
                            break;
                        $collaborateIdsCsc = $collaborateIdsCsc.','.$collaborateId;
                        $index++;
                    }
                    if($index < 20)
                    {
                        $companiesIds = \DB::table('company_users')->where('profile_id',$model->id)->get()->pluck('company_id');
                        $jobIds = \DB::table('collaborates')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$model->id)
                            ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                        foreach ($jobIds as $jobId)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                $collaborateIdsCsc = $jobId->id.','.$collaborateIdsCsc;
                            }

                        }
                    }
                    \DB::table('suggestion_engine')->insert(['profile_id'=>$model->id,'type'=>'collaborate','suggested_id'=>$collaborateIdsCsc]);
                }
            }
        });
    }
}
