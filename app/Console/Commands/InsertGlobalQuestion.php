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

            ['header_name'=>"AROMA","header_info"=>"Sniff the product. If you experienced aroma, fill up this section."],

            ['header_name'=>"TASTE","header_info"=>"Take a bite and figure out basic taste(s) you experienced."],

            ['header_name'=>"AROMATICS","header_info"=>"Observe the smell that was released after you chewed the product."],

            ['header_name'=>"ORAL TEXTURE","header_info"=>"Chew only for 3-4 times then answer first chew, continue chewing to get pulp and answer chew down experience and residual  sub section. Observe if it sticks to the mouth, its loose particles and after-feel."],

            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"Rate the overall experience of the product on the preference scale and write about balance/imbalance of 5 main attributes (Appearance, Aroma, Taste, Aromatics, Texture) in comment section below."],

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
		 
			   "option": "Broken,Cracked,Foreign material,Blisters,Folds,Sugar / crystals,Flat,Crushed,Fluffy,Even,Deflated,Balloon like"
		 
			}, {
		 
			   "title": "Color of the crust",
		 
			   "select_type": 2,
		 
			   "is_intensity": 1,
		 
			   "intensity_type": 2,
		 
			   "intensity_value": "Pale,Medium,Deep",
		 
			   "is_nested_question": 0,
		 
			   "is_mandatory": 1,
		 
			   "option": "Hay,Straw,Golden,Copper,Bronze,Light brown,Brown,Chocolate,Charcoal,Any other"
		 
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
		 
				  "title": "Appearance of filling",
		 
				  "is_nested_question": 1,
		 
				  "question": [{
		 
						"title": "Identify color",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Bright,Dull,Glace"
		 
					 }, {
		 
						"title": "Quantity",
		 
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
			   
			   "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]		 
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
		 
			   "is_intensity": 1,
		 
			   "intensity_type": 1,
		 
			   "intensity_value": 15,
		 
			   "is_nested_question": 0,
		 
			   "is_mandatory": 0,
		 
			   "option": "Chemical,Preservative,Metallic,Medicinal"
		 
			}, {
		 
			   "title": "Overall aroma experience",
		 
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
		 
				  "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]	
		 
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
		 
				  "option": "Sweet,Salt,Sour,Bitter,Umami"
		 
			   }, {
		 
				  "title": "Ayurveda taste intensity",
		 
				  "select_type": 2,
		 
				  "is_intensity": 1,
		 
				  "intensity_type": 1,
		 
				  "intensity_value": 15,
		 
				  "is_nested_question": 0,
		 
				  "is_mandatory": 1,
		 
				  "option": "Astringent (Dryness),Pungent - Masala (Warm Spices),Pungent - Cool Sensation (Cool Species),Pungent - Chilli"
		 
			   }, {
		 
				  "title": "Overall preference",
		 
				  "select_type": 5,
		 
				  "is_intensity": 0,
		 
				  "is_nested_question": 0,
		 
				  "is_mandatory": 1,
		 
				  "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]	
		 
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
		 
				  "title": "Identify OFF taste (consider aromatics as well)",
		 
				  "select_type": 2,
		 
				  "is_intensity": 1,
		 
				  "intensity_type": 1,
		 
				  "intensity_value": 15,
		 
				  "is_nested_question": 0,
		 
				  "is_mandatory": 1,
		 
				  "option": "Chemical,Excess Preservatives,Gas (Kerosene / Sulphur),Medicinal (Hospital smell),Metallic (Tin / Al / Cl),Tetra pack"
		 
			   }, {
		 
				  "title": "Aftertaste",
		 
				  "is_nested_question": 1,
		 
				  "question": [{
		 
						"title": "Did you feel the aftertaste? (consider aromatics as well)",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Yes,No"
		 
					 }, {
		 
						"title": "How was the aftertaste?",
		 
						"select_type": 1,
		 
						"is_intensity": 1,
		 
						"intensity_type": 2,
		 
						"intensity_value": "Weak,Sufficient,Strong,Overwhelming",
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Pleasant,Unpleasant"
		 
					 }, {
		 
						"title": "Length of the aftertaste?",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Short,Long"
		 
					 }]
		 
			   }, {
		 
				  "title": "Overall preference",
		 
				  "select_type": 5,
		 
				  "is_intensity": 0,
		 
				  "is_nested_question": 0,
		 
				  "is_mandatory": 1,
		 
				  "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]	
		 
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
		 
				  "option": "Sticky,Greasy,Dry and hard,Wet,Soft,Creamy,Saucy,Watery,Crystal,Loose,Pasty"
		 
			   }, {
		 
				  "title": "First Chew",
		 
				  "subtitle": "Chew 3-4 times and pause",
		 
				  "is_nested_question": 1,
		 
				  "question": [{
		 
						"title": "Uniformity",
		 
						"subtitle": "Same force need to chew a single bite",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Low,Medium,High"
		 
					 }, {
		 
						"title": "Burst of flavour",
		 
						"subtitle": "Moisture release",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Low,Medium,High"
		 
					 }, {
		 
						"title": "Melt in the mouth (only filling)",
		 
						"subtitle": "Amount of saliva and time needed for filling to melt",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Low,Medium,High"
		 
					 }]
		 
			   }, {
		 
				  "title": "Chew-down experience",
		 
				  "subtitle": "Chew multiple times to make pulp",
		 
				  "is_nested_question": 1,
		 
				  "question": [{
		 
						"title": "Moisture absorption",
		 
						"subtitle": "Amount of saliva absorbed",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Low,Medium,High"
		 
					 }, {
		 
						"title": "Cohesiveness",
		 
						"subtitle": "Pulp stays together or scatters",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Low,Medium,High"
		 
					 }, {
		 
						"title": "Sticky texture",
		 
						"subtitle": "Is there a film being formed between product and teeth?",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Yes,No"
		 
					 }, {
		 
						"title": "Pasty texture",
		 
						"subtitle": "Forms quickly into a paste without sticking",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Yes,No"
		 
					 }, {
		 
						"title": "Bite length",
		 
						"subtitle": "Chewing time taken to form a pulp",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Long,Short,Just fine"
		 
					 }]
		 
			   }, {
		 
				  "title": "Residual",
		 
				  "is_nested_question": 1,
		 
				  "question": [{
		 
						"title": "Did you feel anything left in mouth?",
		 
						"select_type": 1,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 1,
		 
						"option": "Yes,No"
		 
					 }, {
		 
						"title": "If residual was left, did you get...?",
		 
						"select_type": 2,
		 
						"is_intensity": 0,
		 
						"is_nested_question": 0,
		 
						"is_mandatory": 0,
		 
						"option": "Oily film,Loose particles,Sticking on Tooth,Chalky"
		 
					 }]
		 
			   }, {
		 
				  "title": "Overall preference",
		 
				  "select_type": 5,
		 
				  "is_intensity": 0,
		 
				  "is_nested_question": 0,
		 
				  "is_mandatory": 1,
		 
				  "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]	
		 
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
		 
			   "option": [{
			   
					 "value" : "Don\'t like",
					 
					 "color_code":"#CD5C5C"
					 
					 }, {
					 
					 "value" : "Can\'t like",
					 
					 "color_code":"#DC143C"
					 
					 },{
					 
					 "value" : "Somewhat like",
					 
					 "color_code":"#B22222"
					 
					 },{
					 
					 "value" : "Clearly like",
					 
					 "color_code":"#FF0000"
					 
					 },{
					 
					 "value" : "Love it",
					 
					 "color_code":"#CD5C5C"
					 
					 }]	
		 
			}, {
		 
			   "title": "Comments",
		 
			   "select_type": 3,
		 
			   "is_intensity": 0,
		 
			   "is_mandatory": 0,
		 
			   "is_nested_question": 0
		 
			}]
		 
		 }';
        $data = ['name'=>'Bunfills','keywords'=>"Bunfills",'description'=>'Bunfills',
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

        \DB::table('global_questions')->insert($data);
    }
}
