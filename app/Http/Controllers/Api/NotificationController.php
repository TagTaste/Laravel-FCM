<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $profile = \App\Notify\Profile::find($userId);
        $this->model = $profile->notifications;
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
        $profile = \App\Notify\Profile::find($userId);
        $this->model =  $profile->notifications()->where('id',$id)->first();
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
        $profile = \App\Notify\Profile::find($userId);
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
        $profile = \App\Notify\Profile::find($userId);
        $this->model =  $profile->notifications()->where('id',$id)
            ->update(['read_at' => Carbon::now()]);
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
        $profile = \App\Notify\Profile::find($userId);
        $this->model =  $profile->unreadNotifications;
        return $this->sendResponse();
    }
    
}
