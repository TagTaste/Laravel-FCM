<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\Advertisements;
use Carbon\Carbon;


class AdvertisementController extends Controller
{
    
    /**
     * Variable to model
     *
     * @var field
     */
    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Advertisements $model)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logged_in_profile_id = $request->user()->profile->id;
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page, 10);

        $this->model = Advertisements::where('expired_at', '>' ,$this->now)
            ->inRandomOrder()
            ->skip($skip)
            ->take($take)
            ->get();

        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd("test create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $profile = $request->user()->profile;
        $data['profile_id'] = (int) $profile->id;
        if (!$request->has('image') || is_null($request->input('image'))) {
            return $this->sendError("wrong format");
        }
        
        if ($request->has('company_id') && !is_null($request->input('company_id'))) {
            $data['company_id'] = (int) $request->input('company_id');
        } else {
            $data['company_id'] = null;
        }

        $data['image'] = $this->changeInJson($request->input('image'));
        $data['title'] = $request->input('title');
        $data['description'] = $request->input('description');
        $data['created_at'] = $this->now;
        $data['updated_at'] = $this->now;
        if ($request->has('expired_at') && !is_null($request->input('expired_at'))) {
            $input = $request->input('expired_at'); 
            $date = date('Y-m-d h:i:s', strtotime($input)); 
            $data['expired_at'] = $date;
            $data['is_expired'] = False;
        }

        if ($request->has('link') && !is_null($request->input('link'))) {
            $data['link'] = $request->input('link');
        }
        $this->model = Advertisements::create($data);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("test show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd("test edit");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeInJson($images)
    {
        $data = [];
        $image = json_decode($images);
        $data[] = ['original_photo'=>$image->original_photo,'tiny_photo'=>$image->tiny_photo,'meta'=>$image->meta];
        return json_encode($data);
    }
}
