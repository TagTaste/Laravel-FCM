<?php

namespace App\Http\Controllers\Api\Profile;

use App\Collaborate;
use App\Events\NewFeedable;
use App\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Profile;
use App\Company;

class CollaborateController extends Controller
{
    /**
     * Variable to model
     *
     * @var collaborate
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $profileId)
    {

        $page = $request->input('page', 1);
        $take = 20;
        $skip = $page > 1 ? ($page * $take) - $take : 0;
        $collaborations = $this->model->where('profile_id', $profileId)->orderBy('created_at', 'desc')->skip($skip)
            ->take($take)->get();

        $profileId = $request->user()->profile->id;
        $this->model = [];
        foreach ($collaborations as $collaboration) {
            $this->model[] = ['collaboration' => $collaboration, 'meta' => $collaboration->getMetaFor($profileId)];
        }

//        if($request->has('categories')){
//            $categories = $request->input('categories');
//            $this->model = $this->model->whereHas('categories',function($query) use ($categories){
//                $query->whereIn('category_id',$categories);
//            });
//        }

        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $profileId)
    {
        $profileId = $request->user()->profile->id;
        $inputs = $request->all();
        $inputs['profile_id'] = $profileId;
        $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
        $fields = $request->has("fields") ? $request->input('fields') : [];

        //save images
        for ($i = 1; $i <= 5; $i++) {
            if (!$request->hasFile("image$i")) {
                break;
            }
            $imageName = str_random("32") . ".jpg";
            $relativePath = "images/p/$profileId/collaborate";
            $request->file("image$i")->storeAs($relativePath, $imageName);
            $inputs["image$i"] = $imageName;
        }
        if (!empty($fields)) {
            unset($inputs['fields']);
        }

        $this->model = $this->model->create($inputs);

//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
//		$this->model->syncFields($fields);

        $profile = Profile::find($profileId);
        $this->model = $this->model->fresh();
        event(new NewFeedable($this->model, $profile));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, $profileId, $id)
    {
        $collaboration = $this->model->where('profile_id', $profileId)->find($id);
        if ($collaboration === null) {
            throw new \Exception("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;
        $meta = $collaboration->getMetaFor($profileId);
        $this->model = ['collaboration' => $collaboration, 'meta' => $meta];

        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $inputs = $request->all();
        $profileId = $request->user()->profile->id;

        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->first();

        if ($collaborate === null) {
            throw new \Exception("Could not find the specified Collaborate project.");
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile("image$i")) {
                $imageName = str_random("32") . ".jpg";
                $relativePath = "images/p/$profileId/collaborate";
                $request->file("image$i")->storeAs($relativePath, $imageName);
                $inputs["image$i"] = $imageName;
            }
        }
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
        $this->model = $collaborate->update($inputs);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;

        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->first();

        if ($collaborate === null) {
            throw new \Exception("Could not find the specified Collaborate project.");
        }

        $this->model = $collaborate->delete();
        return $this->sendResponse();
    }

    public function approve(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->first();

        if ($collaborate === null) {
            throw new \Exception("Invalid Collaboration project.");
        }

        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $company = Company::find($companyId);
            if (!$company) {
                throw new \Exception("Company does not exist.");
            }

            return $collaborate->approveCompany($company);
        }

        if ($request->has('profile_id')) {
            $inputProfileId = $request->input('profile_id');
            $profile = Profile::find($inputProfileId);
            if (!$profile) {
                throw new \Exception("Profile does not exist.");
            }

            return $collaborate->approveProfile($profile);
        }
    }

    public function reject(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->first();

        if ($collaborate === null) {
            throw new \Exception("Invalid Collaboration project.");
        }

        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $company = Company::find($companyId);
            if (!$company) {
                throw new \Exception("Company does not exist.");
            }

            return $collaborate->rejectCompany($company);
        }

        if ($request->has('profile_id')) {
            $inputProfileId = $request->input('profile_id');
            $profile = Profile::find($inputProfileId);
            if (!$profile) {
                throw new \Exception("Profile does not exist.");
            }

            return $collaborate->rejectProfile($profile);
        }
    }
}