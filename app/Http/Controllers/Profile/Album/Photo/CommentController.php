<?php namespace App\Http\Controllers\Profile\Album\Photo;

use App\Comment;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class CommentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$comments = Comment::orderBy('id', 'desc')->paginate(10);

		return view('comments.index', compact('comments'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('comments.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$comment = new Comment();

		$comment->content = $request->input("content");
        $comment->user_id = $request->input("user_id");
        $comment->flag = $request->input("flag");

		$comment->save();

		return redirect()->route('comments.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$comment = Comment::findOrFail($id);

		return view('comments.show', compact('comment'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comment = Comment::findOrFail($id);

		return view('comments.edit', compact('comment'));
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
		$comment = Comment::findOrFail($id);

		$comment->content = $request->input("content");
        $comment->user_id = $request->input("user_id");
        $comment->user_id = $request->input("user_id");
        $comment->flag = $request->input("flag");

		$comment->save();

		return redirect()->route('comments.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::findOrFail($id);
		$comment->delete();

		return redirect()->route('comments.index')->with('message', 'Item deleted successfully.');
	}

}
