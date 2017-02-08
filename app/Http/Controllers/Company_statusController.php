<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company_status;
use Illuminate\Http\Request;

class Company_statusController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$company_statuses = Company_status::orderBy('id', 'desc')->paginate(10);

		return view('company_statuses.index', compact('company_statuses'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_statuses.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company_status = new Company_status();

		$company_status->name = $request->input("name");
        $company_status->description = $request->input("description");

		$company_status->save();

		return redirect()->route('company_statuses.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_status = Company_status::findOrFail($id);

		return view('company_statuses.show', compact('company_status'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_status = Company_status::findOrFail($id);

		return view('company_statuses.edit', compact('company_status'));
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
		$company_status = Company_status::findOrFail($id);

		$company_status->name = $request->input("name");
        $company_status->description = $request->input("description");

		$company_status->save();

		return redirect()->route('company_statuses.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company_status = Company_status::findOrFail($id);
		$company_status->delete();

		return redirect()->route('company_statuses.index')->with('message', 'Item deleted successfully.');
	}

}
