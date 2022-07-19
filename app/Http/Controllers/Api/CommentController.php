<?php namespace App\Http\Controllers\Api;

use App\Comment;
use App\CompanyUser;
use App\Events\Actions\Tag;
use App\Events\Update;
use App\Polling;
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
        'surveys' =>\App\Surveys::class,

        'collaborate_share' => \App\Shareable\Collaborate::class,
        'photo_share' => \App\Shareable\Photo::class,
//        'job_share' => \App\Shareable\Occupation::class,
        'recipe_share' => \App\Shareable\Recipe::class,
        'shoutout_share' => \App\Shareable\Shoutout::class,
        'polling' => Polling::class,
        'polling_share' => \App\Shareable\Polling::class,
        'product_share' => \App\Shareable\Product::class,
        'surveys_share' => \App\Shareable\Surveys::class
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
        
            $model = $model->where('id',$modelId)->first();
    
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
	public function index(Request $request, $model, $modelId)
	{
        $this->model = [];
        $model = $this->getModel($model, $modelId);
        
        $this->checkRelationship($model);
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $this->model['data'] = $model->comments()->orderBy('created_at','desc')->skip(($page - 1) * 10)->take(10)->get();
        $this->model['next_page'] = $page > 1 ? $page - 1 : null;
        $this->model['count'] = $model->comments()->count();
        $this->model['previous_page'] = count($this->model['data']) >= 10 && $page*10 < $this->model['count']  ? $page + 1 : null;
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
        $models = $model;
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
        //known issue for surveys (notification model_id datatype is wrong in modoel subscriber so need to fix it )
        if($models!="surveys"){ //stopped for surveys fir now
        event(new \App\Events\Actions\Comment($model,$request->user()->profile, $comment->content, null, null, null, $comment));
        
            if ($comment->has_tags) {
                event(new Tag($model,$request->user()->profile,$comment->content, null, null, null, $comment));
            }
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

    public function update(Request $request, $id, $modelName, $modelId)
    {
        $userId = $request->user()->id;
        $comment = Comment::where('user_id',$userId)->find($id);
        if($comment === null){
            return $this->sendError("Comment does not belong to the user.");
        }
        $model = $this->getModel($modelName,$modelId);

        $content = htmlentities($request->input("content"), ENT_QUOTES, 'UTF-8', false);
        $comment->content = $content;
        $comment->user_id = $request->user()->id;
        $comment->has_tags = $this->hasTags($content);
        $comment->save();

        //$model->comments()->attach($comment->id);

        if ($comment->has_tags) {
            event(
                new Tag(
                    $model,
                    $request->user()->profile,
                    $comment->content,
                    null,
                    null,
                    null,
                    $comment
                )
            );
        }
        $meta = $comment->getMetaFor($model);
        $this->model = [
            "comment"=>$comment,
            "meta"=>$meta
        ];
        return $this->sendResponse();
    }

    public function commentDelete(Request $request, $id, $modelName, $modelId)
    {
        $userId = $request->user()->id;
        $comment = Comment::where('user_id',$userId)->find($id);

        if($comment === null){
            $model = $this->getModel($modelName,$modelId);
//            \Log::info($model);
            if(isset($model->company_id)&&!empty($model->company_id))
            {
                $checkAdmin = CompanyUser::where("company_id",$model->company_id)->where('profile_id', $request->user()->profile->id)->exists();
                if (!$checkAdmin) {
                    return $this->sendError("Comment does not belong to the user.");
                }
            }
            else if($request->user()->profile->id != $model->profile_id)
            {
                return $this->sendError("Comment does not belong to the user.");
            }
            $comment = Comment::find($id);
            if(!$comment)
            {
                return $this->sendError("Not found.");
            }
//            throw new \Exception('Comment does not belong to the user');
        }
        $this->model = $comment->delete();

        return $this->sendResponse();
    }

    public function notificationComment(Request $request, $id, $modelName, $modelId)
    {
        $this->model = [];
        $model = $this->getModel($modelName, $modelId);

        $this->checkRelationship($model);

        $previousPage = intval($model->comments()->where('id','>',$id)->count()/10);
        //paginate
        $page = $previousPage + 1;
        $this->model['data'] = $model->comments()->orderBy('created_at','desc')->whereNull('deleted_at')
            ->skip($previousPage*10)->take(10)->get();
        $nextPage = intval($model->comments()->where('id','<',$id)->count()/10) +1;
        $this->model['previous_page'] = $nextPage == $previousPage || count($this->model['data']) < 10 ? null : $previousPage + 2;
        $this->model['next_page'] = $previousPage == 0 ? null : $previousPage;
        return $this->sendResponse();
    }
    
    public function tagging(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $query = $request->input('term');

        $this->model = \App\Recipe\Profile::select('profiles.*')->join('users','profiles.user_id','=','users.id')
            ->where('profiles.id','!=',$loggedInProfileId)->where('account_deactivated', false)->where('users.name','like',"%$query%")->take(15)->get();
        return $this->sendResponse();
    }

}
