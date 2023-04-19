<?php

namespace App\Console\Commands;

use App\Quiz;
use App\QuizApplicants;
use App\QuizAnswers;
use Illuminate\Console\Command;


class QuizApplicantScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:quizapplicantscore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to sync old answeres to applicants table applicant_score column';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $applicants = \DB::table("quiz_applicants")
        ->where("application_status", "=", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"))
        ->where("deleted_at", "=", null)->groupBy('quiz_id')->get();

        foreach ($applicants as $profile) {
            $id = $profile->quiz_id;
            $profileUserId = $profile->profile_id;
               
               $result = $this->calculateScore($id,$profileUserId);
               $score_block[] = array(
                'score_text' => $result['score'],
                'total' => $result['total'], 
                'correct_answer' => $result['correctAnswerCount'],
                'incorrect_answer' => $result['incorrectAnswerCount'], 
                );
               $applicant_score = json_encode($score_block);
               \DB::table("quiz_applicants")->where('quiz_id', $id)->where('profile_id', $profileUserId)
               ->update(["applicant_score" => $applicant_score]);
              unset($score_block);
              unset($applicant_score);
        }  
    }

    public function calculateScore($id,$profileId)
    {
        //calculation of final score of an applicant
        $correctAnswersCount = 0;
        $incorrectAnswersCount = 0;
        
        $questions =  Quiz::where("id", $id)->whereNull("deleted_at")->first();

        if(!empty($questions->form_json)){
        $questions = json_decode($questions->form_json);
        } 
        
        $answerMapping = []; //original correct options wrt ques
        
        $answers = QuizAnswers::where("quiz_id", $id)
        ->where('profile_id', $profileId)->whereNull('deleted_at')->get();   
        
        $score = 0;
        if(!empty($questions)){
        $total = count($questions);
        }
        
        if (count($answers) > 0) {
            if(!empty($questions)){
            foreach ($questions as $value) {
                foreach ($value->options as $option) {
                    if (isset($option->is_correct) && $option->is_correct) {

                        $answerMapping[$value->id][] = $option->id;
                    }
                }
            }
           }


           if(!empty($questions)){
            foreach ($questions as $question) {
                
                $answerArray = QuizAnswers::where("quiz_id", $id)->where("question_id", $question->id)->where("profile_id", $profileId)->whereNull("deleted_at")->pluck("option_id")->toArray();    
                
                sort( $answerArray);
                sort($answerMapping[$question->id]);
                if(!empty($answerArray)){
                if ($answerMapping[$question->id] == $answerArray) { //checking if original correct options is matching to users one
                    $correctAnswersCount++;
                    $score += 1;
                }
                else{
                    $incorrectAnswersCount++;
                }
            }
            }
            }
            $score = ($score * 100) / $total;

            $result["score"] = (is_float($score)) ? number_format($score, 2, ".", "") : $score;
            $result["correctAnswerCount"] = $correctAnswersCount;
            $result["incorrectAnswerCount"] = $incorrectAnswersCount;

        } 
        $result["total"] = $total;

        return $result;
    }
}
