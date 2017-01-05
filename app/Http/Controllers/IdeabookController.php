<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Ideabook;
use App\Privacy;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class IdeabookController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$ideabooks = $request->user()->ideabooks()->orderBy('id', 'desc')->paginate(10);



		return view('ideabooks.index', compact('ideabooks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $privacy = Privacy::getAll();
		return view('ideabooks.create', compact('privacy'));
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
        $ideabook->user_id = $request->user()->id;

		$ideabook->save();

		return redirect()->route('ideabooks.index')->with('message', 'Ideabook created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$ideabook = $request->user()->ideabooks()->findOrFail($id);

		return view('ideabooks.show', compact('ideabook'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$ideabook = $request->user()->ideabooks()->findOrFail($id);
        $privacy = Privacy::getAll();

		return view('ideabooks.edit', compact('ideabook','privacy'));
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
		$ideabook = $request->user()->ideabooks()->find($id);

		$ideabook->name = $request->input("name");
        $ideabook->description = $request->input("description");
        $ideabook->privacy_id = $request->input("privacy_id");

		$ideabook->save();

		return redirect()->route('ideabooks.index')->with('message', 'Ideabook updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$ideabook = $request->user()->ideabooks()->findOrFail($id);
		$ideabook->delete();

		return redirect()->route('ideabooks.index')->with('message', 'Ideabook deleted successfully.');
	}

}
