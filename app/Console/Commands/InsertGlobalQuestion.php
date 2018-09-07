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

        ['header_name'=>"APPEARANCE","header_info"=>"Observe the visual aspect of the product like it's shape, density of mass and color."],
    
        ['header_name'=>"AROMA","header_info"=>"Sniff the product. If you experienced aroma, fill up this section. Otherwise, move to the next section."],
    
        ['header_name'=>"TASTE","header_info"=>"Take a bite and figure out basic taste(s) you experienced."],
    
        ['header_name'=>"AROMATICS","header_info"=>"Observe the smell that was released after you chewed the product."],
    
        ['header_name'=>"ORAL TEXTURE","header_info"=>"Chew the product multiple times. Observe if it sticks to the mouth, its loose particles and after-taste."],
    
        ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product and provide some comments."],
    
    ];
        $questions2 = '{

            "INSTRUCTIONS": [{
         
               "title": "INSTRUCTIONS",
         
               "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
         
               "select_type": 4
         
            }],
         
         
            "APPEARANCE": [{
         
               "title": "Visual observation",
         
               "select_type": 2,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Broken,Cracked,Foreign material,Blisters,Folds,Sugar / crystals,Flat,Crushed,Fluffy,Even"
         
            }, {
         
               "title": "Color of the crust",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Hay,Straw,Golden,Copper,Bronze,Light brown,Brown,Chocolate,Charcoal,Any other"
         
            }, {
         
               "title": "Intensity of the color",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Low,Medium,High"
         
            }, {
         
               "title": "Sponginess (on touching)",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Low,Medium,High"
         
            }, {
         
               "title": "Cross section (cut from the centre)",
         
               "select_type": 2,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Bright,Dull,Shiny,Matt,Greasy,Dense,Thick,Thin,Airy,Firm"
         
            }, {
         
               "title": "Filling",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Bright,Dull,Shiny,Matt"
         
            }, {
         
               "title": "Quantity of filling",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Less,More,Just fine"
         
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
         
               "title": "Any off aroma (if observed)",
         
               "select_type": 2,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 0,
         
               "option": "Chemical,Preservative,Metallic,Medicinal"
         
            }, {
         
               "title": "Overall aroma experience in a single word",
         
               "select_type": 1,
         
               "is_intensity": 0,
         
               "is_nested_question": 0,
         
               "is_mandatory": 1,
         
               "option": "Pleasant,Inviting,Mouthwatering,Unpleasant,Uninviting,Repelling"
         
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
         
                  "title": "Chemical feeling factor (if observed)",
         
                  "select_type": 2,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 0,
         
                  "option": "Hot,Cold,Dry feeling (Astringent),Metallic"
         
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
         
                  "option": "Rough,Smooth,Loose Particles,Oily,Moist,Buttery,Dry"
         
               }, {
         
                  "title": "Filling texture",
         
                  "select_type": 2,
         
                  "is_intensity": 0,
         
                  "is_nested_question": 0,
         
                  "is_mandatory": 1,
         
                  "option": "Sticky,Greasy,Dry and Hard,Wet,Soft,Creamy,Saucy,Watery,Crystal,Loose"
         
               }, {
         
                  "title": "First Chew",
         
                  "is_nested_question": 1,
         
                  "question": [{
         
                        "title": "Uniformity",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Low,Medium,High"
         
                     }, {
         
                        "title": "Burst of flavour (moisture release)",
         
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
         
                        "title": "Cohesiveness",
         
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
         
                        "title": "Sticky Texture",
         
                        "subtitle": "Is there a film being formed between product and teeth?",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Yes,No"
         
                     }, {
         
                        "title": "Pasty Texture",
         
                        "subtitle": "Forms quickly into a paste without sticking",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Yes,No"
         
                     }, {
         
                        "title": "Bite Length",
         
                        "subtitle": "Chewing time taken to form a bolus",
         
                        "select_type": 1,
         
                        "is_intensity": 0,
         
                        "is_nested_question": 0,
         
                        "is_mandatory": 1,
         
                        "option": "Long,Short,Just fine"
         
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
         $data = ['name'=>'Bunfills','keywords'=>"bunfills",'description'=>'Bunfills',
             'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

         \DB::table('global_questions')->insert($data);
    }
}
