<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profile\Book;
use Illuminate\Http\Request;

class ProfileBookController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$profile_books = Book::orderBy('id', 'desc')->paginate(10);

		return view('profile_books.index', compact('profile_books'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('profile_books.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$profile_book = new Book();

		$profile_book->title = $request->input("title");
        $profile_book->description = $request->input("description");
        $profile_book->publisher = $request->input("publisher");
        $profile_book->release_date = $request->input("release_date");
        $profile_book->url = $request->input("url");
        $profile_book->isbn = $request->input("isbn");
        $profile_book->profile_id = $request->user()->profile->id;

		$profile_book->save();

		return redirect()->route('profile_books.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$profile_book = Book::findOrFail($id);

		return view('profile_books.show', compact('profile_book'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$profile_book = Book::findOrFail($id);

		return view('profile_books.edit', compact('profile_book'));
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
		$profile_book = Book::findOrFail($id);

		$profile_book->title = $request->input("title");
        $profile_book->description = $request->input("description");
        $profile_book->publisher = $request->input("publisher");
        $profile_book->release_date = $request->input("release_date");
        $profile_book->url = $request->input("url");
        $profile_book->isbn = $request->input("isbn");
        $profile_book->profile_id = $request->user()->profile->id;

		$profile_book->save();

		return redirect()->route('profile_books.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$profile_book = Book::findOrFail($id);
		$profile_book->delete();

		return redirect()->route('profile_books.index')->with('message', 'Item deleted successfully.');
	}

}
