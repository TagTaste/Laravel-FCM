<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$photos = Photo::orderBy('id', 'desc')->paginate(10);

		return view('photos.index', compact('photos'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('photos.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$photo = new Photo();

		$photo->caption = $request->input("caption");
        $photo->file = $request->input("file");
        $photo->album_id = $request->input("album_id");
        $photo->album_id = $request->input("album_id");

		$photo->save();

		return redirect()->route('photos.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$photo = Photo::findOrFail($id);

		return view('photos.show', compact('photo'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$photo = Photo::findOrFail($id);

		return view('photos.edit', compact('photo'));
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
		$photo = Photo::findOrFail($id);

		$photo->caption = $request->input("caption");
        $photo->file = $request->input("file");
        $photo->album_id = $request->input("album_id");
        $photo->album_id = $request->input("album_id");

		$photo->save();

		return redirect()->route('photos.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$photo = Photo::findOrFail($id);
		$photo->delete();

		return redirect()->route('photos.index')->with('message', 'Item deleted successfully.');
	}

}
