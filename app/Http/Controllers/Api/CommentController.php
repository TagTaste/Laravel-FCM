<?php namespace App\Http\Controllers\Api;

use App\Comment;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class CommentController extends Controller {
    
    private $models = [
        'photo' => \App\Photo::class,
        'tagboard' => \App\Ideabook::class
    ];
    
    private function getModel(&$model){
        return isset($this->models[$model]) ? new $this->models[$model] : false;
    }
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($model, $modelId)
	{
	    $model = $this->getModel($model);
	    if(!$model){
	        $this->errors[] = "Invalid model $model.";
	        return $this->sendResponse();
        }
        
        $model = $model->where('id',$modelId)->first();
	    
	    if(!$model){
	        $this->errors[] = "Model doesn't exist.";
	        return $this->sendResponse();
        }
        
        if(!method_exists($model, 'comments')){
	       $this->errors[] = "$model doesn't have comments.";
	       return $this->sendResponse();
        }
        
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
        $model = $this->getModel($model);
        if(!$model){
            $this->errors[] = "Invalid model $model.";
            return $this->sendResponse();
        }
        
        $model = $model->where('id',$modelId)->first();
        
        if(!$model){
            $this->errors[] = "Model doesn't exist.";
            return $this->sendResponse();
        }
        
        if(!method_exists($model, 'comments')){
            $this->errors[] = "$model doesn't have comments.";
            return $this->sendResponse();
        }
        
		$comment = new Comment();
		$comment->content = $request->input("content");
        $comment->user_id = $request->user()->id;
		$comment->save();

		$model->comments()->attach($comment->id);

        $this->model = $comment;
		return $this->sendResponse();
	}

}
