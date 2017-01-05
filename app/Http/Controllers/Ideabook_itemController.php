<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ideabook_item;
use Illuminate\Http\Request;

class Ideabook_itemController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ideabook_items = Ideabook_item::orderBy('id', 'desc')->paginate(10);

		return view('ideabook_items.index', compact('ideabook_items'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('ideabook_items.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$ideabook_item = new Ideabook_item();

		$ideabook_item->ideabook_id = $request->input("ideabook_id");
        $ideabook_item->article_id = $request->input("article_id");

		$ideabook_item->save();

		return redirect()->route('ideabook_items.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ideabook_item = Ideabook_item::findOrFail($id);

		return view('ideabook_items.show', compact('ideabook_item'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ideabook_item = Ideabook_item::findOrFail($id);

		return view('ideabook_items.edit', compact('ideabook_item'));
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
		$ideabook_item = Ideabook_item::findOrFail($id);

		$ideabook_item->ideabook_id = $request->input("ideabook_id");
        $ideabook_item->article_id = $request->input("article_id");

		$ideabook_item->save();

		return redirect()->route('ideabook_items.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ideabook_item = Ideabook_item::findOrFail($id);
		$ideabook_item->delete();

		return redirect()->route('ideabook_items.index')->with('message', 'Item deleted successfully.');
	}

}
