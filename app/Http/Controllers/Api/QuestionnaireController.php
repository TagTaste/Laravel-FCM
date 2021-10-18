<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;

class QuestionnaireController extends Controller
{
    use SendsJsonResponse;
    public function index($type, Request $request)
    {

        if ($type == "public") {
        } else if ($type == "private") {
            $validator = Validator::make($request->all(), [
                'track_consistency' => 'required',
                'title' => 'required',
                'description' => 'nullable',
                'keyword' => 'required',
                'header_info' => 'required|array',
                'question_json' => 'required|array'
            ]);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }


            $data = [
                'name' => $request->title, 'keywords' => $request->keyword, 'description' => $request->description ?? null,
                'question_json' => json_encode($request->question_json,true), 'header_info' => json_encode($request->header_info, true), 'track_consistency' => $request->track_consistency
            ];
            $this->model = \DB::table('global_questions')->insert($data);
            return $this->sendResponse();
        }


        return $this->sendError("Invalid Type");
    }
}
