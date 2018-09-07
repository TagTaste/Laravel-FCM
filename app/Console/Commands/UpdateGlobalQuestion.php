<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateGlobalQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'globalquestion:update {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update a question question which takes global question ID as a argument';

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

        $id = $this->argument('id');

        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"Observe the visual aspect of the product."],
            ['header_name'=>"AROMA","header_info"=>"Sniff the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],
            ['header_name'=>"TASTE","header_info"=>"Take a sip and figure out basic taste(s) you experienced."],
            ['header_name'=>"AROMATICS","header_info"=>"Observe the smell that was released after you have sipped the product."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Sip the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product and provide some comments."],
        ];
        $questions2 = '{
   "INSTRUCTIONS": [{
      "title": "INSTRUCTIONS",
      "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
      "select_type": 4
   }],

   "APPEARANCE": [{
      "title": "Color of coffee",
      "select_type": 1,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": “Low,Medium,High”,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Brown (Coffee bean),Rust (Roasted coffee),Black (Brew only),Caramel (Milk coffee),Any other"
   }, {
      "title": "Overall preference",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
   }, {
      "title": "Comments",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0
   }],

   "AROMA": [{
         "title": "Aromas observed",
         "select_type": 2,
         "is_intensity": 1,
         "intensity_type": 1,
         "intensity_value": 15,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "is_nested_option": 1,
         "nested_option_list": "AROMA",
      }, {
      "title": "Any off aroma (If yes, describe more in comments)",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
   }, {
         "title": "Overall preference",
         "select_type": 5,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
      }, {
         "title": "Comments",
         "select_type": 3,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 0
      }],

   "TASTE": [{
         "title": "Basic taste",
         "select_type": 2,
         "is_intensity": 1,
         "intensity_type": 1,
         "intensity_value": 15,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Sweet,Salt,Sour,Bitter,Umami,Astringent,Pungent"
      }, {
         "title": "Chemical feeling factor (if observed)",
         "select_type": 1,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Yes,No"
      }, {
         "title": "Overall preference",
         "select_type": 5,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
      }, {
         "title": "Comments",
         "select_type": 3,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 0
      }],

   "AROMATICS": [{
         "title": "Aromatics observed",
         "subtitle": "Aromatics is the smell that is released after you chew the product",
         "select_type": 2,
         "is_intensity": 1,
         "intensity_type": 1,
         "intensity_value": 15,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "is_nested_option": 1,
         "nested_option_list": "AROMA",
      }, {
         "title": "After-taste",
         "is_nested_question": 1,
         "question": [{
               "title": "After-taste",
               "select_type": 1,
               "is_intensity": 1,
               "intensity_type": 2,
               "intensity_value": “Low,Medium,High”,
               "is_nested_question": 0,
               "is_mandatory": 1,
               "option": "Good,Bad"
            }, {
               "title": "Duration of the after-taste",
               "select_type": 1,
               "is_intensity": 0,
               "is_nested_question": 0,
               "is_mandatory": 1,
               "option": "None,Short,Sufficient,Long"
            }]
      }, {
         "title": "Overall preference",
         "select_type": 5,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
      }, {
         "title": "Comments",
         "select_type": 3,
         "is_intensity": 0,
         "is_mandatory": 0,
         "is_nested_question": 0
      }],

   "ORAL TEXTURE": [{
         "title": "Acidity",
         "is_nested_question": 1,
         "question": [{
               "title": "Brightness of coffee",
               "select_type": 1,
               "is_intensity": 0,
               "is_nested_question": 0,
               "is_mandatory": 1,
               "option": "Flat,Bright"
            }, {
               "title": "If you selected Bright, was it...?",
               "select_type": 2,
               "is_intensity": 1,
               "intensity_type": 1,
               "intensity_value": 15,
               "is_nested_question": 0,
               "is_mandatory": 0,
               "option": "Tangy,Winey,Sour,Fermented"
            }]
      }, {
         "title": "Body",
         "is_nested_question": 1,
         "question": [{
               "title": "Is it like...?",
               "select_type": 1,
               "is_intensity": 1,
               "intensity_type": 1,
               "intensity_value": 15,
               "is_nested_question": 0,
               "is_mandatory": 1,
               "option": "Syrup,Whole Milk,Water,Any other"
            }]
      }, {
         "title": "Sweetness",
         "is_nested_question": 1,
         "question": [{
               "title": "Fullness of Flavour",
               "select_type": 1,
               "is_intensity": 0,
               "is_nested_question": 0,
               "is_mandatory": 1,
               "option": "None,Barely detectable,Identifiable but not very intense,Slighty intense,Moderately intense,Intense,Very intense,Extremely intense"
            }]
      }, {
         "title": "Overall preference",
         "select_type": 5,
         "is_intensity": 0,
         "is_nested_question": 0,
         "is_mandatory": 1,
         "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
      }, {
         "title": "Comments",
         "select_type": 3,
         "is_intensity": 0,
         "is_mandatory": 0,
         "is_nested_question": 0
      }],

   "OVERALL PREFERENCE": [{
      "title": "Are these 5 elements balanced: Aroma, Taste, Acidity, Body and Flavour?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
   }, {
      "title": "Full product experience",
      "select_type": 5,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Don\'t like,Can\'t say,Somewhat like,Clearly like,Love it"
   }, {
      "title": "Comments",
      "select_type": 3,
      "is_intensity": 0,
      "is_mandatory": 0,
      "is_nested_question": 0
   }]
}
';

         $data = ['name'=>'Cold Brew Coffee','keywords'=>"Cold Brew,Coffee,Cold Brew Coffee",'description'=>'Cold Brew Coffee',
             'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

         \DB::table('global_questions')->where('id',$id)->update($data);
    }
}
