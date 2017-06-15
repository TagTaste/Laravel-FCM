<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\CompanyCatalogue;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class CompanyCatalogueController extends Controller
{
    /**
     * Variable to model
     *
     * @var company_catalogue
     */
    protected $model;
    
    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(CompanyCatalogue $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($profileId, $companyId)
    {
        $this->model = CompanyCatalogue::where('company_id', $companyId)->paginate(10);
        
        
        return $this->sendResponse();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $profileId, $companyId)
    {
        $inputs = $request->all();
        $userId = $request->user()->id;
        $company = Company::where('id', $companyId)->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new \Exception("User does not belong to this company.");
        }
        $inputs['company_id'] = $companyId;
        if (!$request->hasFile('image') && empty($request->input('image)'))) {
            return $this->sendError("Image missing.");
        }
        
        $imageName = str_random(32) . ".jpg";
        $request->file('image')->storeAs(CompanyCatalogue::getCompanyImagePath($profileId, $companyId), $imageName);
        $inputs['image'] = $imageName;
        
        $catalogue = CompanyCatalogue::checkExists($inputs);
        if ($catalogue) {
            $this->model = [];
            return $this->sendError("This catalogue already exists with the given company.");
        }
        $this->model = $this->model->create($inputs);
        
        return $this->sendResponse();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($profileId, $companyId, $id)
    {
        
        
        $this->model = CompanyCatalogue::where('company_id', $companyId)->where('id', $id)->first();
        if (!$this->model) {
            throw new \Exception("Catalogue not found.");
        }
        
        return $this->sendResponse();
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $inputs = $request->all();
        
        $userId = $request->user()->id;
        
        $company = Company::where('id', $companyId)->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new \Exception("User does not belong to this company.");
        }
        
        $this->model = $this->model->findOrFail($id);
        $this->model->update($inputs);
        
        return $this->sendResponse();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $userId = $request->user()->id;
        
        $company = Company::where('id', $companyId)->where('user_id', $userId)->first();
        
        if (!$company) {
            throw new \Exception("User does not belong to this company.");
        }
        $this->model = $this->model->find($id);
        
        if (!$this->model) {
            return $this->sendError("Model not found.");
        }
        $this->model = $this->model->delete();
        
        return $this->sendResponse();
    }
}