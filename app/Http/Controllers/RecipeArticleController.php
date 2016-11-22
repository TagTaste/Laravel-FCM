<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RecipeArticle;
use Illuminate\Http\Request;

class RecipeArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$recipe_articles = RecipeArticle::orderBy('id', 'desc')->paginate(10);

		return view('recipe_articles.index', compact('recipe_articles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('recipe_articles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$recipe_article = new RecipeArticle();

		$recipe_article->dish_id = $request->input("dish_id");
        $recipe_article->step = $request->input("step");
        $recipe_article->content = $request->input("content");
        $recipe_article->template_id = $request->input("template_id");
        $recipe_article->parent_id = $request->input("parent_id");

		$recipe_article->save();

		return redirect()->route('recipe_articles.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$recipe_article = RecipeArticle::findOrFail($id);

		return view('recipe_articles.show', compact('recipe_article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$recipe_article = RecipeArticle::findOrFail($id);

		return view('recipe_articles.edit', compact('recipe_article'));
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
		$recipe_article = RecipeArticle::findOrFail($id);

		$recipe_article->dish_id = $request->input("dish_id");
        $recipe_article->step = $request->input("step");
        $recipe_article->content = $request->input("content");
        $recipe_article->template_id = $request->input("template_id");
        $recipe_article->parent_id = $request->input("parent_id");

		$recipe_article->save();

		return redirect()->route('recipe_articles.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$recipe_article = RecipeArticle::findOrFail($id);
		$recipe_article->delete();

		return redirect()->route('recipe_articles.index')->with('message', 'Item deleted successfully.');
	}

}
