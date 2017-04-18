<?php

namespace App\Http\Controllers;

use App\Payload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayloadController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var payload
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Payload $model)
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
		$payloads = $this->model->paginate();

		return view('payloads.index', compact('payloads'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('payloads.create');
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

		return redirect()->route('payloads.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$payload = $this->model->findOrFail($id);
		
		return view('payloads.show', compact('payload'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$payload = $this->model->findOrFail($id);
		
		return view('payloads.edit', compact('payload'));
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

		$payload = $this->model->findOrFail($id);		
		$payload->update($inputs);

		return redirect()->route('payloads.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('payloads.index')->with('message', 'Item deleted successfully.');
	}
}