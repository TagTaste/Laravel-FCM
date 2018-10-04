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

	public function uploadCuisine(Request $request)
    {
        $filename = str_random(32) . ".xlsx";
        $path = "images/collaborate/global/nested/option";
        $file = $request->file('file')->storeAs($path,$filename,['visibility'=>'public']);
        //$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;
        //$fullpath = \Storage::url($file);

        //load the file
        $data = [];
        try {
            $fullpath = $request->file->store('temp', 'local');
            \Excel::load("storage/app/" . $fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
            \Storage::disk('local')->delete($file);
        } catch (\Exception $e){
            \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());

        }
        $cuisines = [];
        foreach ($data as $item)
        {

            foreach ($item as $datum)
            {
                if(isset($datum['Country'])||isset($datum['Country'])||isset($datum['Cuisine'])||is_null($datum['Cuisine']))
                    break;
                $cuisines[] = ['is_active'=>1,'country'=>$datum['Country'],'name'=>$datum['Cuisine']];
            }
        }
        $this->model = \App\Cuisine::insert($cuisines);
        return $this->sendResponse();
    }

}
