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
        $headerInfo2 = $headerInfo2 = [
            ['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=>"Visual examination of the product- look for color, presentation, size and texture of the product."],

            ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't take a bite yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. "],

            ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste/s and its intensity as mentioned in the section. What is Umami?When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste."],

            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Aromatics is different from the aroma, it is about experiencing odor/s inside the mouth, as you eat. Please take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odours inside your mouth using the aroma/aromatics list"],

            ['header_name'=>"TEXTURE","header_info"=>"Let us assess the (oral) texture- please look for lip feel, first chew experience, chew down experience, swallow, and most importantly sound (whenever applicable)."],

            ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]

        ];



        $questions2 = '{

	"INSTRUCTIONS": [

		{

			"title": "Instruction",

			"subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box.\n Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",

			"select_type": 4

		}

	],

	"APPEARANCE": [

		{

			"title": "How is the color of the product",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{

					"value": "Tempting",
					"is_intensity": 0
				},
				{
					"value": "Just fine",
					"is_intensity": 0

				},
				{
					"value": "Not appealing",
					"is_intensity": 0
				}

			]

		},

		{

			"title": "Did you like the presentation of the product (like shape, plating etc)",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Impressive",
					"is_intensity": 0
				},
				{
					"value": "Average",
					"is_intensity": 0

				},
				{
					"value": "Below average",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "Assess the portion size of the product",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

					"value": "Generous",
					"is_intensity": 0
				},
				{
					"value": "Adequate",
					"is_intensity": 0

				},
				{
					"value": "Inadequate",
					"is_intensity": 0
				}

			]
		},
		{

			"title": "How is the texture of the product",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{

					"value": "Crispy",
					"is_intensity": 0
				},
				{
					"value": "Firm",
					"is_intensity": 0

				},
				{
					"value": "Creamy",
					"is_intensity": 0
				}

			]

		},
		{

			"title": "Overall preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

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

		}

	],

	"AROMA": [

		{

			"title": "Identify the observed Aroma. Please mention a maximum of 2 dominant aromas.",

			"subtitle": "We have a list of aromas/ aromatics, grouped under different heads. If you select \"any other \" option please write the identified aromas. Use the search box to find any aroma/aromatics from the list.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",

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

			"option": [

				{

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

		}

	],

	"TASTE": [

		{

			"title": "Basic Taste",

			"is_nested_question": 0,
			"is_intensity": 1,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [

				{

					"value": "Sweet",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Salt",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Sour",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
				},
				{
					"value": "Bitter",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Umami",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "No Basic Taste",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				}

			]

		},

		{
			"title": "Ayurveda Taste",

			"select_type": 2,
			"is_intensity": 1,
			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [

				{
					"value": "Astringent (Dryness)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "Pungent (Spices/ Garlic)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"

				},
				{
					"value": "Pungent Cool Sensation (Mint)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"

				},
				{
					"value": "Pungent Chilli",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				},
				{
					"value": "No Ayurveda Taste",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
				}

			]

		},

		{

			"title": "Overall preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

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

		}

	],

	"AROMATICS TO FLAVORS": [

		{

			"title": "Identify the Aromatics observed. Please mention a maximum of 2 dominant aromatics.",

			"subtitle": "We have a list of aromas/ aromatics, grouped under different heads. If you select \"any other\" option please write the identified aromatics. Use the search box to find any  aroma/aromatics from the list.",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_list": "AROMA"

		},

		{

			"title": "After swallowing the food did you feel the presence of any aftertaste",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Yes",
					"is_intensity": 0

				},
				{
					"value": "No",
					"is_intensity": 0
				}

			]
		},

		{

			"title": "FLAVOR",

			"subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%). Congratulations! You just discovered the flavor/s of the product that you are tasting.",

			"is_nested_question": 1,

			"is_mandatory": 1,

			"question": [

				{

					"title": "Did you experience any Flavors?",

					"select_type": 1,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 1,

					"option": [

						{
							"value": "No Flavor",
							"is_intensity": 0

						},
						{
							"value": "Can\'t say",
							"is_intensity": 0
						},
						{
							"value": "Desirable Flavor",
							"is_intensity": 0

						},
						{
							"value": "Undesirable Flavor",
							"is_intensity": 0
						}

					]


				},
				{
					"title": "Was the observed flavor natural or any of the trending inspirational flavors. Please select the relevant options.",

					"select_type": 2,

					"is_intensity": 0,

					"is_nested_question": 0,

					"is_mandatory": 0,
					"option": [

						{
							"value": "Natural",
							"is_intensity": 0

						},
						{
							"value": "Wasabi",
							"is_intensity": 0
						},
						{
							"value": "Sriracha",
							"is_intensity": 0

						},
						{
							"value": "Smoky Barbeque",
							"is_intensity": 0
						},
						{
							"value": "Tandoori",
							"is_intensity": 0

						},
						{
							"value": "Kebab",
							"is_intensity": 0
						},
						{
							"value": "Jalapeno Cheese",
							"is_intensity": 0

						},
						{
							"value": "Chipotle",
							"is_intensity": 0
						},
						{
							"value": "Sour cream and onion",
							"is_intensity": 0

						},
						{
							"value": "Salsa",
							"is_intensity": 0
						},
						{
							"value": "Pudina chutney",
							"is_intensity": 0

						},
						{
							"value": "Creamy truffle",
							"is_intensity": 0
						},
						{
							"value": "Any other",
							"is_intensity": 0
						}

					]

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

			"option": [

				{

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

		}

	],

	"TEXTURE": [

		{

			"title": "Please put the product in your mouth and assess. Remember not to eat or chew at this stage.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Moist",
					"is_intensity": 0

				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{
					"value": "Creamy",
					"is_intensity": 0

				},
				{
					"value": "Spongy",
					"is_intensity": 0
				},
				{
					"value": "Runny liquid",
					"is_intensity": 0

				}
			]
		},

		{

			"title": "Sound of the product (Concentrate on the sound it produces after the first bite and subsequent bites)",

			"select_type": 1,

			"is_nested_question": 0,
			"is_intensity": 1,
			"is_mandatory": 1,
			"option": [

				{
					"value": "Crispy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
					"is_nested_question": 0,
					"is_nested_option": 0
				},
				{
					"value": "Crunchy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
					"is_nested_question": 0,
					"is_nested_option": 0

				},
				{
					"value": "Crackly",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic",
					"is_nested_question": 0,
					"is_nested_option": 0

				}

			]

		},

		{

			"title": "Please put the product again in your mouth, chew 3-4 times, pause and assess.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{
					"value": "Soft",
					"is_intensity": 0

				},
				{
					"value": "Firm",
					"is_intensity": 0
				},
				{
					"value": "Hard",
					"is_intensity": 0

				}
			]

		},

		{

			"title": "Chew down",

			"subtitle": "Take a bite again, chew it for 8-10 times to make a pulp. Now assess the time taken to make a pulp.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Shorter time to chew",
					"is_intensity": 0

				},
				{
					"value": "Moderate time to chew",
					"is_intensity": 0
				},
				{
					"value": "Longer time to chew",
					"is_intensity": 0

				}
			]

		},

		{

			"title": "After swallowing, how do you feel inside the mouth?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,
			"option": [

				{
					"value": "Oily film",
					"is_intensity": 0

				},
				{
					"value": "Loose particles",
					"is_intensity": 0
				},
				{
					"value": "Sticking on tooth",
					"is_intensity": 0

				},
				{
					"value": "Chalky",
					"is_intensity": 0

				},
				{
					"value": "None",
					"is_intensity": 0

				}
			]

		},

		{

			"title": "Overall preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

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

		}

	],

	"OVERALL PRODUCT EXPERIENCE": [

		{

			"title": "Are all the attributes (appearance, aroma, taste, aromatics to flavor and texture) in balance with each other?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Yes",
					"is_intensity": 0

				},
				{
					"value": "No",
					"is_intensity": 0
				}
			]

		},

		{

			"title": "If not, what is/are out of balance?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": [

				{
					"value": "Appearance",
					"is_intensity": 0

				},
				{
					"value": "Aroma",
					"is_intensity": 0
				},
				{
					"value": "Taste",
					"is_intensity": 0

				},
				{
					"value": "Aromatics to Flavor",
					"is_intensity": 0
				},
				{
					"value": "Texture",
					"is_intensity": 0
				}
			]
		},

		{

			"title": "Is the product sample acceptable?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{
					"value": "Yes",
					"is_intensity": 0

				},
				{
					"value": "No",
					"is_intensity": 0
				}
			]

		},

		{

			"title": "Overall Product Experience",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [

				{

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
        
        $data = ['name'=>'New Questionair with multiple option with different intensity - 1','keywords'=>"Masala/ Seasoning",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
