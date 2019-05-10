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


            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Empty the package on a white plate. Examine the product visually and answer the questions outlined below. "]],

            ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."]],

            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."]],


            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Unlike aromas, aromatics are the odors that reach the sensors of the nose from inside the mouth (reverse action).\nReverse Action - As we eat with our mouth closed, food releases odors. These odors are sensed by us as they travel to the back of the throat and then turn up towards the sensors of the nose.\n\nPlease take a bite again, eat normally, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth."]],


            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."]],


            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."]]


        ];
        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "Welcome to the Product Review!\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
		"select_type": 4
	}],
	"APPEARANCE": [{
			"title": "What is the serving temperature of the product? ",
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
			"title": "Which prominent coating do you observe on the product?",
			"subtitle": "If you select \"Any other\", then please mention it in the <b>comment box</b>.",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Spice mix",
					"is_intensity": 0
				},
				{
					"value": "Sugar crystals",
					"is_intensity": 0
				},
				{
					"value": "Salt",
					"is_intensity": 0
				},
				{
					"value": "Herbs",
					"is_intensity": 0
				},
				{
					"value": "Any other",
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
					"value": "Firm",
					"is_intensity": 0
				},
				{
					"value": "Limp",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "What is the prominent impression about the coating?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Even coating",
					"is_intensity": 0
				},
				{
					"value": "Missed coating",
					"is_intensity": 0
				},
				{
					"value": "Clumpy Coating",
					"is_intensity": 0
				},
				{
					"value": "Loose particles",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "Using fingers, hold the pieces for few seconds and put them back on the plate. What do you observe on your fingers?",
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
					"value": "Sticky film (Non oily)",
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
			"title": "Overall Preference ",
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
			"title": "Overall Preference ",
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
			"title": "Which promnient flavor do you feel in the product?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Veggies",
					"is_intensity": 0
				},
				{
					"value": "Spice Mix",
					"is_intensity": 0
				},
				{
					"value": "Oil",
					"is_intensity": 0
				}
			]
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
		}
	],
	"TEXTURE": [{
			"title": "Take sufficient quantity of the product, bite the product just once then identify the sound and its intensity. Which prominent sound do you hear?",
			"subtitle": "Crispy- one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events,e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product,e.g., Puffed rice.",
			"select_type": 1,
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
			"title": "How much force is needed to chew the product? ",
			"subtitle": "Please chew the product 3-4 times and pause.",
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
			"title": "As you chew, what is prominently being released from the product?",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 1,
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
					"value": "Pasty",
					"is_intensity": 0
				},
				{
					"value": "Mushy",
					"is_intensity": 0
				},
				{
					"value": "Chewy",
					"is_intensity": 0
				},
				{
					"value": "Fibrous",
					"is_intensity": 0
				},
				{
					"value": "Spongy",
					"is_intensity": 0
				},
				{
					"value": "Rubbery",
					"is_intensity": 0
				},
				{
					"value": "Coarse",
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
			"subtitle": "Take sufficient quantity of the product, chew it for minimum 8-10 times and pause.",
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
			"title": " After swallowing the product, do you feel anything left inside the mouth ?",
			"subtitle": "If you select \"Any other\", then please mention it in the <b>comment box.</b>",
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
					"value": "Sticking on palate",
					"is_intensity": 0
				},
				{
					"value": "Chalky",
					"is_intensity": 0
				},
				{
					"value": "No residue",
					"is_intensity": 0
				},
				{
					"value": "Any other",
					"is_intensity": 0
				}
			]
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
			"is_mandatory": 0,
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

        $data = ['name'=>'Salty Snacks _Veggie Snacks_V1','keywords'=>"Salty Snacks _Veggie Snacks_V1",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
