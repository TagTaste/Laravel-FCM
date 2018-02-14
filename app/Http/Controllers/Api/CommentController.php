<?php namespace App\Http\Controllers\Api;

use App\Comment;
use App\Events\Actions\Tag;
use App\Events\Update;
use App\Traits\CheckTags;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentController extends Controller {
    use CheckTags;
    
    private $models = [
        'photo' => \App\Photo::class,
        'tagboard' => \App\Ideabook::class,
        'collaborate'=> \App\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'shoutout' =>\App\Shoutout::class,
        'collaborate_share' => \App\Shareable\Collaborate::class,
        'photo_share' => \App\Shareable\Photo::class,
//        'job_share' => \App\Shareable\Job::class,
        'recipe_share' => \App\Shareable\Recipe::class,
        'shoutout_share' => \App\Shareable\Shoutout::class
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
        
        $this->model = $model->comments()->orderBy('created_at','desc')->paginate(10);
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
        $model = $this->getModel($model,$modelId);
        $this->checkRelationship($model);
        
        if(!method_exists($model, 'comments')){
            throw new \Exception("This model does not have comments defined.");
        }
        if($request->input("content")==null){
            return $this->sendError("Please write a comment.");
        }
        $content = htmlentities($request->input("content"), ENT_QUOTES, 'UTF-8', false);
        $comment = new Comment();
        $comment->content = $content;
        $comment->user_id = $request->user()->id;
        $comment->has_tags = $this->hasTags($content);
        $comment->save();
        
        $model->comments()->attach($comment->id);
        
        event(new \App\Events\Actions\Comment($model,$request->user()->profile, $comment->content));
        
        if($comment->has_tags){
            event(new Tag($model,$request->user()->profile,$comment->content, null, null, null, $comment));
        }
        $meta = $comment->getMetaFor($model);
        $this->model = ["comment"=>$comment,"meta"=>$meta];
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
