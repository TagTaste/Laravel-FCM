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

            ['header_name'=>"INSTRUCTIONS"],

            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the section."]],

            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aroma/s (odor/s) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone. Any attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the section."]],

            ['header_name'=>"TASTE","header_info"=> ["text" => "Take 3 french fries and eat; assess the taste/s.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."]],

            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Unlike aromas, aromatics are the odor/s that reach the sensors of the nose from inside the mouth ( reverse action).\nReverse Action - As we eat with our mouth closed, food releases odors. These odors are sensed by us as they travel to the back of the throat and then turn up towards the sensors of the nose.\nPlease take a bite again, eat normally, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odor/s that come from inside the mouth."]],

            ['header_name'=>"ORAL TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is chewed; and it may even last after the product is swallowed. Product may make sound (chips), may give us joy (creamy foods), may even cause pain or disgust (sticky/slimy foods)."]],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."]]

        ];

        $questions2 = '{
 
  "INSTRUCTIONS": [
    {
      "title": "Instruction",
      "subtitle": "Please follow the questionnaire and select the answers that match with your observations.\nRemember, there are no right or wrong answers.\nAnything that stands out as either too good or too bad, may please be highlighted in the comments box.",
      "select_type": 4
    }
  ],
 
  "APPEARANCE": [
    {
      "title": "At what temperature has the product been served? You may also touch and confirm the temperature.",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": [
 
				{
 
					"value": "Below room temperature",
					"is_intensity": 0
				},
				{
					"value": "Room temperature",
					"is_intensity": 0
 
				},
				{
					"value": "Slightly hot",
					"is_intensity": 0
				},
				{
					"value": "Hot",
					"is_intensity": 0
				},
				{
					"value": "Very hot",
					"is_intensity": 0
				},
				{
					"value": "Burning hot",
					"is_intensity": 0
				}
 
			]
    },
    {
      "title": "What is the color of the crust? If you select \"Any other\" option then please mention it in the comment box.",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": [
 
				{
 
					"value": "Hay",
					"is_intensity": 0
				},
				{
					"value": "Straw",
					"is_intensity": 0
 
				},
				{
					"value": "Golden",
					"is_intensity": 0
				},
				{
					"value": "Yellow",
					"is_intensity": 0
				},
				{
					"value": "Copper",
					"is_intensity": 0
				},
				{
					"value": "Bronze",
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
      "title": "Considering the product served, is there any variation in the color?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": [
 
				{
 
					"value": "Uniform",
					"is_intensity": 0
				},
				{
					"value": "Non uniform",
					"is_intensity": 0
 
				}
 
			]
    },
    {
      "title": "How does the color appear?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": [
        {
					"value": "Bright",
					"is_intensity": 0
				},
				{
					"value": "Dull",
					"is_intensity": 0
 
				},{
 
					"value": "Shiny",
					"is_intensity": 0
				},
				{
					"value": "Dehydrated",
					"is_intensity": 0
 
				},
				{
					"value": "Oily",
					"is_intensity": 0
				},
				{
					"value": "Soggy",
					"is_intensity": 0
 
				},{
 
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
      "title": "Considering the product served, what is your perception about its length?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
     "option": [
 
				{
 
					"value": "Too long",
					"is_intensity": 0
				},
				{
					"value": "Too short",
					"is_intensity": 0
 
				},
				{
 
					"value": "Appropriate",
					"is_intensity": 0
				}
				
			]
    },
    {
      "title": "Considering the product served, what is your perception about its thickness?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
       "option": [
 
				{
 
					"value": "Too thin",
					"is_intensity": 0
				},
				{
					"value": "Too thick",
					"is_intensity": 0
 
				},
				{
 
					"value": "Appropriate",
					"is_intensity": 0
				}
			]
    },
    {
      "title": "Did you experience any off appearance? If yes, then please answer the questions given below.",
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
      "title": "Did you find any of these irregularities on the surface of the served product?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0,
       "option": [
 
				{
 
					"value": "Blisters",
					"is_intensity": 0
				},
				{
					"value": "Blemishes",
					"is_intensity": 0
 
				},
				{
 
					"value": "Dark ends",
					"is_intensity": 0
				},
				{
					"value": "Black spots",
					"is_intensity": 0
 
				},
				{
					"value": "Peels",
					"is_intensity": 0
 
				},
				{
 
					"value": "Ruptured crust",
					"is_intensity": 0
				}
 
			]
    },
    {
      "title": "Which of these irregular sizes did you spot?",
      "subtitle": "(Slivers - Short and narrow; Nubbins - Lumps)",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0,
       "option": [
 
				{
 
					"value": "Slivers",
					"is_intensity": 0
				},
				{
					"value": "Nubbins",
					"is_intensity": 0
 
				},
				{
 
					"value": "Off cut",
					"is_intensity": 0
				},
				{
					"value": "Feathered edges",
					"is_intensity": 0
 
				},{
 
					"value": "Shattered",
					"is_intensity": 0
				},
				{
					"value": "Broken",
					"is_intensity": 0
 
				},{
 
					"value": "Ragged cuts",
					"is_intensity": 0
				},
				{
					"value": "Side cuts",
					"is_intensity": 0
 
				}
 
			]
    },
    {
      "title": "Press a single french fries between index finger and thumb with moderate force. How springy is the product?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option":[
        {
          	"value": "Deforms and bounces back",
					"is_intensity": 0
        },
        {
          	"value": "Collapses",
					"is_intensity": 0
        },
        {
          	"value": "Tears",
					"is_intensity": 0
        },
        {
          	"value": "Hard",
					"is_intensity": 0
        }
      ]
    },
    {
      "title": "How does the centre of the product appear?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
       "option":[
        {
          	"value": "Hollow",
					"is_intensity": 0
        },
        {
          	"value": "Raw",
					"is_intensity": 0
        },
        {
          	"value": "Cooked",
					"is_intensity": 0
        },
        {
          	"value": "Dense",
					"is_intensity": 0
        },
        {
          	"value": "Dry",
					"is_intensity": 0
        },
        {
          	"value": "Fluffy",
					"is_intensity": 0
        },
        {
          	"value": "Moist",
					"is_intensity": 0
        },
        {
          	"value": "Mushy",
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
 
			"option": [
 
				{
 
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
  
 
  "AROMA": [
    {
      "title": "What all aroma/s did you observe?",
      "subtitle": "Directly use the search box to select the aroma/s that you observed or follow the category based aroma list. In case you can\'t find the observed aroma/s, select \"Any other\" and if unable to sense any aroma/s at all, then select  \"Absent\".",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "is_nested_option": 1,
      "nested_option_title": "AROMAS",
      "nested_option_list": "AROMA"
    },
    {
      "title": "If you experienced any Off (bad)- aroma, then please identify it from the list.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
      "nested_option_title": "OFF-AROMA",
      "nested_option_list": "OFFAROMA"
    },
    {
 
			"title": "Overall Preference",
 
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
 
  "TASTE": [
    {
      "title": "Which Basic taste/s did you observe?",
      "is_nested_question": 0,
			"is_intensity": 0,
			"is_nested_option": 0,
			"is_mandatory": 1,
			"select_type": 2,
      	"option": [
 
				{
 
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
					"intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
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
 
      "title": "Which Ayurvedic taste/s did you observe?",
 
			"select_type": 2,
			"is_intensity": 0,
			"is_mandatory": 1,
 
			"is_nested_question": 0,
 
			"is_nested_option": 0,
 
			"option": [
 
				{
					"value": "Astringent (Dryness)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
				},
				{
					"value": "Pungent (Spices / Garlic)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
 
				},
				{
					"value": "Pungent Cool Sensation (Mint)",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
 
				},
				{
					"value": "Pungent Chilli",
					"is_intensity": 1,
					"intensity_type": 2,
					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
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
 
			"option": [
 
				{
 
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
  
 
  "AROMATICS TO FLAVORS": [
    {
      "title": "What all aromatics did you observe?",
      "subtitle": "Directly use the search box to select the aromatics that you observed or follow the category based aromatics list. In case you can\'t find the observed aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select  \"Absent\".",
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
      "title": "If you experienced any off (bad)- aromatics, then please identify it from the list.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
      "nested_option_title": "OFF-AROMATICS",
      "nested_option_list": "OFFAROMA"
    },
    {
      "title": "Aftertaste",
      "subtitle": "Please chew and swallow the product. Assess the sensation inside your mouth.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "How was the aftertaste? ",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": [
 
				{
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
          "title": "Length of the aftertaste?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": [
 
				{
					"value": "None",
					"is_intensity": 0
 
				},
				{
					"value": "Short",
					"is_intensity": 0
				},
				{
					"value": "Sufficient",
					"is_intensity": 0
				},
				{
					"value": "Long",
					"is_intensity": 0
				}
			]
        }
      ]
    },
    {
      "title": "Flavor",
      "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together. Usually, taste has a lesser contribution and aromatics on the other hand has a greater contribution towards the development of the flavor.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "How was the flavor experience?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": [
 
				{
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
          "title": "Specific to Potato which prominent flavor did you identify? ",
          "subtitle": "If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the comment box.",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
  "option": [
 
				{
					"value": "Cooked",
					"is_intensity": 0
 
				},
				{
					"value": "Boiled",
					"is_intensity": 0
				},
				{
					"value": "Roasted",
					"is_intensity": 0
				},
				{
					"value": "Raw",
					"is_intensity": 0
				},
				{
					"value": "Baked",
					"is_intensity": 0
				},
				{
					"value": "Freshly Fried",
					"is_intensity": 0
				},
				{
					"value": "Fried",
					"is_intensity": 0
				},
				{
					"value": "None",
					"is_intensity": 0
				}
			]
        }
      ]
    },
    {
 
			"title": "Overall Preference",
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
 
  "ORAL TEXTURE": [
    {
      "title": "Place 3 french fries between the lips. How would you describe the surface texture?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
       "option": [
 
				{
					"value": "Dry",
					"is_intensity": 0
 
				},
				{
					"value": "Moist",
					"is_intensity": 0
				},
				{
					"value": "Oily",
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
					"value": "Crystals",
					"is_intensity": 0
				},
				{
					"value": "Sticky",
					"is_intensity": 0
				}
			]
    },
    {
      "title": "Now go ahead and bite (only once) into 3 french fries at the same time. Identify the sound and its intensity. What was the sound like?", 
      "subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events,e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product,e.g., Puffed rice.",
      "select_type": 2,
      "is_nested_question": 0,
      "is_mandatory": 1,
			"is_intensity": 0,
			"option": [
 
				{
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
      "title": "How did the french fries break into pieces during the first bite (fracturability)?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
        "option": [
 
				{
					"value": "Cut instantly",
					"is_intensity": 0
 
				},
				{
					"value": "Cut reluctantly",
					"is_intensity": 0
				},
				{
					"value": "Ruptured",
					"is_intensity": 0
				},
				{
					"value": "Hard",
					"is_intensity": 0
				}
			]
    },
    {
      "title": "First Chew",
      "subtitle": "Chew the product for 3-4 times and pause.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Assess the moistness (moisture release) inside your mouth. What did you feel?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
            "option": [
 
				{
					"value": "Dehydrated",
					"is_intensity": 0
 
				},
				{
					"value": "Dry",
					"is_intensity": 0
				},
				{
					"value": "Slightly moist",
					"is_intensity": 0
				},
				{
					"value": "Moist",
					"is_intensity": 0
				},
					{
					"value": "Succulent",
					"is_intensity": 0
				}
			]
        },
        {
          "title": "Assess the solids inside the mouth? What did you feel?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": [
 
				{
					"value": "Pasty",
					"is_intensity": 0
 
				},
				{
					"value": "Mushy",
					"is_intensity": 0
				},
				{
					"value": "Fluffy",
					"is_intensity": 0
				},
				{
					"value": "Chewy",
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
        }
      ]
    },
    {
      "title": "Chew down",
      "subtitle": "Chew the product again for 8-10 times to make a pulp and pause.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Was there any variation in the force required to chew the product?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": [
 
				{
					"value": "Uniform force",
					"is_intensity": 0
 
				},
				{
					"value": "Non uniform force",
					"is_intensity": 0
				}
			]
        },
        {
          "title": "While chewing, how much saliva was absorbed by the product?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
            "option": [
				
				{
					"value": "Barely",
					"is_intensity": 0
				},
				{
					"value": "Very Less",
					"is_intensity": 0
				},
				{
					"value": "Less",
					"is_intensity": 0
				},
					{
					"value": "Moderate",
					"is_intensity": 0
				},
				{
					"value": "Plenty",
					"is_intensity": 0
				},
				{
					"value": "High",
					"is_intensity": 0
				},
				{
					"value": "Extremely High",
					"is_intensity": 0
				}
			]
        },
        {
          "title": "During chewing, was the pulp formed or did the mass scatter?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": [
 
				{
					"value": "Dissolved",
					"is_intensity": 0
 
				},
				{
					"value": "Scattered",
					"is_intensity": 0
				},
				{
					"value": "Pulpy mass",
					"is_intensity": 0
				},
				{
					"value": "Tightly bonded",
					"is_intensity": 0
				}
			]
        },
        {
          "title": "Is the product sticking on the palate or teeth?",
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
          "title": "If yes, then how much Force was needed to remove the product from the palate or teeth?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
           "option": [
 
				{
					"value": "Barely",
					"is_intensity": 0
 
				},
				{
					"value": "Weak",
					"is_intensity": 0
				},
				{
					"value": "Mild",
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
					"value": "Very Intense",
					"is_intensity": 0
				},
				{
					"value": "Extremely Intense",
					"is_intensity": 0
				}
			]
        }
      ]
    },
    {
      "title": "Residual",
      "subtitle": "After swallowing what is the feeling inside the mouth.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Do you feel anything left in the mouth?",
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
          "title": "If yes, then what was left in your mouth?",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
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
					"value": "Toothpack",
					"is_intensity": 0
				}
			]
        }
      ]
    },
    {
 
			"title": "Overall Preference",
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
      "title": "Which part of the french fries did you enjoy the most?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": [
 
				{
					"value": "Outer crispy layer",
					"is_intensity": 0
 
				},
				{
					"value": "Inner cooked potato",
					"is_intensity": 0
				},
				{
					"value": "Salty crystals",
					"is_intensity": 0
 
				},
				{
					"value": "Seasoning",
					"is_intensity": 0
				},
				{
					"value": "None",
					"is_intensity": 0
 
				}
				
			]
    },
    {
      "title": "Does any attribute/s need/s any improvement?",
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
      "title": "If yes, which attribute/s needs improvement? Elaborate in the comment box.",
      "select_type": 2,
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
 
			"title": "Overall Product Preference",
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

        $data = ['name'=>'French Fries 24/Jan (For Private review final)','keywords'=>"french fries",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
