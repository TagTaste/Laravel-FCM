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
        return response()->json(["data"=>$this->model,"error"=>"","status"=>200]); 
    }

}
