<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailUnsubscribeReasonController extends Controller
{
    //
    public $model;

    public function index()
    {
    	$this->model = \DB::table('unsubscribe_reasons')->get();
    	return $this->model;
    }

    public function store(Request $request)
    {
    	$input = $request->all();
    	$exist = \DB::table('unsubscribe_reasons')->where('reason',$input['reason'])->exists();
    	if(!$exist)
    	{
    		$this->model = \DB::table('unsubscribe_reasons')->insert($input);
    		return 1;	
    	}
    	return $this->sendError("Reason already stored");
    	
    }
}
