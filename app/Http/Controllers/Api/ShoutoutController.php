<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Tag;
use App\Events\Model\Subscriber\Create;
use App\Http\Requests\API\Shoutout\StoreRequest;
use App\Http\Requests\API\Shoutout\UpdateRequest;
use App\Shoutout;
use App\Traits\CheckTags;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class ShoutoutController extends Controller
{
    use CheckTags;
	/**
	 * Variable to model
	 *
	 * @var shoutout
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Shoutout $model)
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
		//we never return all of the shoutouts
        return;
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
		
		//move this to validator
        if(empty($inputs['profile_id']) && empty($inputs['company_id'])){
            return $this->sendError("Missing owner information");
        }
  
		try {
            $this->verifyOwner($request);
        } catch (\Exception $e){
		    //if there's an error, just log it.
		    //Log::warning($e->getMessage());
            $this->model = [];
		    return $this->sendError($e->getMessage());
        }
        
        $inputs['has_tags'] = $this->hasTags($inputs['content']);
        $profile = $request->user()->profile;
        if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
            $image = $this->getExternalImage($inputs['preview']['image'],$profile->id);
            $s3 = \Storage::disk('s3');
            $filePath = 'p/' . $profile->id . "/si";
            $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
            if($resp){
                \File::delete(storage_path($image));
            }
            $inputs['preview']['image'] = $resp;
        }
        if(isset($inputs['preview']))
        {
            $inputs['preview'] = json_encode($inputs['preview']);
        }
		$this->model = $this->model->create($inputs);
        event(new Create($this->model,$profile));
        
        if($inputs['has_tags']){
            event(new Tag($this->model, $profile, $this->model->content));
        }
		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$shoutout = $this->model->where('id',$id)->whereNull('deleted_at')->first();
		if(!$shoutout){
		    return $this->sendError("Shoutout not found.");
        }
        $profileId = $request->user()->profile->id;
        $meta = $shoutout->getMetaFor($profileId);
        $this->model = ['shoutout'=>$shoutout,'meta'=>$meta];
		
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
        $shoutout = $this->model->where('id',$id)->whereNull('deleted_at')->first();

        if(isset($shoutout->company_id))
        {
            $companyId = isset($inputs['company_id']) ? $inputs['company_id'] : null;
            if($shoutout->company_id != $companyId)
            {
                return $this->sendError("User does not belong to this post.");
            }
            $checkAdmin = CompanyUser::where("company_id",$inputs['company_id'])->where('profile_id', $request->user()->profile->id)->exists();

            if (!$checkAdmin) {
                return $this->sendError("User does not belong to this post.");
            }
            unset($inputs['company_id']);
        }
        else if(isset($shoutout->profile_id) && $shoutout->profile_id != $request->user()->profile->id)
        {
            return $this->sendError("User does not belong to this post.");
        }

        $inputs['has_tags'] = $this->hasTags($inputs['content']);
        $profile = $request->user()->profile;
        if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
            $image = $this->getExternalImage($inputs['preview']['image'],$profile->id);
            $s3 = \Storage::disk('s3');
            $filePath = 'p/' . $profile->id . "/si";
            $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
            if($resp){
                \File::delete(storage_path($image));
            }
            $inputs['preview']['image'] = $resp;
        }
        if(isset($inputs['preview']))
        {
            $inputs['preview'] = json_encode($inputs['preview']);
        }
        else
        {
            $inputs['preview'] = null;
        }

		$this->model = $shoutout->update($inputs);

        $shoutout = Shoutout::where('id',$id)->whereNull('deleted_at')->first();
        $profileId = $request->user()->profile->id;
        $meta = $shoutout->getMetaFor($profileId);
        $this->model = ['shoutout'=>$shoutout,'meta'=>$meta];

        if($inputs['has_tags']){
            event(new Tag($shoutout, $profile, $this->model['shoutout']->content));
        }

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
        try {
            $this->verifyOwner($request);
        } catch (\Exception $e){
            //if there's an error, just throw it.
            throw $e;
        }
        
		$this->model = $this->model->destroy($id);
        return $this->sendResponse();
	}
    
    private function verifyOwner(Request &$request)
    {
        if($request->has('company_id') && $request->input('company_id') !== null){
            $company = Company::find($request->input('company_id'));
            if(!$company){
                throw new \Exception("Company doesn't exist.");
            }
            $userId = $request->user()->id;
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                throw new \Exception("User doesn't belong to this company");
            }
        }
    
        if($request->has('profile_id') && $request->input('profile_id') !== null){
            if($request->input('profile_id') != $request->user()->profile->id){
                throw new \Exception("User doesn't belong to this profile.");
            }
        }
        
        if($request->input('company_id') !== null && $request->input('profile_id') !== null){
            throw new \Exception("Missing Profile Id or company id");
        }
	}
    
    public function like(Request $request, $id)
    {
        return;
	}
    
    public function getExternalImage($url,$profileId){
	    $path = 'images/p/' . $profileId . "/simages/";
        \Storage::disk('local')->makeDirectory($path);
        $filename = str_random(10) . ".jpg";
        $saveto = storage_path("app/" . $path) .  $filename;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);
        
        $fp = fopen($saveto,'a');
        fwrite($fp, $raw);
        fclose($fp);
        return "app/" . $path . $filename;
    }
}