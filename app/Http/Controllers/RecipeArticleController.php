<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RecipeArticle;
use App\DishArticle;
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
	public function create($id)
	{
		return view('recipe_articles.create', compact('id'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		foreach ($request['content'] as $key => $value) {
			$receipe = RecipeArticle::create([
                'dish_id' => $request['id'],
                'step' => ++$key,
                'content' => $value,
            ]);
		}
		return redirect()->route('articles.index')->with('message', 'Item created successfully.');
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
		$dish_article = DishArticle::findOrFail($id);

		return view('recipe_articles.edit', compact('dish_article'));
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
		foreach ($request['content'] as $key => $value) {
			$receipe = RecipeArticle::where("dish_id", "=", $id)->where("id", "=", $request['receipe_id'.$key])->first();
			if(count($receipe) > 0) {
				$receipe->step = ++$key;
				$receipe->content = $value;
				$receipe->save();
			} else {
				$receipe = RecipeArticle::create([
	                'dish_id' => $id,
	                'step' => ++$key,
	                'content' => $value,
	            ]);
			}
		}
		return redirect()->route('articles.index')->with('message', 'Item updated successfully.');
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$recipe_article = RecipeArticle::findOrFail($id);
		$recipe_article->delete();

		return 0;
	}
}
