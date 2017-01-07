<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\RecipeArticle;
use App\DishArticle;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
	public function create($dish_id)
	{
		return view('recipe_articles.create', compact('dish_id'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$articles = array();
		foreach ($request['content'] as $key => $value) {
            $articles[] = ['dish_id' => $request['id'], 'step' => ++$key, 'content' => $value, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
		}
		RecipeArticle::insert($articles);

		return redirect()->route('articles.index')->with('message', 'Recipe created successfully.');
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
	public function edit(Request $request, $dish_id)
	{
		$dish_article = DishArticle::findOrFail($dish_id);
		$article = $request->user()->articles()->findOrFail($dish_article->article_id);

		return view('recipe_articles.edit', compact('article'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $dish_id)
	{
		$steps = array();
		$recipes = RecipeArticle::where("dish_id", "=", $dish_id)->get();
		$recipes = $recipes->keyBy('id');
		foreach ($request['content'] as $key => $value) {
			$recipe = $recipes->get($request['recipe_id'.$key]);
			if ($recipe) {
				$recipe->step = ++$key;
				$recipe->content = $value;
				$recipe->save();
			} else {
				$steps[] = ['dish_id' => $dish_id, 'step' => ++$key, 'content' => $value, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
			}
		}
		RecipeArticle::insert($steps);
		return redirect()->route('articles.index')->with('message', 'Recipe updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($recipe_id)
	{
		$recipe_article = RecipeArticle::findOrFail($recipe_id);
		$recipe_article->delete();

		return redirect()->route('recipe_articles.index')->with('message', 'Recipe deleted successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($recipe_id)
	{
		$recipe_article = RecipeArticle::findOrFail($recipe_id);
		$recipe_article->delete();

		return $recipe_article;
	}
}
