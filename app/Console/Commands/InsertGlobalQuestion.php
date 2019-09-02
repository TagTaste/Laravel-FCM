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
            ['header_name' => "APPEARANCE", "header_info" => ["text" => "Empty the package in a white bowl. Examine the product and answer the questions outlined below."]],
            ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."]],
            ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."]],
            ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these identified odors are called Aromatics."]],
            ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."]],
            ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."]]
        ];

        $questions2 = '{

    



"INSTRUCTIONS": [{

        "title": "Instruction",

        "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b>.\n\nRemember, there are no right or wrong answers. ",

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

            "title": "How does the visual texture of the product appear to you?",

            "select_type": 2,

            "is_intensity": 0,

            "is_nested_question": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Smooth",

                    "is_intensity": 0

                },

                {

                    "value": "Silky",

                    "is_intensity": 0

                },

                {

                    "value": "Seed awareness",

                    "is_intensity": 0

                },

                {

                    "value": "Skin awareness",

                    "is_intensity": 0

                },

                {

                    "value": "Fibrous",

                    "is_intensity": 0

                }
            ]

        },
{

            "title": "How does the product drop / flow from the spoon? ",

            "subtitle": "Take a teaspoonful of the product and tilt it slightly.",

            "select_type": 1,

            "is_intensity": 0,

            "is_nested_question": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Does not drop",

                    "is_intensity": 0

                },

                {

                    "value": "Flows reluctantly",

                    "is_intensity": 0

                },

                {

                    "value": "Flows slowly",

                    "is_intensity": 0

                },

                {

                    "value": "Flows moderately",

                    "is_intensity": 0

                },

                {

                    "value": "Flows quickly",

                    "is_intensity": 0

                },

                {

                    "value": "Flows slightly faster",

                    "is_intensity": 0

                },

                {

                    "value": "Flows freely (water)",

                    "is_intensity": 0

                }

            ]

        },
        {

            "title": "How is the consistency of the product?",

            "select_type": 1,

            "is_intensity": 0,

            "is_nested_question": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Homogenous",

                    "is_intensity": 0

                },

                {

                    "value": "Water separated",

                    "is_intensity": 0

                },

                {

                    "value": "Pulpy",

                    "is_intensity": 0

                },

                {

                    "value": "Lumpy (clots)",

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

            "title": "While eating, how does the product feel inside the mouth? ",
            "subtitle": "Please eat one teaspoon of the product and assess.",

            "select_type": 1,

            "is_intensity": 0,

            "is_nested_question": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Like water",

                    "is_intensity": 0

                },

                {

                    "value": "Like toned milk",

                    "is_intensity": 0

                },

                {

                    "value": "Like full cream milk",

                    "is_intensity": 0

                },
                {

                    "value": "Like honey",

                    "is_intensity": 0

                },

                {

                    "value": "Like condensed milk",

                    "is_intensity": 0

                },
                    {

                    "value": "Like puree (tomato)",

                    "is_intensity": 0

                },
                {

                    "value": "Like paste (peanut butter)",

                    "is_intensity": 0

                }
                
            ]

        },
        {

            "title": "While eating, which textures can you experience inside your mouth?",

            "subtitle": "Please select a maximum of 3 options.",

            "select_type": 2,

            "is_intensity": 0,

            "is_nested_question": 0,
            
            "is_nested_option": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Smooth",

           "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"

                },

                {

                    "value": "Silky",

                    "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


                },

                {

                    "value": "Soft",

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

                    "value": "Gritty",

                    "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


                },

                {

                    "value": "Seeds",

                    "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


                },
                {

                    "value": "Fibre",

                    "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


                },

                {

                    "value": "Skin",

                    "is_intensity": 1,

                    "intensity_type": 2,

                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"


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

            "title": "How fast does the product melt-in-the-mouth?",
            "subtitle": "Compress half a teaspoon of the product between the tongue and the palate. Please don\'t swallow the product yet.",

            "select_type": 2,

            "is_intensity": 0,

            "is_nested_question": 0,

            "is_mandatory": 1,

            "option": [{

                    "value": "Melts quickly",

                    "is_intensity": 0

                },

                {

                    "value": "Melts moderately",

                    "is_intensity": 0

                },

                {

                    "value": "Melts slowly",

                    "is_intensity": 0

                },
                {

                    "value": "Doesn\'t melt",

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

        $data = ['name'=>'Condiments - Tamarind (Sauth)','keywords'=>"Condiments - Tamarind (Sauth)",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);


    }
}
