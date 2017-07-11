<?php namespace App\Http\Controllers\Api;

use App\Cuisine;
use App\Http\Requests;
use Illuminate\Http\Request;

class CuisineController extends Controller {

    /**
     * Variable to model
     *
     * @var category
     */
    protected $model;

    public function __construct(Cuisine $model)
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
        $this->model = Cuisine::orderBy('id', 'asc')->get();
        \Log::info("here".$this->model);
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
        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
	}

}
