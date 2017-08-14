<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Patent;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

/**
 * Class PatentController
 * @package App\Http\Controllers\Api\Profile\Company
 */
class PatentController extends Controller {
    
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $profileId, $companyId)
	{
		$this->model = Patent::where('company_id',$companyId)->paginate(10);
        return $this->sendResponse();
	}
    
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, $profileId, $companyId)
	{
	    $company = \App\Company::where('user_id',$request->user()->id)->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("This user does not own this company.");
        }
        $inputs = $request->only(['title','description','number','issued_by','awarded_on','url']);
        $inputs['company_id'] = $company->id;

        $this->model = Patent::create(array_filter($inputs));
        return $this->sendResponse();
    }
    
    
    /**
     * @param $profileId
     * @param $companyId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function show($profileId, $companyId, $id)
	{
		$this->model = Patent::where('id',$id)->where('company_id',$companyId)->first();
		return $this->sendResponse();
    }
    
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $companyId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $userId = $request->user()->id;
        $inputs = $request->only(['title','description','number','issued_by','awarded_on','url']);
        $inputs = array_filter($inputs);
        if(isset($input['awarded_on'])){
            $input['awarded_on'] = "01-".$input['awarded_on'];
            $input['awarded_on'] = date('Y-m-d',strtotime($input['awarded_on']));
        }
        $this->model = Patent::whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->where('id',$id)->where('company_id',$companyId)->update($inputs);

        return $this->sendResponse();
	}
    
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $companyId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
	{
        $userId = $request->user()->id;

        $this->model = Patent::whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->where('id',$id)->where('company_id',$companyId)->delete();

        return $this->sendResponse();
	}

}
