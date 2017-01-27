<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profile\Show;
use Illuminate\Http\Request;

class ProfileShowController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profile_shows = Show::orderBy('id', 'desc')->paginate(10);

		return view('profile_shows.index', compact('profile_shows'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profile_shows.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$profile_show = new Show();

		$profile_show->title = $request->input("title");
        $profile_show->description = $request->input("description");
        $profile_show->channel = $request->input("channel");
        $profile_show->current = $request->input("current");
        $profile_show->start_date = $request->input("start_date");
        $profile_show->end_date = $request->input("end_date");
        $profile_show->url = $request->input("url");
        $profile_show->appeared_as = $request->input("appeared_as");
        $profile_show->profile_id = $request->user()->profile->id;

		$profile_show->save();

		return redirect()->route('profile_shows.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile_show = Show::findOrFail($id);

		return view('profile_shows.show', compact('profile_show'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile_show = Show::findOrFail($id);

		return view('profile_shows.edit', compact('profile_show'));
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
		$profile_show = Show::findOrFail($id);

		$profile_show->title = $request->input("title");
        $profile_show->description = $request->input("description");
        $profile_show->channel = $request->input("channel");
        $profile_show->current = $request->input("current");
        $profile_show->start_date = $request->input("start_date");
        $profile_show->end_date = $request->input("end_date");
        $profile_show->url = $request->input("url");
        $profile_show->appeared_as = $request->input("appeared_as");
        $profile_show->profile_id = $request->input("profile_id");

		$profile_show->save();

		return redirect()->route('profile_shows.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile_show = Show::findOrFail($id);
		$profile_show->delete();

		return redirect()->route('profile_shows.index')->with('message', 'Item deleted successfully.');
	}

}
