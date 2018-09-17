<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Variable to model
     *
     * @var type
     */
    protected $model;
    
    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Type $model)
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
        $types = $this->model->paginate();
        
        return view('types.index', compact('types'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('types.create');
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
        
        return redirect()->route('types.index')->with('message', 'Occupation Type created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $type = $this->model->findOrFail($id);
        
        return view('types.show', compact('type'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $type = $this->model->findOrFail($id);
        
        return view('types.edit', compact('type'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        
        $type = $this->model->findOrFail($id);
        $type->update($inputs);
        
        return redirect()->route('types.index')->with('message', 'Occupation Type updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->model->destroy($id);
        
        return redirect()->route('types.index')->with('message', 'Occupation Type deleted successfully.');
    }
}