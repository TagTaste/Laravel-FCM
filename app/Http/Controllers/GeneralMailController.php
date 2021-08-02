<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TieReportMail;
use App\Mail\FoodCompositionMail;
use Illuminate\Support\Facades\Validator;
use \Tagtaste\Api\SendsJsonResponse;

class GeneralMailController extends Controller
{
    use SendsJsonResponse;

    public function paymentCallback(Request $request){
        $inputs = $request->all();
        $dataStr = json_encode($inputs);
        file_put_contents(storage_path("logs/") ."nikhil.txt", $dataStr, FILE_APPEND);
        file_put_contents(storage_path("logs/") ."nikhil.txt", "++++++++++++++++++++++\n\n", FILE_APPEND);        
        return "Success";
    }

    public function tieReportMail(Request $request)
    {
        $failed = $this->verifyEMail($request->email);
        if($failed) {
            return $this->sendError('Invalid Email Id Given');
        } else {
            $this->sendMail($request->email);
            $this->model = 1;
            return $this->sendResponse();
        }
    }

    protected function verifyEMail($email)
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);
        return $validator->fails();
    }

    protected function sendMail($email)
    {
        \Mail::to($email)->send(new TieReportMail());
    }

    public function foodCompositionMail(Request $request)
    {
        $failed = $this->verifyEMail($request->email);
        if($failed) {
            return $this->sendError('Invalid Email Id Given');
        } else {
            $this->sendfoodCompositionMail($request->email);
            $this->model = 1;
            return $this->sendResponse();
        }
    }

    protected function sendfoodCompositionMail($email)
    {
        \Mail::to($email)->send(new FoodCompositionMail());
    }
}
