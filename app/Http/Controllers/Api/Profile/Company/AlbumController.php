<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Album;
use App\Company;
use App\Http\Controllers\Api\Controller;
use \Tagtaste\Api\SendsJsonResponse;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $profileId, $companyId)
    {
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        $inputs = $request->intersect(['name','description']);
        $this->model = $company->albums()->create($inputs);
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
        
        $input = $request->intersect(['name','description']);
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
        
        $this->model = $company->albums()->where('id',$id)->delete();
        //\DB::table('companies')->where('company_id',$company->id)->where('album_id',$id)->delete();
        
        return $this->sendResponse();
    }
}
