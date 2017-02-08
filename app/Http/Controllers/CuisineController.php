<?php namespace App\Http\Controllers;

use App\Cuisine;
use App\Http\Requests;
use Illuminate\Http\Request;

class CuisineController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$cuisines = Cuisine::orderBy('id', 'desc')->paginate(10);

		return view('cuisines.index', compact('cuisines'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('cuisines.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$cuisine = new Cuisine();

		$cuisine->name = $request->input("name");
        $cuisine->public = $request->input("public");
        $cuisine->count = $request->input("count");

		$cuisine->save();

		return redirect()->route('cuisines.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$cuisine = Cuisine::findOrFail($id);

		return view('cuisines.show', compact('cuisine'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$cuisine = Cuisine::findOrFail($id);

		return view('cuisines.edit', compact('cuisine'));
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
		$cuisine = Cuisine::findOrFail($id);

		$cuisine->name = $request->input("name");
        $cuisine->public = $request->input("public");
        $cuisine->count = $request->input("count");

		$cuisine->save();

		return redirect()->route('cuisines.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$cuisine = Cuisine::findOrFail($id);
		$cuisine->delete();

		return redirect()->route('cuisines.index')->with('message', 'Item deleted successfully.');
	}

}
