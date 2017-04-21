<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\DishArticle;
use Illuminate\Http\Request;

class DishArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$dish_articles = DishArticle::orderBy('id', 'desc')->paginate(10);

		return view('dish_articles.index', compact('dish_articles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('dish_articles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$dish_article = new DishArticle();

		$dish_article->showcase = $request->input("showcase");
        $dish_article->hasRecipe = $request->input("hasRecipe");
        $dish_article->article_id = $request->input("article_id");
        $dish_article->chef_id = $request->input("chef_id");

		$dish_article->save();

		return redirect()->route('dish_articles.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$dish_article = DishArticle::findOrFail($id);

		return view('dish_articles.show', compact('dish_article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$dish_article = DishArticle::findOrFail($id);

		return view('dish_articles.edit', compact('dish_article'));
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
		$dish_article = DishArticle::findOrFail($id);

		$dish_article->showcase = $request->input("showcase");
        $dish_article->hasRecipe = $request->input("hasRecipe");
        $dish_article->article_id = $request->input("article_id");
        $dish_article->chef_id = $request->input("chef_id");

		$dish_article->save();

		return redirect()->route('dish_articles.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$dish_article = DishArticle::findOrFail($id);
		$dish_article->delete();

		return redirect()->route('dish_articles.index')->with('message', 'Item deleted successfully.');
	}

	public function image($filename)
    {
        return response()->file(storage_path("app/" . DishArticle::$fileInputs['image'] . '/' . $filename));
	}
}
