<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Album;
use App\Company;
use App\Http\Controllers\Controller;
use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId, $companyId)
    {
        $this->model = Album::forCompany($companyId)->paginate(10);

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
    public function store(Request $request, $profileId, $companyId)
    {
        $userId = $request->user()->id;
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }

        $this->model = $company->albums()->create($request->only(['name','description']));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId, $companyId, $id)
    {
        $this->model = Album::forCompany($companyId)->with('photos')->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Album not found.");
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
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        $input = $request->only(['name','description']);
        $input = array_filter($input);

        $this->model = $company->albums()->where('id',$id)->update($input);

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $companyId,$id)
    {
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        $input = $request->only(['name','description']);
        $input = array_filter($input);

        $this->model = $company->albums()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
