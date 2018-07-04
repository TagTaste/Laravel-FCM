<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    public function reviewQuestions(Request $request, $collaborateId, $id)
    {
        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();

        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNotNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();


        foreach ($withNested as $item)
        {
            foreach ($withoutNest as &$data)
            {
                if(isset($data->questions)&&!is_null($data->questions))
                {
                    $data->questions = json_decode($data->questions);
                }
                $i = 0;
                if($item->parent_question_id == $data->id)
                {
                    $item->questions = json_decode($item->questions);
                    $data->questions->question{$i} = $item;
                    $i++;
                }
            }
        }
        $this->model = $withoutNest;
        return $this->sendResponse();
    }

    public function headers(Request $request, $id)
    {
        $this->model = \DB::table('collaborate_tasting_header')->where('collaborate_id',$id)->orderBy('id')->get();

        return $this->sendResponse();
    }
}
