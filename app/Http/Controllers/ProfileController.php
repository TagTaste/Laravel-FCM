<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Profile;
use App\ProfileType;

use Illuminate\Http\Request;

class ProfileController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profileTypes = ProfileType::all();

		$profiles = Profile::with('type','attribute')->orderBy('id', 'asc')->paginate(10);

		return view('profiles.index', compact('profiles','profileTypes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profiles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$attributes = $request->input("attributes");
		
		$typeId = $request->input('typeId');

		$inputsWithFile = \App\ProfileAttribute::select('id')->where('enabled',1)->where('requires_upload',1)->where('profile_type_id','=',$typeId)->get();

		foreach($inputsWithFile as $input){
			$key = 'attributes.' . $input->id;
			if($request->hasFile($key)){
				$file = $request->file($key)['value'];
				$fileName = $key . "." . strtolower(str_random(32)) . "." . $file->extension();
				$file->storeAs('files',$fileName);
				$attributes[$input->id]['value'] = $fileName;
			}

		 }

		$data = [];
		foreach($attributes as $id => $value){
			$data[] = [
				'user_id'=>1,
				'profile_attribute_id' => $id, 'value' => $value['value'], 'type_id' => $typeId];
		}

		$profile = Profile::insert($data);

		return redirect()->route('profiles.index')->with('message', 'Profile created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$userId = 1; //use the logged in user id;


		//$profile = Profile::with('attribute')->where("user_id",1)->get();
		$profile = \DB::table('profiles')
						->join('profile_attributes','profiles.profile_attribute_id','=','profile_attributes.id')
						->select(
							"profile_attributes.id",
							"profile_attributes.multiline",
							"profile_attributes.requires_upload",
							"profile_attributes.label",
							"profiles.value")
						->where("profiles.user_id",$userId)
						->where("profiles.type_id",$id)
						->where("profile_attributes.enabled",1)->get();


		return view('profiles.show', compact('profile'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile = Profile::findOrFail($id);

		return view('profiles.edit', compact('profile'));
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
		$profile = Profile::findOrFail($id);

		$profile->user_id = $request->input("user_id");
        $profile->attribute_id = $request->input("attribute_id");
        $profile->value = $request->input("value");
        $profile->type_id = $request->input("type_id");

		$profile->save();

		return redirect()->route('profiles.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile = Profile::findOrFail($id);
		$profile->delete();

		return redirect()->route('profiles.index')->with('message', 'Item deleted successfully.');
	}

	public function fileDownload($file){
		return response()->file(storage_path("app/files/" . $file)) ;
	}

}
