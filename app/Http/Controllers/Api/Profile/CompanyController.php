<?php

namespace App\Http\Controllers\Api\Profile;

use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->only(['name','about','logo','hero_image','phone',
            'email','registered_address','established_on', 'status_id',
            'type','employee_count','client_count','annual_revenue_start',
            'annual_revenue_end',
            'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url',
        ]);
        $this->model = $request->user()->companies()->create(array_filter($inputs));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profileId,$id)
    {
        $this->model = $request->user()->companies()->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Company not found.");
        }

        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $this->model = $request->user()->companies()
            ->where('id',$id)->update($request->only(['address','phone','country']));
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$profileId,$id)
    {
        $this->model = $request->user()->companies()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
