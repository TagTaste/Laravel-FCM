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
        $categories = $this->model->paginate();
        
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
            return $this->sendError("This category already exists.");
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
        $this->model = $this->model->findOrFail($id);
        
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
        
        $category = $this->model->findOrFail($id);
        $category->update($inputs);
        
        return redirect()->route('categories.index')->with('message', 'Item updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->model->destroy($id);
        
        return redirect()->route('categories.index')->with('message', 'Item deleted successfully.');
    }
}