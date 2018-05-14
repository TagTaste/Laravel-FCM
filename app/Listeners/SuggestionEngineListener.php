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
            $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=',$event->modelData);
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
    }
}
