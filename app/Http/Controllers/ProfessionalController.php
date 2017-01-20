<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Professional;
use Illuminate\Http\Request;

class ProfessionalController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$professionals = Professional::orderBy('id', 'desc')->paginate(10);

		return view('professionals.index', compact('professionals'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('professionals.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$professional = new Professional();

		$professional->ingredients = $request->input("ingredients");
        $professional->favourite_moments = $request->input("favourite_moments");
        $professional->profile_id = $request->input("profile_id");

		$professional->save();

		return redirect()->route('professionals.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$professional = Professional::findOrFail($id);

		return view('professionals.show', compact('professional'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$professional = Professional::findOrFail($id);

		return view('professionals.edit', compact('professional'));
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
		$professional = Professional::findOrFail($id);

		$professional->ingredients = $request->input("ingredients");
        $professional->favourite_moments = $request->input("favourite_moments");
        $professional->profile_id = $request->input("profile_id");

		$professional->save();

		return redirect()->route('professionals.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$professional = Professional::findOrFail($id);
		$professional->delete();

		return redirect()->route('professionals.index')->with('message', 'Item deleted successfully.');
	}

}
