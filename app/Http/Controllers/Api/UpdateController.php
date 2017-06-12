<?php

namespace App\Http\Controllers\Api;

use App\Update;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var update
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Update $model)
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
		$updates = $this->model->paginate();

		return view('updates.index', compact('updates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('updates.create');
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
		$this->model->create($inputs);

		return redirect()->route('updates.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId)
	{
        $data = Update::where('profile_id',$profileId)->get();
        $data['notification_count']=Update::where('profile_id',$profileId)->count();
        $this->model=$data;
		return $this->sendResponse();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$update = $this->model->findOrFail($id);
		
		return view('updates.edit', compact('update'));
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

		$update = $this->model->findOrFail($id);		
		$update->update($inputs);

		return redirect()->route('updates.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model->destroy($id);

		return redirect()->route('updates.index')->with('message', 'Item deleted successfully.');
	}

	public function isRead(Request $request, $modelName,$modelId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $match=['model_name'=>$modelName,'model_id'=>$modelId,'profile_id'=>$loggedInProfileId];
        $this->model = Update::where($match)->update(['is_read'=>1]);

        return $this->sendResponse();
    }
}