<?php namespace App\Http\Controllers\Api\Profile\Photo;

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
	public function index($profileId, $photoId)
	{
		$this->model = Comment::whereHas('photo',function($query) use ($photoId){
		    $query->where('photo_id',$photoId);
        })->orderBy('created_at', 'desc')->paginate(10);
		
		return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $photoId)
	{
		$comment = new Comment();

		$comment->content = $request->input("content");
        $comment->user_id = $request->user()->id;
		$comment->save();

		$photo = Photo::find($photoId);
		$photo->comments()->attach($comment->id);

        $this->model = $comment;

        $photoProfile=\DB::table("profile_photos")->select('profile_id')->where('photo_id',$photoId)->pluck('profile_id');
        if($photoProfile[0]!=$profileId) {
            event(new Update($photoId, 'Photo', $photoProfile[0],
                $request->user()->name . "comment on your post"));
        }

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
        $userId = $request->user()->id;
        $comment = Comment::where('user_id',$userId)->find($id);
        if($comment === null){
            throw new \Exception("Comment does not belong to the user.");
        }
        
		$comment->content = $request->input("content");
		$this->model = $comment->save();

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
        $userId = $request->user()->id;
        $comment = Comment::where('user_id',$userId)->find($id);
        if($comment === null){
            throw new \Exception("Comment does not belong to the user.");
        }
		$this->model = $comment->delete();

		return $this->sendResponse();
	}

}
