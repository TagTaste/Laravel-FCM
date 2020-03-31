<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\ReportType;
use Carbon\Carbon;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReportTypeList(Request $request)
    {
        $this->errors['status'] = 0;
        $this->model = ReportType::where('is_active', 1)->get();
        return $this->sendResponse();
    }
}
