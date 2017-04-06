<?php namespace App\Http\Controllers\Api\Profile\Album\Photo;

use App\Comment;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class CommentController extends Controller {
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId, $albumId, $photoId)
	{
		$this->model = Comment::whereHas('photo',function($query) use ($photoId){
		    $query->where('photo_id',$photoId);
        })->orderBy('created_at', 'desc')->paginate(10);
		
		return $this->sendResponse();
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
		$this->model = Comment::findOrFail($id);
        return $this->sendResponse();
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
//        $comment->user_id = $request->input("user_id");
//        $comment->flag = $request->input("flag");

		$this->model = $comment->save();

		return $this->sendResponse();
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
		$this->model = $comment->delete();

		return $this->sendResponse();
	}

}
