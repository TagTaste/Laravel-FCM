<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ideabook;
use Illuminate\Http\Request;

class IdeabookController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ideabooks = Ideabook::orderBy('id', 'desc')->paginate(10);

		return view('ideabooks.index', compact('ideabooks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('ideabooks.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$ideabook = new Ideabook();

		$ideabook->name = $request->input("name");
        $ideabook->description = $request->input("description");
        $ideabook->privacy_id = $request->input("privacy_id");
        $ideabook->user_id = $request->input("user_id");

		$ideabook->save();

		return redirect()->route('ideabooks.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ideabook = Ideabook::findOrFail($id);

		return view('ideabooks.show', compact('ideabook'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ideabook = Ideabook::findOrFail($id);

		return view('ideabooks.edit', compact('ideabook'));
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
		$ideabook = Ideabook::findOrFail($id);

		$ideabook->name = $request->input("name");
        $ideabook->description = $request->input("description");
        $ideabook->privacy_id = $request->input("privacy_id");
        $ideabook->user_id = $request->input("user_id");

		$ideabook->save();

		return redirect()->route('ideabooks.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ideabook = Ideabook::findOrFail($id);
		$ideabook->delete();

		return redirect()->route('ideabooks.index')->with('message', 'Item deleted successfully.');
	}

}
