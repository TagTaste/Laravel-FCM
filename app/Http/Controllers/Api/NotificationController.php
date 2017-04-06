<?php

namespace App\Http\Controllers\Api;

use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;

class NotificationController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->model = $request->user()->notifications;
        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->model =  $request->user()->notifications->where('id',$id)->first();
        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->model = $request->user()->notifications()->where('id',$id)->delete();
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
        $this->model = $request->user()->notifications()->where('id',$id)
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
        $this->model = $request->user()->unreadNotifications;
        return $this->sendResponse();
    }
    
}
