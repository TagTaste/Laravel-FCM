<?php

namespace App\Http\Controllers\Api\QuestionnairePreview;

use App\Collaborate;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Questions;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;

use App\QuestionnaireLists;
use App\QuestionnaireHeaders;
use App\QuestionnaireHeaderHelpers;
use App\QuestionnaireQuestions;
use App\QuestionnaireQuestionHelpers;
use App\QuestionnaireQuestionOptions;
use App\QuestionnairePreviewShareUsers;
use App\TempTokens;

use App\Deeplink;
use App\Mail\QuestionnairePreviewShareMail;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;

class QuestionnairePreviewController extends Controller
{

    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Questions $model)
    {
        $this->model = $model;
        $this->now = Carbon::now()->toDateTimeString();
    }

    public function questionnaireDetail(Request $request, $id)
    {
        $questionnaire = QuestionnaireLists::select('id','title','description')->where('id',$id)->first();
        if(is_null($questionnaire)){
            return $this->sendNewError("This questionnaire doesn't exist.");
        }

        $questionnaire['pop_up_title'] = 'Amazing to meet you!';
        $questionnaire['pop_up_description'] = 'This is a preview of the questionnaire. No interactions are recorded. You can comment and share your feedback for improving the questionnaire. The questionnaire creator will get a notification of your suggestions and update it accordingly.';
        
        $this->model = $questionnaire;
        return $this->sendNewResponse();
    }

    public function headers(Request $request, $id)
    {
        $questionnaire = QuestionnaireLists::select('id','title','description')->where('id',$id)->first();
        if(is_null($questionnaire)){
            return $this->sendNewError("This questionnaire doesn't exist.");
        }

        $headers = QuestionnaireHeaders::select('id','title as header_type','header_type_id as header_selection_type')
        ->where('questionnaire_id',$id)
        ->where('is_active',1)
        ->whereNull('deleted_at')
        ->orderBy('pos', 'asc')
        ->get();

        foreach($headers as $header){
            $headerHelper = QuestionnaireHeaderHelpers::select('assets_order','images','title as text','video_link','videos_meta')
            ->where('header_id', $header->id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')->first();

            if(!is_null($headerHelper)){
                $headerHelper['assets_order'] = json_decode($headerHelper->assets_order);
                $headerHelper['images'] = json_decode($headerHelper->images);
                $headerHelper['videos_meta'] = json_decode($headerHelper->videos_meta);
            }
            $header->header_info = $headerHelper;
        }

        $this->model = $headers;
        return $this->sendNewResponse();
    }

    public function reviewQuestions(Request $request, $id, $headerId)
    {
        $questionnaire = QuestionnaireLists::where('id',$id)->first();
        if(is_null($questionnaire)){
            return $this->sendNewError("This questionnaire doesn't exist.");
        }
        
        $header = QuestionnaireHeaders::where('questionnaire_id',$id)
        ->where('id',$headerId)
        ->where('is_active',1)
        ->whereNull('deleted_at')
        ->get();

        if(is_null($header)){
            return $this->sendNewError("This header doesn't exist.");
        }
         
        $questions = QuestionnaireQuestions::where('header_id',$headerId)
        ->where('is_active',1)
        ->whereNull('deleted_at')
        ->orderBy('pos', 'asc')
        ->get();

        foreach($questions as $question){
            $question->subtitle = $question->sub_title;
            $question->header_type_id = $question->header_id;
            //if its a global question
            if($question->is_nested_option){
                $question->min_selection = $question->min_selection ?? [];
                $question->max_selection = $question->max_selection ?? [];
                $question->info = $question->getHelper();

                $intesnityData = $question->updateIntensityValues();
                $question->intensity_value = $intesnityData['intensity_value'];
                $question->intensity_color = $intesnityData['intensity_color'];
                $question->intensity_type = 2;

                $question->option = $question->getOptions();
            }else{
                //if it is not global question
                $options = QuestionnaireQuestionOptions::where('question_id', $question->id)
                ->where('is_active',1)
                ->whereNull('deleted_at')
                ->orderBy('pos', 'asc')
                ->get();
                
                foreach($options as $option){
                    $option->value = $option->title;
                    $option->option_type = $option->getOptionType();
                    $option->image_url = $option->getImage();                
                    
                    //set intensity
                    if($option->is_intensity){
                        $intesnityData = $option->updateIntensityValues();
                        $option->intensity_value = $intesnityData['intensity_value'];
                        $option->intensity_color = $intesnityData['intensity_color'];
                        $option->intensity_type = 2;
                    }
                }
                $question->option = $options;
            }
        }

        $data = ["question"=>$questions, "answer"=>[]];
        $this->model = $data;
        return $this->sendNewResponse();
    }

    public function getNestedOptions(Request $request, $id, $headerId, $questionId){
        
        $id = $request->has('id') ? $request->input('id') : null;
        if(is_null($id)){
            return $this->sendNewError("Id is missing in query");
        }

        $this->model = [];
        $option = \DB::table('global_nested_option')->where('id',$id)
        ->whereNull('deleted_at')
        ->where('is_active',1)->first();

        if(is_null($option)){
            return $this->sendNewError("Option with this id doesn't exist");

        }

        $optionList = \DB::table('global_nested_option')->where('parent_id',$option->s_no)
        ->whereNull('deleted_at')
        ->where('is_active',1)
        ->orderBy('pos', 'asc')
        ->get();

        $this->model['question'] = $optionList;
        return $this->sendNewResponse();
    }

    public function getNestedOptionSearch(Request $request, $id, $headerId, $questionId)
    {
        $this->model = [];
        $term = $request->input('term');
        $term = array_filter(explode(" ",$term), 'strlen');

        $question = QuestionnaireQuestions::where('id',$questionId)
        ->whereNull('deleted_at')->where('is_active',1)->first();
        if(is_null($question)){
            return $this->sendNewError("Questiuon not found");
        }

        $options = \DB::table('global_nested_option')->where('type',$question->nested_option_list)
        ->whereNull('deleted_at')
        ->where('is_active',1)
        ->where(function ($query) use ($term){
        foreach($term as $val)
        {
            $query->orWhere('value','like','%'.$val.'%');
        }
        })->get();

        $this->model['option'] = $options;
        return $this->sendNewResponse();
    }
    
    public function shareQuestionnaire(Request $request, $id){
        $questionnaire = QuestionnaireLists::select('id','title','description')->where('id',$id)->first();
        if(is_null($questionnaire)){
            return $this->sendNewError("This questionnaire doesn't exist.");
        }
        $emailList = $request->email;
        $error = "";
        $deepLinksList = [];
        foreach($emailList as $email){
            $otpNo = mt_rand(100000, 999999);
            $questionnaire->email = $email;
            $deepLink = Deeplink::getQuestionnairePreviewLink($questionnaire);
            $deepLink->otp = $otpNo;
            array_push($deepLinksList, $deepLink);
            $data = ["email"=>$email, "questionnaire_id"=> $id, "otp"=>$otpNo, "created_at"=>date("Y-m-d H:i:s"), "updated_at"=>date("Y-m-d H:i:s"), "expired_at"=>date("Y-m-d H:i:s", strtotime("+7 days"))];
            $insertData = QuestionnairePreviewShareUsers::create($data); 
            if($insertData){
                // \Mail::to($email)->send(new QuestionnairePreviewShareMail(["link" => $deepLink, "otp"=>$otpNo]));     
            }else{
                $error .= $email.", ";
            }   
        }
        
        if(strlen($error) > 0){
            $error = "Sharing failed to email: ".substr($error, 0, strlen($error)-2);
            $this->model = $error;
            return $this->sendNewError($error);
        }else{
            $this->model = $deepLinksList;
            return $this->sendNewResponse();
        }
    }

    public function generateToken(Request $request, $id){
        $questionnaire = QuestionnaireLists::select('id','title','description')->where('id',$id)->first();
        if(is_null($questionnaire)){
            return $this->sendNewError("This questionnaire doesn't exist anymore.");
        }
        
        $otpNo = $request->otp ?? null;
        $email = $request->email ?? null;
        if(is_null($otpNo) || is_null($email)){
            return $this->sendNewError("Please provide email & otp to verify.");
        }
        
        $sharedUser = QuestionnairePreviewShareUsers::where('questionnaire_id', $id)
        ->where('email', $email)
        ->where('otp', $otpNo)
        ->whereNull('deleted_at')
        ->where('expired_at', '>=', date("Y-m-d"))
        ->first();

        if(!isset($sharedUser) || is_null($sharedUser)){
            return $this->sendNewError("Otp verification failed. Please enter correct details.");
        }
        
        $token = $temporaryToken = Str::random(120);
        $data = ["questionnaire_share_id"=>$sharedUser->id, "email"=>$email, "source"=> "mail", "token"=>$token, "created_at"=>date("Y-m-d H:i:s"), "updated_at"=>date("Y-m-d H:i:s"), "expired_at"=>date("Y-m-d H:i:s", strtotime("+30 minutes"))];

        $insertData = TempTokens::create($data);
        if($insertData){
            QuestionnairePreviewShareUsers::where('id', $sharedUser->id)->update(["attempts"=> $sharedUser->attempts+1, "updated_at"=>date("Y-m-d H:i:s")]);

            $this->model = ["token"=>$token];
            return $this->sendNewResponse();    
        }else{
            return $this->sendNewError("Unable to generate verification token.");    
        }
    }
}
