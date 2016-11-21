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
		$profile_attributes = ProfileAttribute::orderBy('id', 'desc')->paginate(10);

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
		return view('profile_attributes.create',compact('profileTypes'));
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

		return view('profile_attributes.edit', compact('profile_attribute', 'profileTypes'));
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

	public function form($typeId){
		
		$profileAttributes = ProfileAttribute::where('profile_type_id',$typeId)->get();

		$hasFileInput = ProfileAttribute::select('id')->where('profile_type_id',$typeId)->where("requires_upload",1)->get();

		$encType = 'application/x-www-form-urlencoded';

		if($hasFileInput->count() > 0){
			$encType = 'multipart/form-data';
		}

		//todo: move this to ProfileAttributeController. Add to cache when an input is created.
		\Cache::put("fileInputs." . $typeId, $hasFileInput->toArray());

		return view('profile_attributes.form',compact('profileAttributes', 'encType', 'typeId', 'hasFileInput'));
	}

	public function getRequiredInputs($request){
		$input = $request->only('name','label','description','user_id','multiline','requires_upload','allowed_mime_types','enabled','required','parent_id','template_id','profile_type_id');
		return array_filter($input);
	}

}
