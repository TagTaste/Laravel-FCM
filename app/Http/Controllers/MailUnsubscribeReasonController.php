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
    	$count = \DB::table('unsubscribe_reasons')->where('reason',$input['reason'])->count();
    	if($count === 0)
    	{
    		$this->model = \DB::table('unsubscribe_reasons')->insert($input);
    		return 1;	
    	}
    	return 0;
    	
    }
}
