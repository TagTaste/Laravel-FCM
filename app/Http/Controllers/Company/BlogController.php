<?php namespace App\Http\Controllers\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$company_blogs = Blog::orderBy('id', 'desc')->paginate(10);

		return view('company_blogs.index', compact('company_blogs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('company_blogs.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$company_blog = new Blog();

		$company_blog->name = $request->input("name");
        $company_blog->url = $request->input("url");
        $company_blog->company_id = $request->input("company_id");
        $company_blog->company_id = $request->input("company_id");

		$company_blog->save();

		return redirect()->route('company_blogs.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company_blog = Blog::findOrFail($id);

		return view('company_blogs.show', compact('company_blog'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$company_blog = Blog::findOrFail($id);

		return view('company_blogs.edit', compact('company_blog'));
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
		$company_blog = Blog::findOrFail($id);

		$company_blog->name = $request->input("name");
        $company_blog->url = $request->input("url");
        $company_blog->company_id = $request->input("company_id");
        $company_blog->company_id = $request->input("company_id");

		$company_blog->save();

		return redirect()->route('company_blogs.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$company_blog = Blog::findOrFail($id);
		$company_blog->delete();

		return redirect()->route('company_blogs.index')->with('message', 'Item deleted successfully.');
	}

}
