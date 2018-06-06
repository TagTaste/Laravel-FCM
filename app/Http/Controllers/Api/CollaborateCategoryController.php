<?php

namespace App\Http\Controllers\Api;

use App\CollaborateCategory;
use Illuminate\Http\Request;

class CollaborateCategoryController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate_category
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CollaborateCategory $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->model = CollaborateCategory::get();

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

        $category = CollaborateCategory::checkExists($inputs);

        if ($category) {
            $this->model = [];
            return $this->sendError("This collaborate category already exists with the given parent.");
        }

        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $this->model = $this->model->where('id',$id)->with('children')->paginate();

        if(!$this->model){
            return $this->sendError("Collaborate Category not found.");
        }

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

        $category = $this->model->find($id);

        if(!$category){
            return $this->sendError("Collaborate category not found.");
        }

        $this->model = $category->update($inputs);
        
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
        $this->model = $this->model->find($id);

        if(!$this->model){
            return $this->sendError("Model not found.");
        }
        $this->model = $this->model->delete();

        return $this->sendResponse();
	}
}