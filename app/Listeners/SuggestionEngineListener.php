<?php

namespace App\Listeners;

use App\Events\SuggestionEngineEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuggestionEngineListener
{

//    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SuggestionEngineEvent  $event
     * @return void
     */
    public function handle(SuggestionEngineEvent $event)
    {
        if($event->type = 'update')
        {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();

            //for profile update
            $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=',$event->modelData->id);
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('profile_id');
                foreach ($x as $y)
                {
                    if(!\Redis::sIsMember('following:profile:'.$event->modelData->id, $y))
                    {
                        \Redis::sAdd('suggested:profile:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            //get existing similar profile ids
            $profileids = \Redis::sMembers('suggested:profile:'.$event->modelData->id);
            $profileidsCsv = '';
            $index = 0;
            foreach ($profileids as $profileid)
            {
                if($index > 20)
                    break;
                $profileidsCsv = $profileidsCsv.','.$profileid;
                $index++;
            }
             \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type','profile')->update(['suggested_id'=>$profileidsCsv]);

            //for company update
            $data = \DB::table('company_filters')->select('company_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('company_id');
                foreach ($x as $y)
                {
                    if(!\Redis::sIsMember('following:profile:'.$event->modelData->id, "company".$y))
                    {
                        \Redis::sAdd('suggested:company:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            //get existing similar profile ids
            $profileids = \Redis::sMembers('suggested:company:'.$event->modelData->id);
            $profileidsCsv = '';
            $index = 0;
            foreach ($profileids as $profileid)
            {
                if($index > 20)
                    break;
                $profileidsCsv = $profileidsCsv.','.$profileid;
                $index++;
            }
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type','company')->update(['suggested_id'=>$profileidsCsv]);


            //for job update
            $data = \DB::table('job_filters')->select('job_filters_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('job_id');
                foreach ($x as $y)
                {
                    $hasApplied = \DB::table('applications')->where('job_id',$y)->where('profile_id',$event->modelData->id)->exists();
                    if(!$hasApplied)
                    {
                        \Redis::sAdd('suggested:job:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            //get existing similar profile ids
            $jobIds = \Redis::sMembers('suggested:job:'.$event->modelData->id);
            $jobIdsCsc = '';
            $index = 0;
            foreach ($jobIds as $jobId)
            {
                if($index > 20)
                    break;
                $jobIdsCsc = $jobIdsCsc.','.$jobId;
                $index++;
            }
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type','job')->update(['suggested_id'=>$jobIdsCsc]);

            //for collaboration

            $data = \DB::table('collaborate_filters')->select('collaborate_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('collaborate_id');
                foreach ($x as $y)
                {
                    \Redis::sAdd('suggested:collaborate:'.$event->modelData->id,$y);
                }
            }
            //get existing similar profile ids
            $collaborateIds = \Redis::sMembers('suggested:collaborate:'.$event->modelData->id);
            $collaborateIdsCsc = '';
            $index = 0;
            foreach ($collaborateIds as $collaborateId)
            {
                if($index > 20)
                    break;
                $collaborateIdsCsc = $collaborateIdsCsc.','.$collaborateId;
                $index++;
            }
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type','collaborate')->update(['suggested_id'=>$collaborateIdsCsc]);

        }
        else {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();

            //1.for profile update
            $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=',$event->modelData->id);
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('profile_id');
                foreach ($x as $y)
                {
                    if(!\Redis::sIsMember('following:profile:'.$event->modelData->id, $y))
                    {
                        \Redis::sAdd('suggested:profile:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            //get existing similar profile ids
            $profileids = \Redis::sMembers('suggested:profile:'.$event->modelData->id);
            $profileidsCsv = '';
            $index = 0;
            foreach ($profileids as $profileid)
            {
                if($index > 20)
                    break;
                $profileidsCsv = $profileidsCsv.','.$profileid;
                $index++;
            }

            if($index < 20)
            {
                $profileids = \Redis::sMembers('following:profile:'.$event->modelData->id);

                $profileids = \DB::table('profiles')->select('id')->whereNotIn('id',$profileids)->whereNull('deleted_at')->inRandomOrder()->take(20 - $index)->get();

                foreach ($profileids as $profileid)
                {
                    \Redis::sAdd('suggested:profile:'. $event->modelData->id,$profileid->id);
                    $profileidsCsv = $profileid->id.','.$profileidsCsv;
                }
            }

            \DB::table('suggestion_engine')->insert(['profile_id'=>$event->modelData->id,'type'=>'profile','suggested_id'=>$profileidsCsv]);


            //2. for company update
            $data = \DB::table('company_filters')->select('company_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('company_id');
                foreach ($x as $y)
                {
                    if(!\Redis::sIsMember('following:profile:'.$event->modelData->id, "company".$y))
                    {
                        \Redis::sAdd('suggested:company:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            echo "profile id ".$event->modelData->id ."\n";
            //get existing similar company ids
            $companiesIds = \Redis::sMembers('suggested:company:'.$event->modelData->id);
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
                $ids = \Redis::sMembers('following:profile:'.$event->modelData->id);
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
            \DB::table('suggestion_engine')->insert(['profile_id'=>$event->modelData->id,'type'=>'company','suggested_id'=>$companiesIdsCsv]);


            //3. for job update
            $data = \DB::table('job_filters')->select('job_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('job_id');
                foreach ($x as $y)
                {
                    $hasApplied = \DB::table('applications')->where('job_id',$y)->where('profile_id',$event->modelData->id)->exists();
                    if(!$hasApplied)
                    {
                        \Redis::sAdd('suggested:job:'.$event->modelData->id,$y);
                        $modelIds[] = $y;
                    }
                }
            }
            echo "profile id ".$event->modelData->id ."\n";
            //get existing similar company ids
            $jobIds = \Redis::sMembers('suggested:job:'.$event->modelData->id);
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
                $companiesIds = \DB::table('company_users')->where('profile_id',$event->modelData->id)->get()->pluck('company_id');
                $jobIds = \DB::table('jobs')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$event->modelData->id)
                    ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                foreach ($jobIds as $jobId)
                {
                    $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$event->modelData->id)->exists();
                    if(!$hasApplied)
                    {
                        $jobIdsCsc = $jobId->id.','.$jobIdsCsc;
                    }

                }
            }
            if($jobIdsCsc != '')
            {
                \DB::table('suggestion_engine')->insert(['profile_id'=>$event->modelData->id,'type'=>'job','suggested_id'=>$jobIdsCsc]);
            }


            //4.for collaboration

            $data = \DB::table('collaborate_filters')->select('collaborate_id');
            $modelIds = [];
            //get similar profile id's
            foreach ($loggedInProfileKeys as $datum)
            {
                $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('collaborate_id');
                foreach ($x as $y)
                {
                    \Redis::sAdd('suggested:collaborate:'.$event->modelData->id,$y);
                }
            }
            echo "profile id ".$event->modelData->id ."\n";
            //get existing similar collaborate ids
            $collaborateIds = \Redis::sMembers('suggested:collaborate:'.$event->modelData->id);
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
                $companiesIds = \DB::table('company_users')->where('profile_id',$event->modelData->id)->get()->pluck('company_id');
                $jobIds = \DB::table('collaborates')->select('id')->whereNotIn('company_id',$companiesIds)->where('profile_id','!=',$event->modelData->id)
                    ->whereNull('deleted_at')->where('state',1)->inRandomOrder()->get();
                foreach ($jobIds as $jobId)
                {
                    $hasApplied = \DB::table('applications')->where('job_id',$jobId->id)->where('profile_id',$event->modelData->id)->exists();
                    if(!$hasApplied)
                    {
                        $collaborateIdsCsc = $jobId->id.','.$collaborateIdsCsc;
                    }

                }
            }
            if($collaborateIdsCsc != '')
            {
                \DB::table('suggestion_engine')->insert(['profile_id'=>$event->modelData->id,'type'=>'collaborate','suggested_id'=>$collaborateIdsCsc]);
            }
        }
    }
}
