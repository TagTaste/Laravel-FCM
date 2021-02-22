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
        Validator::extend('survey_answer_scrutiny', function ($attribute, $value, $parameters, $validator) {
            
            $decodeJson = (!is_array($value) ? json_decode($value, true) : $value ); 
            
            $requiredNode = ["question_id", "question_type_id", "options"];
            $optionNodeChecker = ["id", "value", "option_type"];
            
            if (is_array($decodeJson)) {
                foreach ($decodeJson as $values) {
                
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
            }
            return false;
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
