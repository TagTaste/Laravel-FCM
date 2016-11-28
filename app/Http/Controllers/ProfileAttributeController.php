<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProfileAttribute;
use App\ProfileType;
use Illuminate\Http\Request;

class ProfileAttributeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profile_attributes = ProfileAttribute::paginate(10);
		//dd($profile_attributes->groupBy('parent_id'));

		return view('profile_attributes.index', compact('profile_attributes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$profileTypes = ProfileType::getTypes();
		
		$inputTypes = $this->getInputTypes();

		return view('profile_attributes.create',compact('profileTypes','inputTypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
	
		$inputs = $this->getRequiredInputs($request);

		$inputs['user_id'] = $request->user()->id;
			
		$profile_attribute = ProfileAttribute::create($inputs);

		return redirect()->route('profile_attributes.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);

		return view('profile_attributes.show', compact('profile_attribute'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);

		$profileTypes = ProfileType::getTypes();

		$inputTypes = $this->getInputTypes();

		return view('profile_attributes.edit', compact('profile_attribute', 'profileTypes', 'inputTypes'));
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

		$profile_attribute = ProfileAttribute::findOrFail($id);

		
		$profile_attribute->update($this->getRequiredInputs($request));

		return redirect()->route('profile_attributes.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile_attribute = ProfileAttribute::findOrFail($id);
		$profile_attribute->delete();

		return redirect()->route('profile_attributes.index')->with('message', 'Item deleted successfully.');
	}

	public function form(Request $request, $typeId){
		
		$profileAttributes = ProfileAttribute::with('values')->where('profile_type_id',$typeId)->get();
		
		$hasFileInput = ProfileAttribute::select('id')->where('profile_type_id',$typeId)->where("input_type",'like',"file")->get();

		$encType = 'application/x-www-form-urlencoded';

		if($hasFileInput->count() > 0){
			$encType = 'multipart/form-data';
		}

		//todo: move this to ProfileAttributeController. Add to cache when an input is created.
		\Cache::put("fileInputs." . $typeId, $hasFileInput->toArray());
		//$profile = $profile->groupBy('profile_attribute_id');

		return view('profile_attributes.form',compact('profileAttributes', 'encType', 'typeId'));
	}

	public function formEdit(Request $request, $typeId) {

// select attribute.id, attribute.profile_type_id, attribute.name, attribute.label, av.id, av.name, av.value, profiles.value_id from profile_attributes attribute
// left join attribute_values av on av.attribute_id = attribute.id
// left join profiles on profiles.value_id = av.id 
// where 
// attribute.profile_type_id = 1
// and (profiles.type_id = 1 
// and profiles.user_id = 2
// or profiles.value_id is null 
// or av.attribute_id is null)
// ORDER BY `attribute`.`id`, av.id ASC

		$userId = $request->user()->id;

		$profileAttributes = ProfileAttribute::select('profile_attributes.*','av.id as av_id','av.name as av_name','av.value as av_value','profiles.value_id as p_value','profiles.id as p_id')
		->leftJoin('attribute_values as av','av.attribute_id','=','profile_attributes.id')
		->leftJoin('profiles','profiles.value_id','=','av.id')
		->orderBy('profile_attributes.id','desc')
		->orderBy('av.id','desc')
		->where('profile_attributes.profile_type_id','=',$typeId)
		->where(function($query) use ($userId, $typeId) {
			$query->where('profiles.user_id','=',$userId)
			->where('profiles.type_id','=',$typeId)
			->orWhereNull('profiles.value_id')
			->orWhereNull('av.attribute_id');
		})
		->orderBy('profile_attributes.id','desc')
		->orderBy('av.id','desc')
		->get();

		$profileAttributes = $profileAttributes->groupBy('id');
		return view('profile_attributes.formEdit',compact('profileAttributes','typeId'));
	}

	public function getRequiredInputs($request){
		$input = $request->only('name','label','description','input_type','allowed_mime_types','enabled','required','parent_id','template_id','profile_type_id');
		return array_filter($input);
	}

	public function getInputTypes(){
		return [
				'Short Text'=>'text', 
				'Long Text' => 'textarea',
				'File Upload' => 'file',
				'Dropdown' => 'dropdown',
				'Dropdown with multiple select' => 'dropdown_multiple',
				'Multiple Options, Multiple Select'=>'checkbox',
				'Multiple Options, Single Select' => 'radio'];

	}

}
