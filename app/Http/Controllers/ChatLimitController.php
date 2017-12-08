<?php

namespace App\Http\Controllers;

use App\ChatLimit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatLimitController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var chat_limit
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(ChatLimit $model)
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
		$chat_limits = $this->model->paginate();

		return view('chat_limits.index', compact('chat_limits'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('chat_limits.create');
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

		return redirect()->route('chat_limits.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$chat_limit = $this->model->findOrFail($id);
		
		return view('chat_limits.show', compact('chat_limit'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$chat_limit = $this->model->findOrFail($id);
		
		return view('chat_limits.edit', compact('chat_limit'));
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

		$chat_limit = $this->model->findOrFail($id);		
		$chat_limit->update($inputs);

		return redirect()->route('chat_limits.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('chat_limits.index')->with('message', 'Item deleted successfully.');
	}
}