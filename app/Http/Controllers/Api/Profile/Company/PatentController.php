<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Patent;
use App\CompanyUser;
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
        $inputs = $request->only(['title','description','number','issued_by','awarded_on','url']);
        $inputs['company_id'] = $companyId;

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
        $inputs = $request->only(['title','description','number','issued_by','awarded_on','url']);
        $inputs = array_filter($inputs);
        $this->model = Patent::where('id',$id)->update($inputs);

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
        $this->model = Patent::where('id',$id)->delete();

        return $this->sendResponse();
	}

}
