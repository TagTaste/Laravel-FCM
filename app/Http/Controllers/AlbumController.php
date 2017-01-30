<?php namespace App\Http\Controllers;

use App\Album;
use App\Http\Requests;
use Illuminate\Http\Request;

class AlbumController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$albums = Album::orderBy('id', 'desc')->paginate(10);
		$tagboard = [0=>'Tag to Board'] + $request->user()->ideabooks->pluck('name','id')->toArray();
		return view('albums.index', compact('albums','tagboard'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('albums.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$album = new Album();

		$album->name = $request->input("name");
        $album->description = $request->input("description");
        $album->profile_id = $request->user()->profile->id;

		$album->save();

		return redirect()->route('albums.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$album = Album::with('photos')->findOrFail($id);
        $tagboard = [0=>'Tag to Board'] + $request->user()->ideabooks->pluck('name','id')->toArray();

		return view('albums.show', compact('album','tagboard'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$album = Album::findOrFail($id);

		return view('albums.edit', compact('album'));
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
		$album = Album::findOrFail($id);

		$album->name = $request->input("name");
        $album->description = $request->input("description");
        $album->profile_id = $request->user()->profile->id;

		$album->save();

		return redirect()->route('albums.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$album = Album::findOrFail($id);
		$album->delete();

		return redirect()->route('albums.index')->with('message', 'Item deleted successfully.');
	}

    public function tag(Request $request)
    {
        $albumId = $request->input('album_id');
        $tagBookId = $request->input('tagbook_id');

        $user = $request->user();
        $album = $user->profile->albums->find($albumId);
        if(!$album){
            throw new \Exception("The requested Album does not belong to the user.");
        }

        $tagbook = $user->ideabooks->find($tagBookId);

        if(!$tagbook){
            throw new \Exception("The requested Tag Book does not belong to the user.");
        }

        $tagbook->albums()->attach($albumId);
        return response()->json(['message'=>"Done."]);
	}

}
