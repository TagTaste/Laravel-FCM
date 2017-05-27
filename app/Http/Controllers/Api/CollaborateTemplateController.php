<?php

namespace App\Http\Controllers\Api;

use App\CollaborateTemplate;
use Illuminate\Http\Request;

class CollaborateTemplateController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate_template
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CollaborateTemplate $model)
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
		$this->model = $this->model->paginate();
        return $this->sendResponse();
	}
    
    private function addFields(&$fields)
    {
        foreach($fields as &$field){
            $field['template_id'] = $this->model->id;
        }
        
        return \DB::table('collaboration_template_fields')->insert($fields);
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
        $fields = null;
		
		if($request->has('fields')){
            $fields =  $request->input('fields');
		    unset($inputs['fields']);
        }
        
		$this->model = $this->model->create($inputs);
  
		
		$status = $this->addFields($fields);

		if(!$status){
		    return $this->sendError("Could not create template.");
        }
        
		$this->model = $this->model->fresh();
		
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
		$this->model = $this->model->findOrFail($id);
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

		$collaborate_template = $this->model->findOrFail($id);
        
        if($request->has('fields')){
            unset($inputs['fields']);
            $fields = $request->input('fields');
            
            \DB::table("collaborate_template_fields")->where("template_id",$collaborate_template->id)->delete();
            
            $status = $this->addFields($fields);
        }
        $this->model =	$collaborate_template->update($inputs);
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
}