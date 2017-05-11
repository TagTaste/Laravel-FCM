<?php

namespace App\Http\Controllers;

use App\Shoutout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoutoutController extends Controller
{
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
	public function __construct(Shoutout $model)
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
		$shoutouts = $this->model->paginate();

		return view('shoutouts.index', compact('shoutouts'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('shoutouts.create');
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

		return redirect()->route('shoutouts.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$shoutout = $this->model->findOrFail($id);
		
		return view('shoutouts.show', compact('shoutout'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$shoutout = $this->model->findOrFail($id);
		
		return view('shoutouts.edit', compact('shoutout'));
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

		$shoutout = $this->model->findOrFail($id);		
		$shoutout->update($inputs);

		return redirect()->route('shoutouts.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('shoutouts.index')->with('message', 'Item deleted successfully.');
	}
}