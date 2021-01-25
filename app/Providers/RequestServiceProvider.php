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
            $requiredNode = ["type", "title", "image_meta", "media_meta", "description", "id", "is_mandatory", "options"];
            $optionNodeChecker = ["id", "value", "label", "option_type", "image_meta", "media_meta"];
            $getListOfFormQuestions = SurveyQuestionsType::where("is_active", "=", 1)->get()->pluck("id")->toArray();
            foreach ($decodeJson as $values) {
                if (in_array($values["type"], $getListOfFormQuestions)) {
                    $diff = array_diff($requiredNode, array_keys($values));
                    if (empty($diff) && isset($values["options"])) {
                        foreach($values["options"] as $opt){
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
