<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->model = [];
        //paginate
//        $page = $request->input('page');
//        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        if(!$profile){
           return $this->sendError("Profile not found.");
        }
        $notifications = $profile->notifications()->paginate()->toArray();
        foreach ($notifications['data'] as $index => $notification) {

            if(!isset($notification['data']['notification']))
            {
                unset($notifications['data'][$index]);
                continue;
            }
            if(!is_null($notification['data']['profile'])) {
                $notifications['data'][$index]['isFollowing'] = \App\Profile::isFollowing($request->user()->profile->id,
                    $notification['data']['profile']['id']);
            }
        }
        $this->model = $notifications;
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        $this->model =  $profile->notifications()->where('id',$id)->first();
        $this->model->where("data->model->name",$this->model->data['model']['name'])
            ->where("data->model->id",$this->model->data['model']['id'])->update(['read_at' => Carbon::now()]);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        $this->model =  $profile->notifications()->where('id',$id)->delete();
        return $this->sendResponse();
    }
    
    
    /**
     * Mark notification as read.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, $id)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        $this->model =  $profile->notifications()->where('id',$id)
            ->update(['read_at' => Carbon::now()]);
        $this->model = \DB::table('notifications')->whereNull('read_at')->where('notifiable_id',request()->user()->profile->id)->count();

        return $this->sendResponse();
    }
    
    /**
     * Get only unread notifications.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(Request $request)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        $this->model =  $profile->unreadNotifications;
        return $this->sendResponse();
    }

    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::where('user_id',$userId)->first();
        $this->model =  $profile->notifications()->where('notifiable_id',$profile->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
        return $this->sendResponse();
    }

    public function seen(Request $request,$type)
    {
        $this->model = false;
        if($type == 'notification')
        {
            $this->model = \DB::table('notifications')->where('notifiable_id',$request->user()->profile->id)->update(['last_seen'=>Carbon::now()->toDateTimeString()]);
        }
        if($type == 'message')
        {
            $this->model = \DB::table('message_recepients')->whereNull('last_seen')->where('recepient_id',$request->user()->profile->id)->update(['last_seen'=>Carbon::now()->toDateTimeString()]);
        }

        return $this->sendResponse();

    }
    
}
