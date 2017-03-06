<?php namespace App\Http\Controllers\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company\Address;
use Illuminate\Http\Request;

class AddressController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$addresss = Address::orderBy('id', 'desc')->paginate(10);

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
		$address = new Address();

		$address->address = $request->input("address");
        $address->country = $request->input("country");
        $address->phone = $request->input("phone");
        $address->company_id = $request->input("company_id");

		$address->save();

		return redirect()->route('company_locations.index')->with('message', 'Address created.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$address = Address::findOrFail($id);

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
		$address = Address::findOrFail($id);

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
		$address = Address::findOrFail($id);

		$address->address = $request->input("address");
        $address->country = $request->input("country");
        $address->phone = $request->input("phone");
        $address->company_id = $request->input("company_id");

		$address->save();

		return redirect()->route('company_locations.index')->with('message', 'Address updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$address = Address::findOrFail($id);
		$address->delete();

		return redirect()->route('company_locations.index')->with('message', 'Address deleted.');
	}

}
