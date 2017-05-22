<?php

namespace App\Http\Controllers;

use App\Collaborate;
use App\CollaborationField;
use App\Field;
use Illuminate\Http\Request;

class CollaborationFieldController extends Controller
{
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($collaborationId)
	{
		$this->model = CollaborationField::where('collaboration_id',$collaborationId)->get();
        return $this->sendResponse();
	}
 

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $collaborationId)
	{
		$fieldId = $request->input('field_id');
		
		$collab = Collaborate::find($collaborationId);
		
		if(!$collab){
		    return $this->sendError("Collaboration Not Found.");
        }
        
        $field = Field::find($fieldId);
		
		if(!$field){
		    return $this->sendError("Field not found.");
        }
        
		$this->model = $collab->addField($field);

		return $this->sendResponse();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($collaborationId, $id)
	{
        $collab = Collaborate::find($collaborationId);
        
        if(!$collab){
            return $this->sendError("Collaboration Not Found.");
        }
        
        $field = Field::find($id);
        
        if(!$field){
            return $this->sendError("Field not found.");
        }
        
        $this->model = $collab->removeField($field);

		return redirect()->route('collaboration_fields.index')->with('message', 'Item deleted successfully.');
	}
}