<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\CompanyUser;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var company_user
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CompanyUser $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$profileId,$companyId)
	{
		$this->model = CompanyUser::where('company_id',$companyId)->get();
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
        $userId = $request->input('user_id');
        $loggedInUserId = $request->user()->id;
        $company = Company::where('id', $companyId)->where('user_id', $loggedInUserId)->first();
        
        if (!$company) {
            throw new \Exception("Company does not belongs this user.");
        }
        
        $companyUser = CompanyUser::where('company_id', $companyId)->where('user_id', $userId)->first();
        if ($companyUser) {
            throw new \Exception("User already exist to this company.");
        }
        
        $this->model = $this->model->create($inputs);
        
        return $this->sendResponse();
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_user = $this->model->findOrFail($id);
		
		return view('company_users.show', compact('company_user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_user = $this->model->findOrFail($id);
		
		return view('company_users.edit', compact('company_user'));
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

		$company_user = $this->model->findOrFail($id);		
		$company_user->update($inputs);

		return redirect()->route('company_users.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model->destroy($id);

		return redirect()->route('company_users.index')->with('message', 'Item deleted successfully.');
	}

}