<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

class LikeController extends Controller
{
      public function like(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $model = $request->input('model');
        $upper = ucFirst($model);
        $upper = $upper."Like";
        $model = $model.'_id';
        $id = $request->input('model_id');
        $profileLiked = \App::make("\App\\".$upper)->where($model,$id)->select('profile_id')->get();
        $profile = \App\Profile::whereIn('id',$profileLiked)->get();
        return response()->json($profile);

    }
}
