<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\ProfileType;
use Illuminate\Http\Request;

class ProfileTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profile_types = ProfileType::orderBy('id', 'desc')->paginate(10);

		return view('profile_types.index', compact('profile_types'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profile_types.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$profile_type = new ProfileType();

		$profile_type->type = $request->input("type");
        $profile_type->enabled = $request->input("enabled");
        $profile_type->default = $request->input("default");

		$profile_type->save();

		return redirect()->route('profile_types.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile_type = ProfileType::findOrFail($id);

		return view('profile_types.show', compact('profile_type'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile_type = ProfileType::findOrFail($id);

		return view('profile_types.edit', compact('profile_type'));
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
		$profile_type = ProfileType::findOrFail($id);

		$profile_type->type = $request->input("type");
        $profile_type->enabled = $request->input("enabled");
        $profile_type->default = $request->input("default");

		$profile_type->save();

		return redirect()->route('profile_types.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile_type = ProfileType::findOrFail($id);
		$profile_type->delete();

		return redirect()->route('profile_types.index')->with('message', 'Item deleted successfully.');
	}

}
