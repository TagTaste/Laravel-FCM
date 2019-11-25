<?php

namespace App\Http\Controllers\Api\V2;

use App\CampusConnect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\Controller;

class CampusConnectController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CampusConnect $model)
	{
		$this->model = $model;
	}

	public function store(Request $request)
	{
        $this->errors['status'] = 0;
        $inputs = $request->all();

        //move this to validator
        if(empty($inputs['campus_name']) && empty($inputs['campus_name'])){
            $this->errors['message'] = 'Missing campus name.';
            $this->errors['status'] = 1;
            return $this->sendResponse();
        }

        $user = $request->user();
        if (is_null($user)) {
            $this->errors['message'] = 'Invalid User.';
            $this->errors['status'] = 1;
            return $this->sendResponse();
        }

        $profile = $user->profile;
        if (!isset($profile) && is_null($profile)) {
            $this->errors['message'] = 'User profile not exist.';
            $this->errors['status'] = 1;
            return $this->sendResponse();
        }

        // $mandatory_field = $profile->profile_completion['mandatory_field_for_campus_connect'];
        // if (count($mandatory_field)) {
        //     $this->errors['message'] = 'User profile is incomplete check mandatory filed.';
        //     $this->errors['mandatory_field'] = $mandatory_field;
        //     $this->errors['status'] = 1;
        //     return $this->sendResponse();
        // }

        $logged_in_profile_id = $profile->id;
        $inputs['profile_id'] = $logged_in_profile_id;
        $inputs['created_at'] = Carbon::now();
        $inputs['updated_at'] = Carbon::now();
        $this->model = $this->model->create($inputs);
        
        event(new \App\Events\CampusConnectRequestEvent($logged_in_profile_id,$user->email,$inputs['campus_name'],null));
        return $this->sendResponse();
	}

}
