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
        $headerInfo2 = [



            ['header_name' => "INSTRUCTIONS",'header_selection_type'=>"0"],


            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],


            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally (please include all the components present in the product) and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],


            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],



            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]



        ];

        $questions2 = '{

	



"INSTRUCTIONS": [{

		"title": "Instruction",

		"subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of each section.\n\nPlease note that you are reviewing the product and NOT the package.\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",

		"select_type": 4

	}],


	"APPEARANCE": [{

			"title": "What is the serving temperature of the product?",

			"subtitle": "You may also touch the product to assess the serving temperature.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Frozen",

					"is_intensity": 0

				},

				{

					"value": "Chilled",

					"is_intensity": 0

				},

				{

					"value": "Cold",

					"is_intensity": 0

				},

				{

					"value": "Room temperature",

					"is_intensity": 0

				},

				{

					"value": "Warm",

					"is_intensity": 0

				},

				{

					"value": "Hot",

					"is_intensity": 0

				},

				{

					"value": "Burning hot",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "How is the visual impression (color and sheen) of the product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Bright",

					"is_intensity": 0

				},

				{

					"value": "Dull",

					"is_intensity": 0

				},

				{

					"value": "Shiny",

					"is_intensity": 0

				},

				{

					"value": "Oily",

					"is_intensity": 0

				},

				{

					"value": "Light",

					"is_intensity": 0

				},
				{

					"value": "Dark",

					"is_intensity": 0

				},
				{

					"value": "Natural",

					"is_intensity": 0

				},
				{

					"value": "Artificial",

					"is_intensity": 0

				}
			]

		},

		{

			"title": "How well has the product been mixed?",
			"subtitle": "Before assessing, please stir the product again.",
			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Evenly mixed",

					"is_intensity": 0

				},

				{

					"value": "Unevenly mixed",

					"is_intensity": 0

				},
				{

					"value": "Clots",

					"is_intensity": 0

				}
			]

		},
		{

			"title": "How is the visual texture of rice in the product?",
			"subtitle": "You may also touch to feel the texture.",
			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Fluffy",

					"is_intensity": 0

				},

				{

					"value": "Separated",

					"is_intensity": 0

				},
				{

					"value": "Moist",

					"is_intensity": 0

				},

				{

					"value": "Mushy",

					"is_intensity": 0

				},
				{

					"value": "Sticky",

					"is_intensity": 0

				},

				{

					"value": "Lumpy",

					"is_intensity": 0

				},
				{

					"value": "Firm",

					"is_intensity": 0

				},
				{

					"value": "Hard",

					"is_intensity": 0

				},

				{

					"value": "Foreign particles",

					"is_intensity": 0

				},
				{

					"value": "Any other",

					"is_intensity": 0

				}
			]

		},
		{

			"title": "Overall preference of Appearance",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	],

	


"AROMA": [{

			"title": "What all aromas have you sensed?",

			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>. ",

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_list": "AROMA",

			"nested_option_title": "AROMAS"

		},

		{

			"title": "Overall preference of Aroma",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}


	],

	"TASTE": [{

			"title": "Which Basic tastes have you sensed?",

			"is_nested_question": 0,

			"is_intensity": 0,

			"is_nested_option": 0,

			"is_mandatory": 1,

			"select_type": 2,

			"option": [{

					"value": "Sweet",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Salt",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Sour",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic,Moderately Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"

				},

				{

					"value": "Bitter",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Umami",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "No Basic Taste",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "Which Ayurvedic tastes have you sensed?",

			"select_type": 2,

			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [{

					"value": "Astringent (Puckery - Raw Banana)",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Pungent (Spices / Garlic)",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Pungent Cool Sensation (Mint)",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Pungent Chilli",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense, Very Intense, Extremely Intense"

				},

				{

					"value": "No Ayurvedic Taste",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "Overall preference of Taste",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	],

	


"AROMATICS TO FLAVORS": [{

			"title": "What all aromatics have you sensed?",

			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", 

			"select_type": 2,

			"is_intensity": 1,

			"intensity_type": 2,

			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",

			"is_nested_question": 0,

			"is_mandatory": 1,

			"is_nested_option": 1,

			"nested_option_title": "AROMATICS",

			"nested_option_list": "AROMA"

		},

		{

			"title": "Please swallow the product and pause. How is the aftertaste?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Pleasant",

					"is_intensity": 0

				},

				{

					"value": "Unpleasant",

					"is_intensity": 0

				},

				{

					"value": "Can\'t say",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "What is the length of the aftertaste?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Long",

					"is_intensity": 0

				},

				{

					"value": "Sufficient",

					"is_intensity": 0

				},

				{

					"value": "Short",

					"is_intensity": 0

				},

				{

					"value": "None",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "How is the flavor experience?",

			"subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Natural & pleasant",

					"is_intensity": 0

				},

				{

					"value": "Natural but unpleasant",

					"is_intensity": 0

				},

				{

					"value": "Artificial but pleasant",

					"is_intensity": 0

				},

				{

					"value": "Artificial & unpleasant",

					"is_intensity": 0

				},

				{

					"value": "Bland",

					"is_intensity": 0

				}

			]

		},
		{

			"title": "Overall preference of Aromatics",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	],

	


"TEXTURE": [
  
  {

			"title": "How much force is needed to chew?",
			"subtitle":"Try to take approxiametly a spoonful of product, chew for 3- 4 times and pause.",

			"is_nested_question": 0,

			"is_intensity": 0,

			"is_mandatory": 1,

			"select_type": 1,

			"option": [{

					"value": "Barely any",

					"is_intensity": 0

				},

				{

					"value": "Normal",

					"is_intensity": 0

				},

				{

					"value": "Excess",

					"is_intensity": 0

				}

			]

		},
		{

			"title": "What are you feeling prominently about the rice inside the mouth?",
      "subtitle": "Take a spoonful of rice and chew for 3-4 times.",
		
			"select_type": 2,

			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [{

					"value": "Chewy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Smooth",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "Granular",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Spongy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "Sticky",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Coarse",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "Gritty",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Hard",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				}
			]

		},
		{

			"title": "What do you feel inside your mouth?",

			"select_type": 2,

			"is_intensity": 0,

			"is_mandatory": 1,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"option": [{

					"value": "Oil",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Moisture",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "Dry",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				}
			]

		},
  {

			"title": "What kind of the mass is being formed inside your mouth?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Tight mass",

					"is_intensity": 0

				},

				{

					"value": "Pulpy mass",

					"is_intensity": 0

				},

				{

					"value": "Barely any mass",

					"is_intensity": 0

				},
				{

					"value": "No mass",

					"is_intensity": 0

				}
			]

		},

{

			"title": "After swallowing the product, do you feel anything left inside the mouth?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Loose particles",

					"is_intensity": 0

				},

				{

					"value": "Sticking on tooth/palate",

					"is_intensity": 0

				},

				{

					"value": "Stuck between teeth",

					"is_intensity": 0

				},
				{

					"value": "Chalky",

					"is_intensity": 0

				},
				{

					"value": "Oily film",

					"is_intensity": 0

				},
				{

					"value": "Any other",

					"is_intensity": 0

				},
				{

					"value": "No residue",

					"is_intensity": 0

				}
			]

		},
		
		{

			"title": "Overall preference of Texture",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	],

	


"PRODUCT EXPERIENCE": [{

			"title": "Did this product succeed in satisfying your basic senses?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

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

			"title": "Which attributes can be improved further?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

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

					"value": "Aromatics to flavor",

					"is_intensity": 0

				},

				{

					"value": "Texture",

					"is_intensity": 0

				},

				{

					"value": "Balanced product",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "Overall Product Preference",

			"select_type": 5,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dislike Extremely",

					"color_code": "#8C0008"

				},

				{

					"value": "Dislike Moderately",

					"color_code": "#C92E41"

				},

				{

					"value": "Dislike Slightly",

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

					"value": "Like Extremely",

					"color_code": "#305D03"

				}

			]

		},

		{

			"title": "Comments",

			"placeholder": "Share feedback in your own words…",

			"select_type": 3,

			"is_intensity": 0,

			"is_mandatory": 0,

			"is_nested_question": 0

		}

	]

}';

        $data = ['name'=>'Seasoning (Dry) _ Rice_LT_18th oct19','keywords'=>"Seasoning (Dry) _ Rice_LT_18th oct19",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}