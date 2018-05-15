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
        if($event->type = 'profile')
        {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();
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
             \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type',$event->type)->update(['suggested_id'=>$profileidsCsv]);
        }
        elseif($event->type = 'company')
        {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();
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
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type',$event->type)->update(['suggested_id'=>$profileidsCsv]);
        }
        elseif ($event->type == 'job')
        {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();
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
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type',$event->type)->update(['suggested_id'=>$jobIdsCsc]);
        }
        elseif ($event->type == 'collaborate')

        {
            $loggedInProfileKeys= \DB::table('profile_filters')->where('profile_id',$event->modelData->id)->get();
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
            \DB::table('suggestion_engine')->where('profile_id',$event->modelData->id)->where('type',$event->type)->update(['suggested_id'=>$collaborateIdsCsc]);
        }
    }
}
