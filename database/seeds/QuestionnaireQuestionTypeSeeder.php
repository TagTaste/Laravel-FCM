<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireQuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $questionList = [['title'=>'Instruction Queston', 'description'=>'instruction question','sort_order'=>1, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/iconParagraph%403x.png','select_type'=>4],
        ['title'=>'Single Choice', 'description'=>'Single choice question','sort_order'=>1, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/iconSingleSelect%403x.png','select_type'=>1],
        ['title'=>'Multiple Choice', 'description'=>'Multiple answer choices','sort_order'=>2, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/iconMultiSelect%403x.png','select_type'=>2],
        ['title'=>'Aroma - GLobal List Questions', 'description'=>'Question to select aroma list','sort_order'=>3, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/IconMultiRadioSelect@3x.png','select_type'=>2],
        ['title'=>'Comment type', 'description'=>'A short paragraph of 150 characters 
        ','sort_order'=>4, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/iconShortText%403x.png','select_type'=>3],
        ['title'=>'Linear Scale Question', 'description'=>'Select a number in a range','sort_order'=>5, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/IconRange@3x.png','select_type'=>5],
        ['title'=>'Food Shot & Food Bill Shot', 'description'=>'Upload images etc','sort_order'=>6, 'icon'=>'https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/images/survey_question_type/iconFileUpload%403x.png','select_type'=>6]];

        $data = [];
        foreach($questionList as $question){
            $extraElements = ['is_active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
            $question = array_merge($question, $extraElements);
            $data[] = $question;
        }

        \DB::table('questionnaire_question_types')->insert($data);
    }
}
