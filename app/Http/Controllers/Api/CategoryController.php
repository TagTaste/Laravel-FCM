<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Variable to model
     *
     * @var category
     */
    protected $model;
    
    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Category $model)
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
        $this->model = Category::with('children')->paginate();
        
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
        
        $category = Category::checkExists($inputs);
        
        if ($category) {
            $this->model = [];
            return $this->sendError("This category already exists with the given parent.");
        }
        
        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $this->model = $this->model::where('id',$id)->with('children')->paginate();
        
        if(!$this->model){
            return $this->sendError("Category not found.");
        }
        
        return $this->sendResponse();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        
        $category = $this->model->find($id);
        
        if(!$category){
            return $this->sendError("Category not found.");
        }
        
        $this->model = $category->update($inputs);
        
        return $this->sendResponse();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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