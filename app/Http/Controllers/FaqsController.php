<?php

namespace App\Http\Controllers;

use App\Faqs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    /**
     * Variable to model
     *
     * @var field
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Faqs $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->model = $this->model->get();
        return response()->json(['data'=>$this->model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->model = $this->model->create($inputs);
        return response()->json(['data'=>$this->model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $faq = $this->model->where('id',$id)->first();
        $this->model = $faq->update($inputs);

        return response()->json(['data'=>$this->model]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->model = $this->model->destroy($id);
        return response()->json(['data'=>$this->model]);
    }

    public function categoriesQuestionAnswer(Request $request, $id)
    {
        $checkExist = \DB::table('faq_categories')->where('id',$id)->whereNull('deleted_at')->first();
        if ($checkExist){
            $this->model = $this->model->where('faq_category_id',$id)->where('is_active',1)->get();
            return response()->json(['data'=>$this->model]);
        }
        return response()->json(['data'=>$this->model]);
    }

    public function allCategories()
    {
        $checkExist =
        $this->model = \DB::table('faq_categories')->rightJoin('faqs_question_answer',function($join){
            $join->on('faq_categories.id','=','faqs_question_answer.faq_category_id')->where('faqs_question_answer.is_active',1)->whereNull('faqs_question_answer.deleted_at');
        })->whereNUll('faq_categories.deleted_at')->get();
        return response()->json(['data'=>$this->model]);
    }

    public function storeCategories(Request $request)
    {
        $inputs = [];
        $inputs['name'] = $request->input('name');
        $inputs['description'] = $request->input('description');
        $inputs['created_at'] = Carbon::now()->toDateTimeString();
        $inputs['updated_at'] = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('faq_categories')->insert($inputs);
        return response()->json(['data'=>$this->model]);
    }
}