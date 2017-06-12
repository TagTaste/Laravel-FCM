<?php namespace App\Http\Controllers\Api;

use App\Comment;
use App\Events\Update;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends Controller {
    
    private $models = [
        'photo' => \App\Photo::class,
        'tagboard' => \App\Ideabook::class,
        'collaborate'=> \App\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'shoutout' =>\App\Shoutout::class
    ];
    
    private function getModel(&$modelName, &$modelId){
        $model = isset($this->models[$modelName]) ? new $this->models[$modelName] : false;
        
        if(!$model){
            throw new \Exception("Invalid model $modelName.");
        }
        
        return $this->fetchModel($model,$modelId);
    }
    
    private function fetchModel($model, $modelId)
    {
        $model = $model->find($modelId);
    
        if(!$model){
            throw new ModelNotFoundException("Could not find model with provided id.");
        }
        
        return $model;
    }
    
    private function checkRelationship(&$model){
        if(!method_exists($model, 'comments')){
            throw new \Exception("This model does not have comments defined.");
        }
    }
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($model, $modelId)
	{
	    $model = $this->getModel($model, $modelId);
        
        $this->checkRelationship($model);
        
        $this->model = $model->comments()->orderBy('created_at','asc')->paginate(10);
        return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $model, $modelId)
	{
	    $modelName=$model;
        $model = $this->getModel($model,$modelId);
        
        $this->checkRelationship($model);
        
        if(!method_exists($model, 'comments')){
            throw new \Exception("This model does not have comments defined.");
        }
        
		$comment = new Comment();
		$comment->content = $request->input("content");
        $comment->user_id = $request->user()->id;
		$comment->save();

		$model->comments()->attach($comment->id);
        $userId = $request->user()->id;
        $data = \DB::table('users')
                ->select("users.id","users.name")
                ->distinct('users.id')
                ->join('comments','comments.user_id','=','users.id')
                ->join('comments_shoutouts','comments.id','=','comments_shoutouts.comment_id')
                ->where('comments_shoutouts.shoutout_id','=',$model->id)
                ->where('comments.user_id','!=',$userId)
                ->get();

        \Log::info($data);

        $loggedInProfileId = $request->user()->profile->id;
        if($loggedInProfileId!=$model->profile_id){
                        $user = $model->profile->user;
                       $data->push(['id'=>$user->id,'name'=>$user->name]);
        }

        foreach ($data as $d){
            event(new Update($model->id,$modelName,$d['id'],"comments"));
        }

        $this->model = $comment;
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
            throw new \Exception('Comment does not belong to the user');
        }
        $this->model = $comment->delete();
        
        return $this->sendResponse();
    }

}
