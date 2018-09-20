<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Tag;
use App\Events\Model\Subscriber\Create;
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
            \Log::info($image);
            \Log::info($filePath);
            $resp = $s3->putFile($filePath, new File(storage_path($image)), ['visibility'=>'public']);
            if($resp){
                $inputs['preview']['image'] = $resp;
                \File::delete(storage_path($image));
            }
            else
            {
                $inputs['preview']['image'] = null;
            }
        }
        if(isset($inputs['preview']))
        {
            $inputs['preview'] = json_encode($inputs['preview']);
        }

        if($request->has('media_file'))
        {
            $path = Shoutout::getProfileMediaPath($profile->id);
            $filename = $request->file('media_file')->getClientOriginalName();
            $filename = str_random(15).".".\File::extension($filename);
            $inputs['media_url'] = $request->file("media_file")->storeAs($path, $filename,['visibility'=>'public']);
            $mediaJson =  $this->videoTranscodingNew($inputs['media_url']);
            $mediaJson = json_decode($mediaJson,true);
            $inputs['cloudfront_media_url'] = $mediaJson['cloudfront_media_url'];
            $inputs['media_json'] = json_encode($mediaJson['media_json'],true);
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
        $shoutout->addToCache();

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

        //$file = file_get_contents($avatar);
        $file = $this->get_web_page($url);
        $filename = str_random(20) . ".jpg";
        $path = 'images/p/' . $profileId . "/simages/";
        $path = storage_path($path);

        if(!is_dir($path) && !mkdir($path,0755,true)){
            \Log::info("Did not create directory.");
        }
        $filename = $path . "/" . $filename;
        file_put_contents($filename,$file);
        return $filename;

	    $path = 'images/p/' . $profileId . "/simages/";
        \Storage::disk('local')->makeDirectory($path);
        $filename = str_random(32) . ".jpg";
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

    /**
     * This function is execute our native video transcoder which direct to 
     */
    private function videoTranscodingNew($url)
    {
        
        $profileId = request()->user()->profile->id;
        $curl = curl_init();
        $data = [
            'profile_id' => $profileId,
            'file_path' => $url
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('TRANSCODING_APIGATEWAY_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $body = $response->body;
        return json_encode($body,true);
    }

    public function get_web_page( $url )
    {
        $url = urldecode($url);
        $url = htmlspecialchars_decode($url);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_CAINFO => app_path("cacert.pem")
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
        \Log::debug($err);
        \Log::debug($errmsg);
        \Log::debug($header);
        return $content;

//        $header['errno']   = $err;
//        $header['errmsg']  = $errmsg;
//        $header['content'] = $content;
//        return $header;
    }
}