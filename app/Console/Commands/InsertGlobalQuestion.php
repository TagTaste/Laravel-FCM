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



            ['header_name' => "INSTRUCTIONS"],



            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Examine the product and answer the questions outlined below."]],


            ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."]],


            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."]],



            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these identified odors are called Aromatics."]],



            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."]],



            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."]]



        ];
        $questions2 = '{

	



"INSTRUCTIONS": [{

		"title": "Instruction",

		"subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of each section.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. ",

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

					"value": "Room Temperature",

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

					"value": "Burning Hot",

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

					"value": "Glazed",

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

			"title": "What all seasonings do you observe on the product?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Spice mix",

					"is_intensity": 0

				},

				{

					"value": "Crystals (Salt/ Sugar)",

					"is_intensity": 0

				},

				{

					"value": "Powder (Salt/ Sugar)",

					"is_intensity": 0

				},

				{

					"value": "Herbs",

					"is_intensity": 0

				},

				{

					"value": "Any other",

					"is_intensity": 0

				},

				{

					"value": "None",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "How does the visual texture of the product appear to you?",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Baked",

					"is_intensity": 0

				},

				{

					"value": "Roasted",

					"is_intensity": 0

				},

				{

					"value": "Fried",

					"is_intensity": 0

				},

				{

					"value": "Sticky",

					"is_intensity": 0

				},

				{

					"value": "Smooth",

					"is_intensity": 0

				},

				{

					"value": "Rough",

					"is_intensity": 0

				},

				{

					"value": "Crisp",

					"is_intensity": 0

				},

				{

					"value": "Limp",

					"is_intensity": 0

				},

				{

					"value": "Firm",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "Using fingers, hold the pieces for a few seconds and put them back on the plate. What do you observe on your fingers? ",

			"is_nested_question": 0,

			"is_intensity": 0,

			"is_nested_option": 0,

			"is_mandatory": 1,

			"select_type": 2,

			"option": [{

					"value": "Oily film",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Loose particles",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Seasoning",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "None",

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

			"title": "What all aromas have you sensed? ",

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

					"value": "Astringent (Dryness - Raw Banana)",

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

			"title": "Which components of the product are contributing more towards making the flavor experience better?",

			"subtitle": "Please select a maximum of two components.\nMain ingredient with <b>one base</b> is like kurkure snack; whereas the main ingredient with <b>multiple bases (multi- bases)</b> is like Khatta meetha snack which is a mixture of peanuts, corn flakes, puffed rice etc.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Main ingredient (One base)",

					"is_intensity": 0

				},

				{

					"value": "Main ingredient (Multi - bases)",

					"is_intensity": 0

				},

				{

					"value": "Spice mix",

					"is_intensity": 0

				},

				{

					"value": "Herbs",

					"is_intensity": 0

				},

				{

					"value": "Salt",

					"is_intensity": 0

				},

				{

					"value": "Sweet",

					"is_intensity": 0

				},

				{

					"value": "Oil",

					"is_intensity": 0

				},

				{

					"value": "Any other",

					"is_intensity": 0

				},

				{

					"value": "None",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "Can you assess <b>specific</b> element(s) from within the multi bases (main ingredient) which enhances the flavor experience?",

			"subtitle": "If selected \"Yes\" then please mention identified element(s) it in the comment box.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 0,

			"option": [{

					"value": "Yes",

					"is_intensity": 0

				},

				{

					"value": "Can\'t say",

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

	


"TEXTURE": [{

			"title": "Take sufficient quantity of the product, bite the product just once then identify the sound and its intensity. Which prominent sound do you hear?",

			"subtitle": "Crispy - One sharp, clean, fast, and high pitched sound. Eg., Chips.\nCrunchy - Multiple low pitched crushing sounds perceived as a series of small events. Eg., Rusks.\nCrackly - One sudden low pitched sound that brittles the product. Eg., Puffed rice. ",

			"select_type": 2,

			"is_nested_question": 0,

			"is_nested_option": 0,

			"is_mandatory": 1,

			"is_intensity": 0,

			"option": [{

					"value": "Crispy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Crunchy",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Crackly",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "No Sound",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "While chewing, how fast does the product dissolve (melt-in-the-mouth)?",

			"subtitle": "Please chew the product 3 times and pause.",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Dissolves quickly",

					"is_intensity": 0

				},

				{

					"value": "Dissolves moderately",

					"is_intensity": 0

				},

				{

					"value": "Dissolves slowly",

					"is_intensity": 0

				},

				{

					"value": "Doesn\'t dissolve (But crumbles)",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "How much force is needed to chew the product?",

			"select_type": 1,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Barely any force",

					"is_intensity": 0

				},

				{

					"value": "Normal force",

					"is_intensity": 0

				},

				{

					"value": "Extra force",

					"is_intensity": 0

				}

			]

		},

		{

			"title": "As you chew, what do you experience inside the mouth?",

			"is_nested_question": 0,

			"is_intensity": 0,

			"is_nested_option": 0,

			"is_mandatory": 1,

			"select_type": 2,

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

			"title": "While chewing, which textures can you feel inside your mouth?",

			"subtitle": "Please select a maximum of 4 options.",

			"select_type": 2,

			"is_intensity": 0,

			"is_nested_question": 0,

			"is_mandatory": 1,

			"option": [{

					"value": "Soft",

					"is_intensity": 0

				},

				{

					"value": "Tender",

					"is_intensity": 0

				},

				{

					"value": "Lumpy",

					"is_intensity": 0

				},

				{

					"value": "Chewy",

					"is_intensity": 0

				},

				{

					"value": "Chunky",

					"is_intensity": 0

				},

				{

					"value": "Spongy",

					"is_intensity": 0

				},

				{

					"value": "Pasty",

					"is_intensity": 0

				},

				{

					"value": "Fibrous",

					"is_intensity": 0

				},

				{

					"value": "Mushy",

					"is_intensity": 0

				},

				{

					"value": "Rubbery",

					"is_intensity": 0

				},

				{

					"value": "Sticky",

					"is_intensity": 0

				},

				{

					"value": "Stringy",

					"is_intensity": 0

				},

				{

					"value": "Coarse",

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

			"title": "What kind of mass is being formed?",

			"subtitle": "Take sufficient quantity of the product, chew it for minimum 7 times and pause.",

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

			"title": "After swallowing the product, what do you feel is left inside the mouth?",

			"is_nested_question": 0,

			"is_intensity": 0,

			"is_nested_option": 0,

			"is_mandatory": 1,

			"select_type": 2,

			"option": [{

					"value": "Oily film",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Loose particles",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},

				{

					"value": "Sticking on tooth / palate",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "Stuck between tooth",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
{

					"value": "Chalky",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

				},
				{

					"value": "No residue",

					"is_intensity": 0

				},
				{

					"value": "Any other",

					"is_intensity": 1,

					"intensity_type": 2,

					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

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

					"value": "Aromatics To Flavors",

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

        $data = ['name'=>'salty_snacks_namkeen_(Haldiram)','keywords'=>"salty_snacks_namkeen_(Haldiram)",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
