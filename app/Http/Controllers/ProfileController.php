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
	public function index(Request $request)
	{
		$profileTypes = ProfileType::orderBy('type','asc')->get();

		$profiles = Profile::where('user_id','=',$request->user()->id)->with('type','attribute')->orderBy('id', 'asc')->paginate(10);

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

	private function inputHasFile(&$typeId, &$requst, &$attributes){
		$inputsWithFile = \App\ProfileAttribute::select('id')->where('enabled',1)->where('input_type','like','file')->where('profile_type_id','=',$typeId)->get();

		foreach($inputsWithFile as $input){
			$key = 'attributes.' . $input->id;
			if($request->hasFile($key)){
				$file = $request->file($key)['value'];
				$fileName = $key . "." . strtolower(str_random(32)) . "." . $file->extension();
				$file->storeAs('files',$fileName);
				$attributes[$input->id]['value'] = $fileName;
			}

		 }
	}

	private function processData(&$attributes, &$userId, &$typeId) {
		$data = [];
		foreach($attributes as $id => $value){

			$single = [
				'user_id'=> $userId,
				'profile_attribute_id' => $id, 
				'type_id' => $typeId
				];


			if(isset($value['value_id'])){
				foreach($value['value_id'] as $valueId){
					$single['value_id'] = $valueId;
					$data[] = $single;
				}
			} else {
				$single['value'] = $value['value'];
				$data[] = $single;
			}
			
		}
		return $data;
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

		$inputsWithFile = \App\ProfileAttribute::select('id')->where('enabled',1)->where('input_type','like','file')->where('profile_type_id','=',$typeId)->get();

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
		$userId = $request->user()->id;
		foreach($attributes as $id => $value){

			$single = [
				'user_id'=> $userId,
				'profile_attribute_id' => $id, 
				'type_id' => $typeId
				];


			if(isset($value['value_id'])){
				foreach($value['value_id'] as $valueId){
					$single['value_id'] = $valueId;
					$data[] = $single;
				}
			} else {
				$single['value'] = $value['value'];
				$data[] = $single;
			}
			
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
	public function show(Request $request, $typeId) 
	{

		$userId = $request->user()->id; //use the logged in user id;

		$profile = Profile::with('attribute','attributeValue')->where('user_id',$userId)->where('type_id',$typeId)->whereHas('attribute',function($query){
			$query->where('enabled',1);
		})->get();

		$profile = $profile->groupBy("attribute.id");

		return view('profiles.show', compact('profile','typeId'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $typeId)
	{	
		
		$userId = $request->user()->id;

		$profileAttributes = \App\ProfileAttribute::type($typeId)->with('values')->get();

		$profile = \App\Profile::profileType($typeId)->forUser($userId)->get();
		$profile = $profile->keyBy('profile_attribute_id');
		$encType = null;

		return view('profiles.edit',compact('profile','profileAttributes','typeId','encType'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request)
	{
		$attributes = $request->input("attributes");
		
		$typeId = $request->input('typeId');

		$inputsWithFile = \App\ProfileAttribute::select('id')->where('enabled',1)->where('input_type','like','file')->where('profile_type_id','=',$typeId)->get();

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
		$userId = $request->user()->id;
		foreach($attributes as $id => $value){

			$single = [
				'user_id'=> $userId,
				'profile_attribute_id' => $id, 
				'type_id' => $typeId
				];


			if(isset($value['value_id'])){
				foreach($value['value_id'] as $valueId){
					$single['value_id'] = $valueId;
					$data[] = $single;
				}
			} else {
				$single['value'] = $value['value'];
				$data[] = $single;
			}
			
		}

		$profile = Profile::insert($data);

		return redirect()->route('profiles.index')->with('message', 'Profile created successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$profile = Profile::where('id','=',$id)->where("user_id",'=',$request->user()->id)->first();
		$profile->delete();

		return redirect()->route('profiles.index')->with('message', 'Item deleted successfully.');
	}

	public function fileDownload($file){
		return response()->file(storage_path("app/files/" . $file)) ;
	}

}
