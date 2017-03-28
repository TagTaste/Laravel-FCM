<?php

namespace App\Http\Controllers\Api\Profile;

use App\Company;
use App\Http\Controllers\Controller;
use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class CompanyController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId)
    {
        $this->model = $request->user()->companies;
        return $this->sendResponse();
    }
    
    
    /**
     * @param Request $request
     * @param $profileId Profile id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $profileId)
    {
        $inputs = $request->intersect(['name','about','phone',
            'email','registered_address','established_on', 'status_id',
            'type','employee_count','client_count','annual_revenue_start',
            'annual_revenue_end',
            'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url','websites'
        ]);
        
        $imageName = null;
        $heroImageName = null;
        if($request->hasFile('logo')){
            $imageName = str_random(32) . ".jpg";
            $inputs['logo'] = $imageName;
        }
        
        if($request->hasFile('heroImage')){
            $heroImageName = str_random(32) . ".jpg";
            $inputs['hero_image'] = $heroImageName;
        }
    
        $company = $request->user()->companies()->create(array_filter($inputs));
        \App\Company::getLogoPath($profileId, $company->id);
        $companyId = $company->id;
        
        if($request->hasFile('logo') && $imageName !== null){
            $path = \App\Company::getLogoPath($profileId, $companyId);
            $response = $request->file('logo')->storeAs($path, $imageName);
        }
    
        if($request->hasFile('heroImage')){
            $request->file('heroImage')->storeAs(\App\Company::getHeroImagePath($profileId, $companyId),$imageName);
        }
        
        if($company->isDirty()){
            $company->update();
        }
    
        $this->model = $company;
        return $this->sendResponse();
    }
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show(Request $request, $profileId, $id)
    {
        $this->model = Company::whereHas('user.profile',function($query) use ($profileId){
            $query->where('id',$profileId);
        })->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Company not found.");
        }
        return $this->sendResponse();
    }
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $profileId, $id)
    {
        $inputs = $request->only(['name','about','logo','hero_image','phone',
            'email','registered_address','established_on', 'status_id',
            'type','employee_count','client_count','annual_revenue_start',
            'annual_revenue_end',
            'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url',
            'tagline','establishments','cuisines','websites'
        ]);
        //$inputs = array_filter($inputs);
        $imageName = null;
        $heroImageName = null;
        
        if(!empty($inputs['heroImage'])){
            $heroImageName = str_random(32) . ".jpg";
        }
    
    
        if(!empty($inputs['logo'])){
            $client = new Client();
            $sink = \App\Company::getLogoPath($profileId, $id,$imageName);
            $imageName = str_random(32) . ".jpg";
            $client->request("POST", env('WEBSITE_API_URL') . '/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $inputs['logo']
                ],
                'sink'=> $sink
            ]);
            $inputs['logo'] = $imageName;
        
        }
    
        if(!empty($inputs['hero_image'])){
            $client = new Client();
            $sink = \App\Company::getHeroImagePath($profileId, $id,$imageName);
            $client->request("POST", env('WEBSITE_API_URL') . '/ramukaka/filedena',[
                'form_params' => [
                    'token' => 'ZZ0vWANeIksiv07HJK5Dj74y%@VjwiXW',
                    'file' => $inputs['hero_image']
                ],
                'sink'=> $sink
            ]);
            $inputs['hero_image'] = $imageName;
        }
        
        $this->model = $request->user()->companies()
            ->where('id',$id)->update($inputs);
        return $this->sendResponse();
    }
    
    
    /**
     * @param Request $request
     * @param $profileId Profile Id
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $this->model = $request->user()->companies()->where('id',$id)->delete();
        return $this->sendResponse();
    }
    
    /**
     * Returns company logo
     *
     * @param $profileId
     * @param $id Company id
     * @return mixed
     */
    public function logo($profileId, $id)
    {
        $company = \DB::table('companies')->select('logo')->find($id);
        $path = Company::getLogoPath($profileId, $id,$company->logo);
        return response()->file($path);
    }
    
    /**
     * Returns Company Hero Image
     *
     * @param $profileId
     * @param $id Company Id
     * @return mixed
     */
    public function heroImage($profileId, $id)
    {
        $company = DB::table('companies')->select('hero_image')->find($id);
        $path = Company::getHeroImagePath($profileId, $id,$company->hero_image);
        return response()->file($path);
    }
}
