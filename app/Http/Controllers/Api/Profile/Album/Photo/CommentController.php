<?php namespace App\Http\Controllers\Api\Profile\Album\Photo;

use App\Comment;
use App\Http\Api\Response;
use App\Http\Requests;
use App\Photo;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller {

    use SendsJsonResponse;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$comments = Comment::orderBy('created_at', 'desc')->paginate(10);

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
	public function store(Request $request, $profileId, $albumId, $photoId)
	{
		$comment = new Comment();

		$comment->content = $request->input("content");
        $comment->user_id = $request->user()->id;
		$comment->save();

		$photo = Photo::find($photoId);
		$photo->comments()->attach($comment->id);

        $this->model = $comment;
		return $this->sendResponse();
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
