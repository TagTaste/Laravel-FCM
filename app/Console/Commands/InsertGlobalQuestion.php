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
                ['header_name'=>"ORAL TEXTURE","header_info"=>"Chew the product multiple times. Observe if it the product sticks to the mouth, its loose particles , the sound and after-taste."],
                ['header_name'=>"OVERALL PRODUCT PREFERENCE","header_info"=>"Rate the overall experience of the product."],
            ];


        $questions2 = '{


	"INSTRUCTIONS": [{
		"title": "INSTRUCTION",
		"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
		"select_type": 4
	}],


	"APPEARANCE": [{
			"title": "Color and evenness of crust",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Light brown,Pale brown,Golden brown,Dark brown"
		},
		{
			"title": "Color of the mass",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Lemon,Golden tone,Light brown,Baked brown"
		},
		{
			"title": "Shape",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Even,Uneven"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"AROMA": [{
			"title": "Aroma observed",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"TASTE": [{
			"title": "Basic Taste",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Sweet",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Salt",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Sour",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"title": "Bitter",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"title": "Umami",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}
			]
		},
		{
             "title": "Ayurveda Taste - numeric intensity",

            "select_type": 2,

            "is_mandatory": 1,

            "is_intensity": 1,

            "intensity_type": 1,

            "intensity_value": 15,

            "is_nested_question": 0,

            "option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
        },
        {
			"title": "Ayurveda Taste",
			"select_type": 2,
			"is_mandatory": 1,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"is_nested_question": 0,
			"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
		},
		{
			"title": "If you were to make your own chocolate what will be the combination of bitter and sweet taste",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "30% Bitter and 70% Sweet,70% Bitter and 30% Sweet,100% Bitter,15% Bitter,10% Bitter and 90% Sweet (Milk Chocolate)"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"AROMATICS": [{
			"title": "Feel of baked flour",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Aromatics observed",
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
			"is_nested_question": 0,
			"is_mandatory": 1,
			"is_nested_option": 1,
			"nested_option_list": "AROMA"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],

	"ORAL TEXTURE": [{
			"title": "Rougness of mass ",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Low,Medium,High"
		},
		{
			"title": "Sound",
			"is_nested_question": 0,
			"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
			"is_mandatory": 1,
			"select_type": 2,
			"is_intensity": 1,
			"intensity_type": 2,
			"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "FIRST CHEW",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Uniformity of bite",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Denseness of mass",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Crunchiness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"

				}
			]
		},
		{
			"title": "CHEWDOWN EXPERIENCE",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Moisture absorption",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				},
				{
					"title": "Cohesiveness of mass",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Low,Medium,High"
				}
			]
		},
		{
			"title": "RESIDUAL",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Loose particles",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Mouthcoating / tooth stickiness",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Dunking of rusk in tea/ milk/ coffee (4 seconds)",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Drops,Lumpy,Holds good"
				}
			]
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0
		}
	],


	"OVERALL PRODUCT PREFERENCE": [

		{
			"title": "Full product experience",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Don\'t like",
					"color_code": "#8C0008"
				},
				{
					"value": "Can\'t say",
					"color_code": "#C92E41"
				},
				{
					"value": "Somewhat like",
					"color_code": "#AC9000"
				},
				{
					"value": "Clearly like",
					"color_code": "#577B33"
				},
				{
					"value": "Love it",
					"color_code": "#305D03"
				}
			]
		},
		{
			"title": "Comments",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 0,
			"is_nested_question": 0
		}
	]
}';

        $data = ['name'=>'RUSK MULTIGRAIN Numeric','keywords'=>"RUSK MULTIGRAIN",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
