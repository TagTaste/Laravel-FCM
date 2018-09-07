<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InsertGlobalQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'globalquestion:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert a new global question';

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
        $headerInfo2 = [['header_name'=>"INSTRUCTIONS"],

        ['header_name'=>"APPEARANCE","header_info"=>"OBSERVE the visual aspect of the product like it's shape, density of mass and color."],
    
        ['header_name'=>"AROMA","header_info"=>"SNIFF the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],
    
        ['header_name'=>"TASTE","header_info"=>"TAKE A BITE and figure out basic taste(s) you experienced."],
    
        ['header_name'=>"AROMATICS","header_info"=>"OBSERVE the smell that was released after you chewed the product."],
    
        ['header_name'=>"ORAL TEXTURE","header_info"=>"CHEW the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],
    
        ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product and provide some comments."],
    
];
        $questions2 = '{

          "INSTRUCTIONS": [{
       
             "title": "INSTRUCTIONS",
       
             "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
       
             "select_type": 4
       
          }],
       
       
          "APPEARANCE": [{
       
             "title": "Evenness in size",
       
             "select_type": 1,
       
             "is_intensity": 0,
       
             "is_nested_question": 0,
       
             "is_mandatory": 1,
       
             "option": "Full pieces,Evenly cut pieces,Unevenly cut pieces"
       
          }, {
       
             "title": "Physical status of the nuts",
       
             "select_type": 1,
       
             "is_intensity": 0,
       
             "is_nested_question": 0,
       
             "is_mandatory": 1,
       
             "option": "Uniform size,Natural form,Mixed size"
       
          }, {
       
             "title": "Spice coating",
       
             "select_type": 1,
       
             "is_intensity": 0,
       
             "is_nested_question": 0,
       
             "is_mandatory": 1,
       
             "option": "Even,Uneven"
       
          }, {
       
             "title": "Surface texture",
       
             "select_type": 2,
       
             "is_intensity": 0,
       
             "is_nested_question": 0,
       
             "is_mandatory": 1,
       
             "option": "Dehydrated,Bright,Rough,Smooth,Dry,Moist,Wet,Oily,Roasted,Crisp"
       
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
       
                "nested_option_list": "AROMA"
       
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
       
                "option": "Sweet,Salt,Sour,Bitter,Astringent,Pungent,Umami,Bland"
       
             }, {
       
                "title": "Length of after taste",
       
                "select_type": 1,
       
                "is_intensity": 0,
       
                "is_nested_question": 0,
       
                "is_mandatory": 1,
       
                "option": "Barely,Short,Sufficient,Long"
       
             }, {
       
                "title": "Chemical feeling factor (if observed)",
       
                "select_type": 2,
       
                "is_intensity": 1,
       
                "intensity_type": 1,
       
                "intensity_value": 15,
       
                "is_nested_question": 0,
       
                "is_mandatory": 1,
       
                "option": "Warm sensation spices,Chillies,Astringent,Hot temperature,Cold temperature"
       
             }, {
       
                "title": "Was the taste of nuts… ? ",
       
                "select_type": 1,
       
                "is_intensity": 0,
       
                "is_nested_question": 0,
       
                "is_mandatory": 1,
       
                "option": "Preserved,Enhanced,Masked by seasoning"
       
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
       
                "nested_option_list": "AROMA"
       
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
       
                "title": "Surface texture",
       
                "subtitle": "Hold the product between the lips",
       
                "select_type": 2,
       
                "is_intensity": 0,
       
                "is_nested_question": 0,
       
                "is_mandatory": 1,
       
                "option": "Dry,Oily,Smooth,Rough,Loose particles"
       
             }, {
       
                "title": "Identify type of sound",
       
                "subtitle": "Crispy: One single high pitch sound. Crunchy: Multiple low pitch sounds in a series. Crackly: One sudden low pitch sound, brittles product.",
       
                "select_type": 2,
       
                "is_intensity": 1,
       
                "intensity_type": 1,
       
                "intensity_value": 15,
       
                "is_nested_question": 0,
       
                "is_mandatory": 1,
       
                "option": "Crispy,Crunchy,Crackly"
       
             }, {
       
                "title": "First Chew",
       
                "is_nested_question": 1,
       
                "question": [{
       
                      "title": "Hardness",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Roughness",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Sound",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Loose particles",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Burst of flavour",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }]
       
             }, {
       
                "title": "Chew-down experience",
       
                "is_nested_question": 1,
       
                "question": [{
       
                      "title": "Moisture absorption",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Abrasiveness of mass",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Moistness",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Persistence of sound",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Cohesiveness of mass",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }]
       
             }, {
       
                "title": "Residual",
       
                "is_nested_question": 1,
       
                "question": [{
       
                      "title": "Mouth coating",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Oily film",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
                   }, {
       
                      "title": "Toothstick",
       
                      "select_type": 1,
       
                      "is_intensity": 0,
       
                      "is_nested_question": 0,
       
                      "is_mandatory": 1,
       
                      "option": "Low,Medium,High"
       
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
       
       }';
         $data = ['name'=>'Kari Kari','keywords'=>"Form for Japanese snacks",'description'=>'Kari Kari, Japan, Snacks, Healthy Snacks',
             'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

         \DB::table('global_questions')->insert($data);
    }
}
