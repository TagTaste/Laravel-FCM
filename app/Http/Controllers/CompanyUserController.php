<?php

namespace App\Http\Controllers;

use App\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
	public function index(Request $request, $profileId, $companyId)
	{
		$company_users = $this->model->paginate(10);

		return view('company_users.index', compact('company_users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_users.create');
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
		$this->model->create($inputs);
		console.log($request);
		return redirect()->route('company_users.index')->with('message', 'Item created successfully.');
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