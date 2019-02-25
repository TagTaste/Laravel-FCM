<?php

namespace App\Http\Controllers\Api;

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
        return $this->sendResponse();
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
        return $this->sendResponse();
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

        return $this->sendResponse();
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
        return $this->sendResponse();
    }

    public function categoriesQuestionAnswer(Request $request, $id)
    {
        $this->model = $this->model->where('faq_category_id',$id)->get();
        return $this->sendResponse();
    }

    public function allCategories()
    {
        $this->model = \DB::table('faq_categories')->get();
        return $this->sendResponse();
    }

    public function storeCategories(Request $request)
    {
        $inputs = [];
        $inputs['name'] = $request->input('name');
        $inputs['description'] = $request->input('description');
        $inputs['created_at'] = Carbon::now()->toDateTimeString();
        $inputs['updated_at'] = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('faq_categories')->input($inputs);
        return $this->sendResponse();
    }
}