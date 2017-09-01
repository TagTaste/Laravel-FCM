<?php

namespace App\Http\Controllers\Api;

use App\Events\SendFeedback;
use App\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Variable to model
     *
     * @var field
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Feedback $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->model = $this->model->where('profile_id',$request->user()->profile->id);
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['profile_id'] = $request->user()->profile->id;
        $this->model = $this->model->create($inputs);
        event(new SendFeedback($this->model));
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
        $inputs = $request->all();

        $feedback = $this->model->findOrFail($id);
        $inputs['profile_id'] = $request->user()->profile->id;
        $this->model = $feedback->update($inputs);

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
        $this->model = $this->model->destroy($id);
        return $this->sendResponse();
    }
}