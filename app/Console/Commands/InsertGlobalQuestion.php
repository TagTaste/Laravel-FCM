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


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are only assessing the aromas (odors through the nose), so please don't drink it yet. Now bring the product closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone."],'header_selection_type'=>"1"],


        ['header_name' => "TASTE","header_info" => ["text" => "Slurp noisily and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],


        ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Slurp noisily again, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odor/s that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



        ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we drink."],'header_selection_type'=>"1"],



    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Acidity, Aftertaste, Flavor and Body; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]


    ];

        $questions2 = '{
    "INSTRUCTIONS": [{
        "title": "Instruction",
        "subtitle": "<b>Welcome to the Product Review!</b>\n\nIf a product involves stirring, shaking etc (like cold coffee) then the taster must follow the instructions fully, as mentioned on the packaging.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        "select_type": 4
    }],
    
    "APPEARANCE": [{
            "title": "What is the serving temperature of the product?",
            "subtitle": "You may also take a sip to assess the serving temperature.",
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
                    "value": "Steaming hot",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the visual impression (color & hue) of the product?",
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
                    "value": "Greasy",
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
            "title": "What is your view about the toppings on the product?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Less still appealing",
                    "is_intensity": 0
                },
                {
                    "value": "Less & unappealing",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced",
                    "is_intensity": 0
                },
                {
                    "value": "Excess still appealing",
                    "is_intensity": 0
                },
                {
                    "value": "Excess & unappealing",
                    "is_intensity": 0
                },
                {
                    "value": "No toppings",
                    "is_intensity": 0
                },
                {
                    "value": "Not applicable",
                    "is_intensity": 0

                }
            ]
        },
        {
            "title": "How is the visual texture of the product?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Silky",
                    "is_intensity": 0
                },
                {
                    "value": "Frothy",
                    "is_intensity": 0
                },
                {
                    "value": "Bubbly",
                    "is_intensity": 0
                },
                {
                    "value": "Sediments",
                    "is_intensity": 0
                },
                {
                    "value": "Syrupy",
                    "is_intensity": 0
                },
                {
                    "value": "Water separated",
                    "is_intensity": 0
                },
                {
                    "value": "Weak",
                    "is_intensity": 0
                },
                {
                    "value": "Strong",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                    "is_intensity": 0,
                    "option_type": 1
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
            "subtitle": "Directly use the search box to select the aromas that you identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\".",
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
            "title": "Which Basic Tastes have you sensed?",
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
                    "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Which Ayurvedic Tastes have you sensed?",
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
                    "is_intensity": 0,
                    "option_type": 2
                    
                }
            ]
        },
        {
            "title": "How acidic is the coffee?",
            "subtitle": "Coffee without acids is \"flat\" and with acids can be \"bright with a pop\" or undesirably \"sour\".",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Flat",
                    "is_intensity": 0
                },
                {
                    "value": "Bright",
                    "is_intensity": 0
                },
                {
                    "value": "Winey",
                    "is_intensity": 0
                },
                {
                    "value": "Lime (Sour)",
                    "is_intensity": 0
                },
                {
                    "value": "Fermented",
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
            "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\". ",
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
            "title": "Please ingest the product and pause. How is the aftertaste?",
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
            "title": "How best would you describe your coffee?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Enjoyably Bitter",
                    "is_intensity": 0
                },
                {
                    "value": "Sharp (High kick)",
                    "is_intensity": 0
                },
                {
                    "value": "Bright (Medium kick)",
                    "is_intensity": 0
                },
                {
                    "value": "Mellow (Low kick)",
                    "is_intensity": 0
                },
                {
                    "value": "Milky",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                    "is_intensity": 0,
                     "option_type": 1

                },
                {
                    "value": "None",
                    "is_intensity": 0,
                     "option_type": 2

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
            "title": "How is the mouthfeel of the product?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Refreshing",
                    "is_intensity": 0
                },
                {
                    "value": "Smooth",
                    "is_intensity": 0
                },
                {
                    "value": "Foamy",
                    "is_intensity": 0
                },
                {
                    "value": "Velvety",
                    "is_intensity": 0
                },
                {
                    "value": "Bitting",
                    "is_intensity": 0
                },
                {
                    "value": "Heavy",
                    "is_intensity": 0
                },
                {
                    "value": "Weak",
                    "is_intensity": 0
                },
                {
                    "value": "Gritty (Sediments)",
                    "is_intensity": 0
                },
                {
                    "value": "Chunky (Toppings)",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                    "is_intensity": 0,
                    "option_type": 1
                }
            ]
        },
        {
            "title": "How would you describe body and smoothness of the coffee?",
            "subtitle": "Body - refers to the heaviness (thinness/ thickness) of texture of the coffee.\nSmoothness - refers to the levels of fat suspended in the coffee.",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Watery",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Milky",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Creamy",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
            ]
        },
        {
            "title": "To what extent is your mouth coated? text update",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Barely any",
                    "is_intensity": 0
                },
                {
                    "value": "Less",
                    "is_intensity": 0
                },
                {
                    "value": "Sufficient",
                    "is_intensity": 0
                },
                {
                    "value": "Little extra",
                    "is_intensity": 0
                },
                {
                    "value": "Excess",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "After ingesting the product, do you feel anything left inside the mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Greasy film",
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
                    "value": "Stuck between tooth",
                    "is_intensity": 0
                },
                {
                    "value": "Chalky",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                    "is_intensity": 0,
                    "option_type": 1
                },
                {
                    "value": "No residue",
                    "is_intensity": 0,
                    "option_type": 2
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
    
    "PRODUCT EXPERIENCE": [
      {
            "title": "Is your coffee balanced or not? If not so, then which attribute(s) are causing imbalance and thus need improvement?",
            "subtitle": "In a good coffee 4 basic elements (Acidity, Aftertaste, Flavor and Body) should be in Balance.",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Balanced",
                    "is_intensity": 0
                },
                {
                    "value": "Acidity",
                    "is_intensity": 0
                },
                {
                    "value": "Aftertaste",
                    "is_intensity": 0
                },
                {
                    "value": "Flavors",
                    "is_intensity": 0
                },
                {
                    "value": "Body",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How would you describe the \"serve size\" of this product?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Generous",
                    "is_intensity": 0
                },
                {
                    "value": "Modest",
                    "is_intensity": 0
                },
                {
                    "value": "Limited",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Overall product preference",
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
            "is_mandatory": 1,
            "is_nested_question": 0
        }
    ]
}';

        $data = ['name'=>'Private_Review_Coffee_Hot/Cold_2020','keywords'=>"Private_Review_Coffee_Hot/Cold_2020",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);


    }
}