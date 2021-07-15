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

            $decodeJson = (!is_array($value) ? json_decode($value, true) : $value);

            $requiredNode = ["question_id", "question_type_id"];
            if (is_array($decodeJson)) {
                foreach ($decodeJson as $values) {

                    $diff = array_diff($requiredNode, array_keys($values));
                    if (empty($diff)) {
                        return true;
                    }
                }
            }
            return false;
        }, [
            "Question Id or Type Missing"
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
