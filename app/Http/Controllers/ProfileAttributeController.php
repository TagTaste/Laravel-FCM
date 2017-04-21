<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

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
	public function create($parentId = null)
	{
	    $parent = null;
	    if($parentId){
	        $parent = ProfileAttribute::find($parentId);

	        if(!$parent){
	            throw new \Exception("Could not find parent.");
            }
        }
		$profileTypes = ProfileType::getTypes();
		
		$inputTypes = ProfileAttribute::getInputTypes();



		return view('profile_attributes.create',compact('profileTypes','inputTypes','parent'));
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
		$profileAttribute = ProfileAttribute::with('children')->findOrFail($id);
		return view('profile_attributes.show', compact('profileAttribute'));
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

		$inputTypes = ProfileAttribute::getInputTypes();

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

		$userId = $request->user()->id;

		$profileAttributes = ProfileAttribute::type($typeId)->with('values')->get();

		$profile = \App\Profile::profileType($typeId)->forUser($userId)->with('attributeValue')->get();

		// dd($profile->toArray());

		// $profileAttributes = ProfileAttribute::
		// 	select('profile_attributes.*','av.id as av_id','av.name as av_name','av.value as av_value','profiles.value as value','profiles.value_id as p_value','profiles.id as p_id')
		// 	->leftJoin('profiles',function($join) use ($userId,$typeId) {
		// 		$join->on('profiles.profile_attribute_id','=','profile_attributes.id')
		// 			->where('profiles.user_id','=',$userId)
		// 			->where('profiles.type_id','=',$typeId);
		// 	})
		// 	->leftJoin('attribute_values as av','av.attribute_id','=','profile_attributes.id')
		// 	->where('profile_attributes.profile_type_id','=',$typeId)->get();

		// dd($profileAttributes);
		// $profileAttributes = $profileAttributes->groupBy('id');
		$encType = null;
		return view('profiles.edit',compact('profile','profileAttributes','typeId','encType'));
	}

	public function getRequiredInputs($request){
		$input = $request->only('name','label','description','input_type','allowed_mime_types','enabled','required','parent_id','template_id','profile_type_id');
		return array_filter($input);
	}

}
