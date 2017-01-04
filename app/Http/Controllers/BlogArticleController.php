<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\BlogArticle;
use Illuminate\Http\Request;

class BlogArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$blog_articles = BlogArticle::orderBy('id', 'desc')->paginate(10);

		return view('blog_articles.index', compact('blog_articles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('blog_articles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$blog_article = new BlogArticle();

		$blog_article->content = $request->input("content");
        $blog_article->image = $request->input("image");
        $blog_article->article_id = $request->input("article_id");

		$blog_article->save();

		return redirect()->route('blog_articles.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$blog_article = BlogArticle::findOrFail($id);

		return view('blog_articles.show', compact('blog_article'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$blog_article = BlogArticle::findOrFail($id);

		return view('blog_articles.edit', compact('blog_article'));
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
		$blog_article = BlogArticle::findOrFail($id);

		$blog_article->content = $request->input("content");
        $blog_article->image = $request->input("image");
        $blog_article->article_id = $request->input("article_id");

		$blog_article->save();

		return redirect()->route('blog_articles.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$blog_article = BlogArticle::findOrFail($id);
		$blog_article->delete();

		return redirect()->route('blog_articles.index')->with('message', 'Item deleted successfully.');
	}

    public function image($filename)
    {
        return response()->file(BlogArticle::$fileInputs['image'] . '/' . $filename);
	}

}
