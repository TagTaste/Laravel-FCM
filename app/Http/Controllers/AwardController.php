<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Profile\Award;
use Illuminate\Http\Request;

class AwardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$awards = Award::orderBy('id', 'desc')->paginate(10);

		return view('awards.index', compact('awards'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('awards.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$award = new Award();

		$award->name = $request->input("name");
        $award->description = $request->input("description");
        $award->date = $request->input("date");
        $award->profile_id = $request->input("profile_id");

		$award->save();

		return redirect()->route('awards.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$award = Award::findOrFail($id);

		return view('awards.show', compact('award'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$award = Award::findOrFail($id);

		return view('awards.edit', compact('award'));
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
		$award = Award::findOrFail($id);

		$award->name = $request->input("name");
        $award->description = $request->input("description");
        $award->date = $request->input("date");
        $award->profile_id = $request->input("profile_id");

		$award->save();

		return redirect()->route('awards.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$award = Award::findOrFail($id);
		$award->delete();

		return redirect()->route('awards.index')->with('message', 'Item deleted successfully.');
	}

}
