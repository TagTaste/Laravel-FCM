<?php

namespace App\Console\Commands\Build\Suggestion\SuggestionToDatabase;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:suggestiontodatabase:job';

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
                $checkProfile = \DB::table('suggestion_engine')->where('type','=','job')->where('profile_id',$model->id)->first();
                if(isset($checkProfile))
                {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('job_filters')->select('job_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('job_id');
                        foreach ($x as $y)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$y)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                Redis::sAdd('suggested:job:'.$model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar company ids
                    $jobIds = Redis::sMembers('suggested:job:'.$model->id);
                    $jobIdsCsc = '';
                    $index = 0;
                    foreach ($jobIds as $jobId)
                    {
                        if($index > 20)
                            break;
                        $jobIdsCsc = $jobId.','.$jobIdsCsc;
                        $index++;
                    }

                    if($index < 20)
                    {
                        $companiesIds = \DB::table('company_users')->where('profile_id',$model->id)->get()->pluck('company_id');
                        $jobIds = \DB::table('jobs')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$model->id)
                            ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                        foreach ($jobIds as $jobId)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                $jobIdsCsc = $jobId->id.','.$jobIdsCsc;
                            }

                        }
                    }
                    if($jobIdsCsc != '')
                    {
                        \DB::table('suggestion_engine')->where('profile_id', $model->id)->where('type','job')->update(['suggested_id'=>$jobIdsCsc]);
                    }
                }
                else {
                    $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id', $model->id)->get();
                    $data = \DB::table('job_filters')->select('job_id');
                    $modelIds = [];
                    //get similar profile id's
                    foreach ($loggedInProfileKeys as $datum)
                    {
                        $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('job_id');
                        foreach ($x as $y)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$y)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                Redis::sAdd('suggested:job:'.$model->id,$y);
                                $modelIds[] = $y;
                            }
                        }
                    }
                    echo "profile id ".$model->id ."\n";
                    //get existing similar company ids
                    $jobIds = Redis::sMembers('suggested:job:'.$model->id);
                    $jobIdsCsc = '';
                    $index = 0;
                    foreach ($jobIds as $jobId)
                    {
                        if($index > 20)
                            break;
                        $jobIdsCsc = $jobId.','.$jobIdsCsc;
                        $index++;
                    }

                    if($index < 20)
                    {
                        $companiesIds = \DB::table('company_users')->where('profile_id',$model->id)->get()->pluck('company_id');
                        $jobIds = \DB::table('jobs')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$model->id)
                            ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                        foreach ($jobIds as $jobId)
                        {
                            $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$model->id)->exists();
                            if(!$hasApplied)
                            {
                                $jobIdsCsc = $jobId->id.','.$jobIdsCsc;
                            }

                        }
                    }
                    if($jobIdsCsc != '')
                    {
                        \DB::table('suggestion_engine')->insert(['profile_id'=>$model->id,'type'=>'job','suggested_id'=>$jobIdsCsc]);
                    }
                }
            }
        });
    }
}
