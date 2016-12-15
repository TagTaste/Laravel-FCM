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
		$profileTypes = ProfileType::orderBy('type','asc')->get()->keyBy('id');

		$profiles = Profile::whereHas('attribute',function($query){
		    $query->where('enabled',1);
        })->where('user_id','=',$request->user()->id)->with('type','attribute')->orderBy('id', 'asc')->get()->groupBy('type_id');

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
		
		$user = $request->user();

        $profileAttributes = \App\ProfileAttribute::where('enabled','=',1)->type($typeId)->with('values')->get();

        $profile = $user->profile()->profileType($typeId)->get();

		$profile = $profile->groupBy('profile_attribute_id');
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
        $inputProfile = $request->input('profile');
        $typeId = $request->input('typeId');
         if(count($inputProfile)){

            $profile = \App\Profile::whereIn('id',array_keys($inputProfile))->get()->keyBy('id');

            if($profile){
                foreach($profile as $p){

                    //var_dump(isset($inputProfile[$p->id]));
                    if(isset($inputProfile[$p->id])){
                        $value = $inputProfile[$p->id];

                        $updatedValue = null;
                        if(!is_array($value)){
                            if($p->value != $value){
                                $p->value = $value;
                            }
                        } else {

                            if(isset($value['value_id'][0]) && is_array($value['value_id'])){
                                $p->value_id = $value['value_id'][0];
                            }

                        }

                        if(!is_null($p->isDirty())){
                            $p->update();
                        }


                    }
                }
            }

            //don't delete chef_ids, etc
            $profileIds = \App\ProfileAttribute::select('id')->where('name','like',"%_id")->get()->pluck('id')->toArray();


            //delete other profiles
            \App\Profile::whereNotIn('id',array_keys($inputProfile))->whereNotIn('profile_attribute_id',$profileIds)->delete();
        } else {
             //profile for this type hasn't been created yet.
             //create profileId first

             Profile::createProfileId($request->user()->id,$typeId);
         }

		$attributes = $request->input("attributes");
        if(count($attributes) > 0){
            $attributes = array_filter($attributes);
        }



		$inputsWithFile = \App\ProfileAttribute::select('id')->where('enabled',1)->where('input_type','like','file')->where('profile_type_id','=',$typeId)->get();

		//check for existing files
        $existingProfile = \App\Profile::whereIn('profile_attribute_id',$inputsWithFile->pluck('id')->toArray())->get()->keyBy('profile_attribute_id');

		foreach($inputsWithFile as $input){
			$key = 'attributes.' . $input->id;
			if($request->hasFile($key)){

				$file = $request->file($key);
				$fileName = "a{$input->id}" . strtolower(str_random(32)) . "." . $file->extension();
				$file->storeAs('files',$fileName);

				//check if file for this attribute exists
                if($existingProfile->get($input->id)){
                    $prof = $existingProfile->get($input->id);
                    $prof->value = $fileName;
                    $prof->update();
                    unset($attributes[$input->id]);
                } else {
                    $attributes[$input->id]['value'] = $fileName;
                }


			}

		}
		$dataMultiple = [];
        $dataSingle = [];
		$userId = $request->user()->id;
		if(count($attributes) > 0){

			foreach($attributes as $id => $value){

				$single = [
				'user_id'=> $userId,
				'profile_attribute_id' => $id, 
				'type_id' => $typeId
				];


				if(isset($value['value_id'])){
					foreach($value['value_id'] as $valueId){
						$single['value_id'] = $valueId;
                        $dataMultiple[] = $single;
					}
				} elseif(isset($value['value'])) {

					$single['value'] = $value['value'];
                    $dataMultiple[] = $single;
				} else {
					$single['value'] = $value;
                    $dataSingle[] = $single;
				}

			}

			\App\Profile::insert($dataMultiple);
			\App\Profile::insert($dataSingle);

		}



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
