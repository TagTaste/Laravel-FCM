<?php

namespace App\Providers;

use App\SurveyQuestionsType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('survey_question_form', function ($attribute, $value, $parameters, $validator) {
            $decodeJson = json_decode($value, true);
            $requiredNode = ["question_type", "title", "image_meta", "video_meta", "description", "id", "is_mandatory", "options"];
            $optionNodeChecker = ["id", "title", "option_type", "image_meta", "video_meta"];
            $getListOfFormQuestions = SurveyQuestionsType::where("is_active", "=", 1)->get()->pluck("question_type_id")->toArray();

            foreach ($decodeJson as $values) {
                if (isset($values["question_type"]) && in_array($values["question_type"], $getListOfFormQuestions)) {
                    $diff = array_diff($requiredNode, array_keys($values));
                    if (empty($diff) && isset($values["options"])) {
                        foreach ($values["options"] as $opt) {
                            $diffOptions = array_diff($optionNodeChecker, array_keys($opt));

                            if (!empty($diffOptions)) {
                                return false;
                            }
                            return true;
                        }
                    }
                }
                return false;
            }
        }, [
            "Form Payload Invalid"
        ]);

        Validator::extend('survey_answer_scrutiny', function ($attribute, $value, $parameters, $validator) {
            $decodeJson = json_decode($value, true);
        
            $requiredNode = ["question_id", "question_type_id", "option"];
            $optionNodeChecker = ["id", "value", "option_type", "media"];


            foreach ($decodeJson as $values) {
                $diff = array_diff($requiredNode, array_keys($values));
                if (empty($diff) && isset($values["option"])) {
                    foreach ($values["option"] as $opt) {
                        $diffOptions = array_diff($optionNodeChecker, array_keys($opt));
                        if (!empty($diffOptions)) {
                            return false;
                        }
                        return true;
                    }
                }

                return false;
            }
        }, [
            "Answer JSON Invalid"
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
