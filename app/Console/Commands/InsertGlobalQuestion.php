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


            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Take a slice of bread & place it on a white plate. Examine the product visually and answer the questions outlined below.\nIn this tasting process, the top and bottom most slice should be excluded."],'header_selection_type'=>"1"],


            ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."],'header_selection_type'=>"1"],


            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],


            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odor/s that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],



            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]


        ];

        $questions2 = '{
	"INSTRUCTIONS": [{
		"title": "Instruction",
		"subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\nRemember, there are no right or wrong answers. ",
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
			"title": "How is the visual impression (color and sheen) of the product (bread)?",
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
			"title": "What is the colour of the crumb?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "White",
					"is_intensity": 0
				},
				{
					"value": "Off white",
					"is_intensity": 0
				},
				{
					"value": "Cream",
					"is_intensity": 0
				},
				{
					"value": "Yellow",
					"is_intensity": 0
				},
				{
					"value": "Beige",
					"is_intensity": 0
				},
				{
					"value": "Golden",
					"is_intensity": 0
				},
				{
					"value": "Light golden",
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
			"title": "Where do you find the ingredient(s) (e.g., oats, seeds etc.) and what is there quantity?",
			"subtitle": "Crumb (inner part of the bread)",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [{
					"value": "Crust",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Crumb",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "None",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "Does the presence of visible ingredient/s on the crumb (inner part of the bread) matter to you?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Yes",
					"is_intensity": 0
				},
				{
					"value": "Doesn\'t matter",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "How does the shape of the product (slice of bread) appear to you?",
			"subtitle": "Dome - Arch shape",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "All sides uniform",
					"is_intensity": 0
				},
				{
					"value": "Slightly domed",
					"is_intensity": 0
				},
				{
					"value": "Excessively domed",
					"is_intensity": 0
				},
				{
					"value": "Collapsed",
					"is_intensity": 0
				},
				{
					"value": "Broken",
					"is_intensity": 0
				},
				{
					"value": "Curvy sides",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "While touching the product, how does the product feel?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Soft",
					"is_intensity": 0
				},
				{
					"value": "Moist",
					"is_intensity": 0
				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{
					"value": "Sticky",
					"is_intensity": 0
				},
				{
					"value": "Rough",
					"is_intensity": 0
				},
				{
					"value": "Smooth",
					"is_intensity": 0
				},
				{
					"value": "Loose particles",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "Observe the size & arrangement of cells in the crumb (inner part of the bread). What do you observe?",
			"subtitle": "Variation - Mix of small & big cells",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Same all over",
					"is_intensity": 0
				},
				{
					"value": "Variation",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "What do observe about the crumb cells (inner part of the bread)?",
			"subtitle": "Fine - Sponge- like with small cells, closely packed.\nOpen - Sponge- like with large cells ( same or different sizes), loosely packed.\nPorous - Small holes through which air or liquid may pass.\nTunnel - A large hole, forming a passage through which even solid can pass.",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [{
					"value": "Fine cells",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Open cells",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Porous holes",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Tunnel",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Raw dough",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "All of them",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				},
				{
					"value": "Any other",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
				}
			]
		},
		{
			"title": "Slide your forefinger on the surface of the crumb to imitate spreading action of a knife while applying the butter. How is your spreading experience?",
			"select_type": 2,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "Spreads easily",
					"is_intensity": 0
				},
				{
					"value": "Sticky (Doesn\'t spread)",
					"is_intensity": 0
				},
				{
					"value": "Chunky particles (Doesn\'t spread)",
					"is_intensity": 0
				},
				{
					"value": "Crumbly (Doesn\'t spread)",
					"is_intensity": 0
				},
				{
					"value": "Breaks (Doesn\'t spread)",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "Overall Preference of Appearance",
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
			"title": "Overall Preference of Aroma",
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
			"title": "Overall Preference of Taste",
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
			"title": "Overall Preference of Aromatics",
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
			"title": "How much force is needed to chew the product?",
			"subtitle": "Please chew the product 3-4 times and pause.",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_mandatory": 1,
			"select_type": 1,
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
			"title": "As you chew, what do you prominently feel inside the mouth?",
			"select_type": 2,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested_question": 0,
			"is_nested_option": 0,
			"option": [{
					"value": "Oil / Butter",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Moisture",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
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
			"select_type": 2,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested_question": 0,
			"is_nested_option": 0,
			"option": [{
					"value": "Soft",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Spongy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Lumpy",
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
					"value": "Chewy",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
				},
				{
					"value": "Rough",
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
					"value": "Any other",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
				}
			]
		},
		{
			"title": "What kind of mass is being formed?",
			"subtitle": "Take sufficient quantity of the product, chew it for minimum 8-10 times and pause.",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_mandatory": 1,
			"select_type": 1,
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
			"title": "Is this product difficult to swallow?",
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
			"title": "After swallowing the product, do you feel anything left inside the mouth?",
			"is_nested_question": 0,
			"is_intensity": 0,
			"is_mandatory": 1,
			"select_type": 2,
			"option": [{
					"value": "Oily film",
					"is_intensity": 0
				},
				{
					"value": "Loose particles",
					"is_intensity": 0
				},
				{
					"value": "Sticking on tooth / palate",
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
			"title": "Overall Preference of Texture",
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
			"title": "Will you like to buy this product from the market?",
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
				},
				{
					"value": "Uncertain",
					"is_intensity": 0
				}
			]
		},
		{
			"title": "How much would you like to pay <b>(in rupees)</b> for the entire packet of bread?",
			"select_type": 1,
			"is_intensity": 0,
			"is_nested_question": 0,
			"is_mandatory": 1,
			"option": [{
					"value": "30",
					"is_intensity": 0
				},
				{
					"value": "35",
					"is_intensity": 0
				},
				{
					"value": "40",
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
			"placeholder": "In your opinion, other than honey and oats which ingredients can make the white bread healthy. Please name at least 2 ingredients.",
			"select_type": 3,
			"is_intensity": 0,
			"is_mandatory": 1,
			"is_nested_question": 0
		}
	]
}';

        $data = ['name'=>'Private _honey oats bread_modern','keywords'=>"Private _honey oats bread_modern",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}