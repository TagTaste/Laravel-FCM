<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\IdeabookArticle;
use Illuminate\Http\Request;

class IdeabookArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ideabook_articles = IdeabookArticle::orderBy('id', 'desc')->paginate(10);

		return view('ideabook_articles.index', compact('ideabook_articles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, $articleId)
    {

		return view('ideabook_articles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $articleId)
	{
        $ideabook = $request->user()->getDefaultIdeabook();

        $ideabook->articles()->attach($articleId);


        return redirect()->back()->with('message', 'Article added to Ideabook.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ideabook_article = IdeabookArticle::findOrFail($id);

		return view('ideabook_articles.show', compact('ideabook_article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ideabook_article = IdeabookArticle::findOrFail($id);

		return view('ideabook_articles.edit', compact('ideabook_article'));
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
		$ideabook_article = IdeabookArticle::findOrFail($id);

		$ideabook_article->ideabook_id = $request->input("ideabook_id");
        $ideabook_article->article_id = $request->input("article_id");

		$ideabook_article->save();

		return redirect()->route('ideabook_articles.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ideabook_article = IdeabookArticle::findOrFail($id);
		$ideabook_article->delete();

		return redirect()->route('ideabook_articles.index')->with('message', 'Item deleted successfully.');
	}

}
