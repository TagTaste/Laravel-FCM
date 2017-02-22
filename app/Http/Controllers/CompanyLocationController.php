<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CompanyLocation;
use Illuminate\Http\Request;

class CompanyLocationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$company_locations = CompanyLocation::orderBy('id', 'desc')->paginate(10);

		return view('company_locations.index', compact('company_locations'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_locations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company_location = new CompanyLocation();

		$company_location->address = $request->input("address");
        $company_location->country = $request->input("country");
        $company_location->phone = $request->input("phone");
        $company_location->company_id = $request->input("company_id");

		$company_location->save();

		return redirect()->route('company_locations.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_location = CompanyLocation::findOrFail($id);

		return view('company_locations.show', compact('company_location'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_location = CompanyLocation::findOrFail($id);

		return view('company_locations.edit', compact('company_location'));
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
		$company_location = CompanyLocation::findOrFail($id);

		$company_location->address = $request->input("address");
        $company_location->country = $request->input("country");
        $company_location->phone = $request->input("phone");
        $company_location->company_id = $request->input("company_id");

		$company_location->save();

		return redirect()->route('company_locations.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company_location = CompanyLocation::findOrFail($id);
		$company_location->delete();

		return redirect()->route('company_locations.index')->with('message', 'Item deleted successfully.');
	}

}
