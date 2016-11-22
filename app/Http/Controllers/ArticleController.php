<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Article;
use App\DishArticle;
use App\Privacy;
use App\Template;

use Illuminate\Http\Request;

class ArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$articles = Article::orderBy('id', 'desc')->paginate(10);

		return view('articles.index', compact('articles'));
	}

	/**
	 * Show the form for creating new artcile of type $type.
	 * 
	 * @param  String $type
	 * @return Response
	 */
	public function create($type)
	{
		$privacy = Privacy::getALl();
		$templates = Template::for(ucwords($type . " article"));
		return view('articles.create', compact('type','privacy', 'templates'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->input('article');
		$inputs['author_id'] = $request->user()->getChefProfileId();
		
		$article = Article::create($inputs);
		
		$type = $request->input("type");

		$typeInputs = $request->input($type);
		$typeInputs['article_id'] = $article->id;

		$class = "\App\\" . ucfirst($type) . "Article";

		$particularArticle = $class::create($typeInputs);

		return redirect()->route('articles.index')->with('message', 'Article created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$article = Article::findOrFail($id);

		return view('articles.show', compact('article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$article = Article::findOrFail($id);

		return view('articles.edit', compact('article'));
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
		$article = Article::findOrFail($id);

		$article->title = $request->input("title");
        $article->author_id = $request->input("author_id");
        $article->privacy_id = $request->input("privacy_id");
        $article->comments_enabled = $request->input("comments_enabled");
        $article->status = $request->input("status");
        $article->template_id = $request->input("template_id");

		$article->save();

		return redirect()->route('articles.index')->with('message', 'Article updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$article = Article::findOrFail($id);
		$article->delete();

		return redirect()->route('articles.index')->with('message', 'Article deleted successfully.');
	}

}
