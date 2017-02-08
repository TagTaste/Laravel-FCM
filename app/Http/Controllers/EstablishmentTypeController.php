<?php namespace App\Http\Controllers;

use App\EstablishmentType;
use App\Http\Requests;
use Illuminate\Http\Request;

class EstablishmentTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$establishment_types = EstablishmentType::orderBy('id', 'desc')->paginate(10);

		return view('establishment_types.index', compact('establishment_types'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('establishment_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$establishment_type = new EstablishmentType();

		$establishment_type->name = $request->input("name");
        $establishment_type->description = $request->input("description");
        $establishment_type->public = $request->input("public");

		$establishment_type->save();

		return redirect()->route('establishment_types.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$establishment_type = EstablishmentType::findOrFail($id);

		return view('establishment_types.show', compact('establishment_type'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$establishment_type = EstablishmentType::findOrFail($id);

		return view('establishment_types.edit', compact('establishment_type'));
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
		$establishment_type = EstablishmentType::findOrFail($id);

		$establishment_type->name = $request->input("name");
        $establishment_type->description = $request->input("description");
        $establishment_type->public = $request->input("public");

		$establishment_type->save();

		return redirect()->route('establishment_types.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$establishment_type = EstablishmentType::findOrFail($id);
		$establishment_type->delete();

		return redirect()->route('establishment_types.index')->with('message', 'Item deleted successfully.');
	}

}
