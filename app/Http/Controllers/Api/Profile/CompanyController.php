<?php

namespace App\Http\Controllers\Api\Profile;

use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;

class CompanyController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Company::whereHas('user.profile',function($query) use ($profileId) {
            $query->where('profiles.id',$profileId);
        })->first();
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
        $this->model = $request->user()->profile->companies()->create($request->only(['name','description']));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $this->model = Company::where('profile_id',$profileId)->where('id',$albumId)->first();

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
        $this->model = $request->user()->profile->companies()
            ->where('id',$request->input('id'))->update($request->only('name','description'));
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
        $this->model = $request->user()->profile->companies()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
