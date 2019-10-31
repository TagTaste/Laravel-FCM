<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HandleController extends Controller
{
	public function show(Request $request,$handle)
	{
	    $model = \DB::table("profiles")->select("id")->where('handle','like',$handle)->first();
        
        if($model){
            $this->model = ['type'=>"profile",'id'=>$model->id];
            return $this->sendResponse();
        }
        
        $model = \DB::table('companies')->select('id')->where('handle',$handle)->first();
        
        if($model){
            $company = Redis::get("company:small:" . $model->id);
            if(!$company){
                throw new \Exception("Company {$model->id} not found in cache.");
            }
            $this->model = ['type'=>"company",'id'=>$model->id,'company'=>json_decode($company)];
            return $this->sendResponse();
        }
        

        return $this->sendError("$handle Handle not found.");
	}
}