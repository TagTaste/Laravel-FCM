<?php

namespace App\Http\Controllers;

use App\ModelSubscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModelSubscriberController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var model_subscriber
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(ModelSubscriber $model)
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
		$model_subscribers = $this->model->paginate();

		return view('model_subscribers.index', compact('model_subscribers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('model_subscribers.create');
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

		return redirect()->route('model_subscribers.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$model_subscriber = $this->model->findOrFail($id);
		
		return view('model_subscribers.show', compact('model_subscriber'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$model_subscriber = $this->model->findOrFail($id);
		
		return view('model_subscribers.edit', compact('model_subscriber'));
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

		$model_subscriber = $this->model->findOrFail($id);		
		$model_subscriber->update($inputs);

		return redirect()->route('model_subscribers.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('model_subscribers.index')->with('message', 'Item deleted successfully.');
	}
}