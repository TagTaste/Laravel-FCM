<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Privacy;
use Illuminate\Http\Request;

class PrivacyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$privacies = Privacy::orderBy('id', 'desc')->paginate(10);

		return view('privacies.index', compact('privacies'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('privacies.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$privacy = new Privacy();

		$privacy->name = $request->input("name");

		$privacy->save();

		return redirect()->route('privacies.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$privacy = Privacy::findOrFail($id);

		return view('privacies.show', compact('privacy'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$privacy = Privacy::findOrFail($id);

		return view('privacies.edit', compact('privacy'));
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
		$privacy = Privacy::findOrFail($id);

		$privacy->name = $request->input("name");

		$privacy->save();

		return redirect()->route('privacies.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$privacy = Privacy::findOrFail($id);
		$privacy->delete();

		return redirect()->route('privacies.index')->with('message', 'Item deleted successfully.');
	}

}
