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


            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long- lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],



            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odor/s that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is chewed; and it may even last after the product is swallowed. Product may make sound (chips), may give us joy (creamy foods), and may even cause pain or disgust (sticky/slimy foods)."],'header_selection_type'=>"1"],



            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavors, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]



        ];

        $questions2 = '{

	



"INSTRUCTIONS": [{

		"title": "Instruction",

		"subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of each section.\n\nRemember, there are no right or wrong answers.",

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

			"title": "How is the color of the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Creamish",

					"is_intensity": 0

				},

				{

					"value": "Whitish",

					"is_intensity": 0

				},

				{

					"value": "Beige",

					"is_intensity": 0

				},

				{

					"value": "Light brown",

					"is_intensity": 0

				},

				{

					"value": "Brown",

					"is_intensity": 0

				},

				{

					"value": "Any other",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "How is the visual impression of the product?",

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

					"value": "Greasy(Oily/ Buttery)",

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

			"title": "How is the visual appeal in terms of the size of the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Very small",

					"is_intensity": 0

				},

				{

					"value": "Small",

					"is_intensity": 0

				},

				{

					"value": "Moderate (Appropriate)",

					"is_intensity": 0

				},

				{

					"value": "Large",

					"is_intensity": 0

				},

				{

					"value": "Excessively large",

					"is_intensity": 0

				}

			]

		},
		{

			"title": "What is your assessment about the thickness/ thinness of the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Barely thin",

					"is_intensity": 0

				},

				{

					"value": "Thin",

					"is_intensity": 0

				},

				{

					"value": "Moderate (Appropriate)",

					"is_intensity": 0

				},

				{

					"value": "Thick",

					"is_intensity": 0

				},
					{

					"value": "Very thick",

					"is_intensity": 0

				}
			]

		},
		{

			"title": "How is the visual texture of the product?",
      "subtitle": "Please select a maximum of top 4 options only.",
			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Soft",

					"is_intensity": 0

				},

				{

					"value": "Flaky",

					"is_intensity": 0

				},

				{

					"value": "Crusty",

					"is_intensity": 0

				},

				{

					"value": "Mushy",

					"is_intensity": 0

				},
					{

					"value": "Bubbly (Roti)",

					"is_intensity": 0

				},
				{

					"value": "Blistered",

					"is_intensity": 0

				},

				{

					"value": "Seasoned dough",

					"is_intensity": 0

				},
				{

					"value": "Leathery",

					"is_intensity": 0

				},

				{

					"value": "Firm",

					"is_intensity": 0

				},
				{

					"value": "Dry flour",

					"is_intensity": 0

				},

				{

					"value": "Cracked",

					"is_intensity": 0

				},
				{

					"value": "Sticky",

					"is_intensity": 0

				},

				{

					"value": "Soggy",

					"is_intensity": 0

				}
			]

		},
		{

			"title": "How is the cooked appeal of the product?",
			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Evenly cooked",

					"is_intensity": 0

				},

				{

					"value": "Undercooked (Raw dough)",

					"is_intensity": 0

				},

				{

					"value": "Overcooked (Burnt spots)",

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

			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\".",

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

			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",

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

			"title": "How much force is needed to chew the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Barely any",

					"is_intensity": 0

				},

				{

					"value": "Weak",

					"is_intensity": 0

				},

				{

					"value": "Moderate",

					"is_intensity": 0

				},
				{

					"value": "Intense",

					"is_intensity": 0

				},

				{

					"value": "Very intense",

					"is_intensity": 0

				}
				
			]

		},
		{

			"title": "As you chew, which of these are being released from the product?",

			"subtitle": "Please chew for 3 - 4 times and pause.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,
			
			"is_nested_option": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Moisture",

           "is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Grease (Oil/ Butter)",

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

			"title": "While chewing, which prominent textures can you feel in your mouth?",

			"subtitle": " Please select a maximum of 3 options.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,
			
			"is_nested_option": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Chewy",

           "is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Tender",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},

				{

					"value": "Fluffy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Fibrous",

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

					"value": "Grainy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Gritty (Hard to chew)",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Rubbery",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Leathery",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Firm",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				},
				{

					"value": "Crusty",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


				}
				
			]

		},
		{

			"title": "What kind of mass is being formed?",
			"subtitle": "Chew the product for minimum 8-10 times and pause.",

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

			"title": "Is the product difficult to swallow?",

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

			"title": "Did you feel anything left inside the mouth after swallowing the product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

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

					"value": "Stuck between teeth/palate",

					"is_intensity": 0

				},
				{

					"value": "Chalky",

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

					"value": "Aromatics To Flavor",

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

        $data = ['name'=>'Roti_Private_Modern','keywords'=>"Roti_Private_Modern",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}