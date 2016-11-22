<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Article;
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($type)
	{

		return view('articles.create', compact('type'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$article = new Article();

		$article->title = $request->input("title");
        $article->author_id = $request->input("author_id");
        $article->privacy_id = $request->input("privacy_id");
        $article->comments_enabled = $request->input("comments_enabled");
        $article->status = $request->input("status");
        $article->template_id = $request->input("template_id");

		$article->save();

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
		$article = Article::findOrFail($id);
		$article->delete();

		return redirect()->route('articles.index')->with('message', 'Item deleted successfully.');
	}

}
