<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class CollaborateReviewController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function reviewQuestions(Request $request, $collaborateId, $id)
    {
        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();
        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNotNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();

        foreach ($withoutNest as &$data)
        {
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $data->questions = json_decode($data->questions);
            }
        }
        $i = 0;
        foreach ($withNested as $item)
        {
            foreach ($withoutNest as &$data)
            {
                if($item->parent_question_id == $data->id)
                {
                    $item->questions = json_decode($item->questions);
                    $item->questions->id = $item->id;
                    $item->questions->is_nested = $item->is_nested;
                    $item->questions->is_mandatory = $item->is_mandatory;
                    $item->questions->is_active = $item->is_active;
                    $item->questions->parent_question_id = $item->parent_question_id;
                    $item->questions->header_type_id = $item->header_type_id;
                    $item->questions->collaborate_id = $item->collaborate_id;
                    $data->questions->questions{$i} = $item->questions;
                    $i++;
                }
            }
        }
        $model = [];
        foreach ($withoutNest as $data)
        {
            $data->questions->id = $data->id;
            $data->questions->is_nested = $data->is_nested;
            $data->questions->is_mandatory = $data->is_mandatory;
            $data->questions->is_active = $data->is_active;
            $data->questions->parent_question_id = $data->parent_question_id;
            $data->questions->header_type_id = $data->header_type_id;
            $data->questions->collaborate_id = $data->collaborate_id;

            $model[] = $data->questions;
        }
        $this->model = $model;
        return $this->sendResponse();
    }

    public function headers(Request $request, $id)
    {
        $this->model = \DB::table('collaborate_tasting_header')->where('collaborate_id',$id)->orderBy('id')->get();

        return $this->sendResponse();
    }

    public function insertHeaders(Request $request, $id)
    {
        $inputs = $request->input('header_type');
        $data = [];
        foreach ($inputs as $input)
        {
            $data[] = ['header_type'=>$input,'is_active'=>1,'collaborate_id'=>$id];
        }
        $this->model = ReviewHeader::insert($data);

        return $this->sendResponse();
    }

    public function insertQuestions(Request $request, $collaborateId, $headerId)
    {
        $title = $request->input('title');
        $subTitle = $request->has('subtitle') ? !is_null($request->input('subtitle')) ? $request->input('subtitle') : null : null;
        $isNested = $request->input('is_nested');
        $parentQueId = $request->has('parent_question_id') ? !is_null($request->input('parent_question_id'))
            ? $request->input('parent_question_id') : null : null ;

        $questions = $request->input('questions');

        $this->model = \DB::table('collaborate_tasting_questions')->insert(['title'=>$title,'subtitle'=>$subTitle,'is_nested'=>$isNested,
            'parent_question_id'=>$parentQueId,'is_active'=>1,'is_mandatory'=>1,'questions'=>$questions,'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId]);
        return $this->sendResponse();


    }
}
