<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailUnsubscribeReasonController extends Controller
{
    //
    public $model;

    public function index()
    {
    	$this->model['data'] = \DB::table('unsubscribe_reasons')->get();
    	if($this->model['data'] != NULL);
    	{
    		$this->model['status'] = "success";
    	}
    	return $this->model;
    }

}
