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
$headerInfo2 =  [
            ['header_name'=>"INSTRUCTIONS",'header_info'=>null],
            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the product- look for color, surface texture, evenness, uniformity etc."],
            ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't drink it yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. Anything that stands out as either too good or too bad, may please be highlighted in the comment box."],
            ['header_name'=>"TASTE","header_info"=>"Tasting Time! Please take a bite, eat normally and assess the taste or tastes. Anything that stands out as too good or too bad, may please be highlighted in the comment box. "],
            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Aromatics is the odour/s that you would experience inside your mouth, as you drink. Slurp noisily again, keeping your mouth closed and exhale through the nose. Try to identify the odors inside your mouth using the aroma options. Anything too good or too bad may please be highlighted in the comment box."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess the oral texture- please look for lip feel, first chew experience, chew down experience, swallow, and most importantly sound (whenever applicable)."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference."]
        ]
        ;


        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Any thing that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],
	"APPEARANCE": [{
			"title": "Attributes which meet your expectation",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color(Appeal),Shape (Evenness),Size,Surface texture"
		},
		{
			"title": "Attributes which need correction",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color(Appeal),Shape (Evenness),Size,Surface texture"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"subtitle": "We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to access the aroma list. Please select the maximum of 4 dominant aromas.",
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
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
	"AROMATICS TO FLAVORS": [{
			"title": "Aromatics observed",
			"subtitle": "Please mention maximum of 4 dominant aromas.",
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
			"title": "Aftertaste",
			"subtitle": "(Swallow the sample and assess the sensation on your tongue, inside your mouth etc) Identify a particular aftertaste in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant,Short,Long,Sufficient"
		},
		{
			"title": "FLAVOR",
			"subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the Flavor of the product that you are tasting.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
				"title": "Is this Flavor profile reminding you of any inspirational Flavor trends?",
				"select_type": 1,
				"is_intensity": 0,
				"is_nested_question": 0,
				"is_mandatory": 1,
				"option": "Wasabi,Sriracha,Smoky Barbeque,Tandoori,Kebab,Jalapeno Cheese,Chipotle,Sour cream and onion,Salsa,Pudina chutney,Creamy truffle"
			}]
		},
		{
			"title": "Overall preference",
			"subtitle": "Share your overall preference for the Flavor (considering Aromatics and Taste together)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"title": "Surface texture",
			"subtitle": "(Hold product between your lips)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Smooth,Rough,Sticky on lips,Oily on lips"
		},
		{
			"title": "Sound of the product",
			"subtitle": "(Concentrate on sound it produces after the first bite and subsequent bites)",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Crispy,Crunchy,Crackly"
		},
		{
			"title": "Oral Feel",
			"subtitle": "(First chew)",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Soft,Firm,Hard,Moist,Dry,Creamy,Spongy,Runny liquid"
		},
		{
			"title": "Chewing experience",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Shorter time to chew,Moderate time to chew,Longer time to chew,Melt in the mouth,"
		},
		{
			"title": "After swallowing, how do you feel inside the mouth?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Oily film,Loose particles,Sticking on tooth,Chalky,None"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
	"OVERALL PREFERENCE": [{
			"title": "Are all the attributes (appearance, aroma, taste, aromatics, flavor, and texture) in balance with each other?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "If not, what is/are out of balance?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Product Experience",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
        $data = ['name'=>'General Food','keywords'=>"General Food",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);


        $headerInfo2 =   [['header_name'=>"INSTRUCTIONS"],
            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the sample- look for color, surface texture, fluidity, etc."],
            ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't drink it yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. Anything that stands out as either too good or too bad, may please be highlighted in the comment box."],
            ['header_name'=>"TASTE","header_info"=>"Slurp noisily and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is the odour/s that you would experience inside your mouth, as you drink. Slurp noisily again, keeping your mouth closed and exhale through the nose. Try to identify the odors inside your mouth using the aroma options. Anything too good or too bad may please be highlighted in the comment box."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess the oral texture- please look for body and mouthfeel of the beverage."],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference."]

        ];



        $questions2 = '{

	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Any thing that stands out as either too good or too bad, may please be highlighted in the comments box.",
		"select_type": 4
	}],

	"APPEARANCE": [{
			"title": "Attributes which meet your expectation",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color(Appeal),Clarity,Fluidity,Texture"
		},
		{
			"title": "Attributes which need correction",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Color(Appeal),Clarity,Fluidity,Texture"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"subtitle": "We have list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to access the aroma list. Please select the maximum of 4 dominant aromas.",
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
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"title": "Aromatics observed",
			"subtitle": "Please mention maximum of 4 dominant aromas.",
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
			"title": "Aftertaste",
			"subtitle": "(Swallow the sample and assess the sensation on your tongue, inside your mouth etc) Identify a particular aftertaste in the comment box.",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "None,Pleasant,Unpleasant,Short,Long,Sufficient"
		},

		{
			"title": "FLAVOR",
			"subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the Flavor of the product that you are tasting.",
			"is_nested_question": 1,
			"is_mandatory": 1,
			"question": [{
					"title": "Is this Flavor profile reminding you of any inspirational Flavor trends?",
					"subtitle": "If yes, please mention it in the comment box",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				},
				{
					"title": "Is this beverage fermented?",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": "Yes,No"
				}
			]
		},
		{
			"title": "Overall preference",
			"subtitle": "Share your overall preference for the Flavor (considering Aromatics and Taste together)",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
			"title": "Body",
			"subtitle": "Refers to the heaviness of texture",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Syrup,Full cream milk,Toned Milk,Watery,Any other"
		},
		{
			"title": "Mouthfeel of the beverage",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Chocolatey,Earthy,Bright,Mellow,Sharp,Dry,Brisk,Complex,Delicate,Silky,Velvety,Raw,Heavy,Opulent,Biting,Astringent,Short,Frivolous,Fresh,Watery,Warm,Smooth,"
		},
		{
			"title": "Is this beverage distilled?l",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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

	"OVERALL PREFERENCE": [{
			"title": "Are all the attributes (appearance, aroma, taste, aromatics, flavor, and texture) in balance with each other?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "If not, what is/are out of balance?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 0,
			"option": "Appearance,Aroma,Taste,Aromatics,Flavor,Texture"
		},
		{
			"title": "Is the product sample acceptable?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": "Yes,No"
		},
		{
			"title": "Overall Preference",
			"select_type": 5,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Dislike Extremely",
					"color_code": "#8C0008"
				},
				{
					"value": "Dislike Strongly",
					"color_code": "#D0021B"
				},
				{
					"value": "Dislike Moderately",
					"color_code": "#C92E41"
				},
				{
					"value": "Can\'t Say",
					"color_code": "#E27616"
				},
				{
					"value": "Like Slightly",
					"color_code": "#AC9000"
				},
				{
					"value": "Like Moderately",
					"color_code": "#7E9B42"
				},
				{
					"value": "Like Strongly",
					"color_code": "#577B33"
				},
				{
					"value": "Like Extremely",
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
        $data = ['name'=>'General Beverages','keywords'=>"General Beverages",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
