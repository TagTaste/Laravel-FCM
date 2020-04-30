<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Tag;
use App\Events\Model\Subscriber\Create;
use App\FeedCard;
use App\Traits\CheckTags;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class FeedCardController extends Controller
{
    use CheckTags;
    /**
     * Variable to model
     *
     * @var shoutout
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(FeedCard $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //we never return all of the feed cards
        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //we never create a feed card
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $feed_card = $this->model->where('id',$id)->whereNull('deleted_at')->first();
        if(!$feed_card){
            return $this->sendError("Feed Card not found.");
        }

        $meta = $feed_card->getMetaFor();
        $this->model = [
            'feedCard'=>$feed_card,
            'meta'=>$meta,
            'type'=>"feedCard"
        ];

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

        //we never update a feed card
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        //we never delete a feed card
        return;
    }
}