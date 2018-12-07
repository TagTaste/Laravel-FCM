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

   ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the product, e.g., color, surface appearance, surface texture (without touch and with touch). 
Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

   ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't take a bite yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. Anything that stands out as either too good or too bad, may please be highlighted in the comment box."],

   ['header_name'=>"TASTE","header_info"=>"Take a bite or multiple bites and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section. If you find the product to be bland, please mention in the comment box. "],

   ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Odour through the mouth. Take a bite again eat normally keeping your mouth closed and exhale through the nose. Identify the odours sensed using the aroma list and the search option. Anything too good or too bad observed please highlight in the comment box at the end of the section."],

   ['header_name'=>"ORAL TEXTURE","header_info"=>"How the product feels inside the mouth (oraI texture) affects our enjoyment. Take a bite again but proceed only as per the instructions mentioned in the sub section.
 "],

   ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]
];



        $questions2 = '{
  "INSTRUCTIONS": [{
    "title": "INSTRUCTION",
    "subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box.\n Anything that stands out as either too good or too bad, may please be highlighted in the comment box.",
    "select_type": 4
  }],
  "APPEARANCE": [{
      "title": "Visual Observation",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "Surface Appearance",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Naturally plain,Spice Coating,Chunky particles,Fine particles,Crystals"
        },
        {
          "title": "Identify the color and mention it in the comment box. (Generic Scale 0-8)",
          "select_type": 1,
          "is_intensity": 1,
          "intensity_type": 2,
          "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Intensity of identified color"
        },
        {
          "title": "About color",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Natural,Artificial,Bright,Dull,Shiny,Glazed,Even,Uneven"
        },
        {
          "title": "Surface texture (without touch)",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Moist,Dry,Loose particles,Rough,Smooth,Melted look"
        }
      ]
    },
    {
      "title": "Surface Texture (With touch)",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "Brittleness ( Hold the product in your hand and try to break it)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No sound,Clear sound,Muffled sound,Flying particles,Sticks on finger,Fun to break,Not fun to break"
        },
        {
          "title": "Any Off Appearance (If yes, describe in comment)",
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
      "subtitle": "(We have a list of more than 500 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use search box to access the aroma list.)",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "is_nested_option": 1,
      "nested_option_list": "AROMA"
    },
    {
      "title": "If you experienced any Off (bad)- aroma, please indicate the intensity.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
      "nested_option_list": "OFFAROMA"
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
          "option": "None,Barely Detectable,Identifiable but not very intense,Slightly Intense,Moderately Intense,Intense,Very Intense,Extremely Intense"
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
          "option": "None,Barely Detectable,Identifiable but not very intense,Slightly Intense,Moderately Intense,Intense,Very Intense,Extremely Intense"
        },
        {
          "title": "Umami",
          "subtitle": "When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste.",
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
      "is_mandatory": 0,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
      "is_nested_question": 0,
      "option": "Astringent (Dryness),Pungent (Spices/Garlic),Pungent Cool Sensation (Mint),Pungent- Chilli"
    },
    {
      "title": "If you were to make your own chocolate what will be the combination of bitter (B) and sweet (S) taste",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "30% B and 70% S,70% Bitter and 30% Sweet,100% Bitter,15% Bitter,10% Bitter and 90% Sweet (Milk Chocolate)"
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
      "title": "Please identify aromatics experienced from the list (you can refer again to aroma section).",
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
      "title": "If you experienced any Off (bad)- aromatics, please indicate the intensity .",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
      "nested_option_list": "OFFAROMA"
    },
    {
      "title": "Aftertaste",
      "subtitle": "Please chew and swallow the product. Assess the sensation inside your mouth.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "How was the aftertaste?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Pleasant,Unpleasant,None"
        },
        {
          "title": "Length of the aftertaste?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "None,Short,Sufficient,Long"
        }
      ]
    },
    {
      "title": "Flavor",
      "subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor/s of the product that you are tasting.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
        "title": "Did you experience any Flavor/s.\n If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the comment box.",
        "select_type": 1,
        "is_intensity": 0,
        "is_nested_question": 0,
        "is_mandatory": 1,
        "option": "No Flavor,Can\'t say,Desirable Flavor,Undesirable Flavor"
      }]
    },
    {
      "title": "Overall preference",
      "subtitle": "Share your overall preference for the Flavor (Concentrate on Aromatics and Taste together)",
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
      "title": "Surface texture- Hold the product between the lips",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Dry,Wet,Oily,Smooth,Ultra Smooth,Rough,Very Rough,Loose particles,Sticky"
    },
    {
      "title": "Please bite into the product and identify the sound/s observed.",
      "subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips \n Crunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks \n Crackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Crispy,Crunchy,Crackly"
    },
    {
      "title": "First Chew",
      "subtitle": "Chew the product for 3-4 times and pause.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "What happens to the product when it breaks?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Crumbles,Fractures"
        },
        {
          "title": "How is the Hardness? (Hardness is the force needed to chew the product)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately-Strong Force,Strong Force,Extremely Strong Force"
        },
        {
          "title": "Size of pieces of crumbs (Chocolate or wafers) that fly while taking a bite",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "None,Small,Sizeable chunks"
        },
        {
          "title": "Uniformity of bite",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Uniform,Non uniform"
        },
        {
          "title": "Burst of Flavour ( Moisture release / Juiciness) ",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouth-Watering"
        },
        {
          "title": "Denseness - Compactness of the product",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouth-Watering"
        },
        {
          "title": "Cohesiveness (Degree to which product shears or deforms)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Shears,Partially shears and deforms,Deforms"
        },
        {
          "title": "Persistence of Sound inside the mouth",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        }

      ]
    },
    {
      "title": "Chew down",
      "subtitle": "Chew the product again for 8-10 times to make a pulp and pause",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "Moisture absorption (Amount of saliva absorbed by the product)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed"
        },
        {
          "title": "Adhesiveness to the palate (Force needed to remove the product that has stuck to the palate.)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately-Strong Force,Strong Force,Extremely Strong Force"
        },
        {
          "title": "Rate of Melt ( Consider rate at which the product melts during chew down)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "None,Slow rate,Moderate rate,High rate"
        },
        {
          "title": "Roughness of mass",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Smooth,Ultra smooth,Gritty,Grainy,Abrasive (pointed particles),Lumpy"
        },
        {
          "title": "Is the product sticking to teeth?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        }
      ]
    },
    {
      "title": " Residual",
      "subtitle": "After swallowing what is the feeling inside the mouth.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [{
          "title": "Do you feel anything left in the mouth?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        },
        {
          "title": "If yes ( optional )",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
          "option": "Oily film,Loose particles,Sticking on tooth,Chalky "
        }
      ]
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
  "OVERALL PRODUCT EXPERIENCE": [{
      "title": "To make it more appealing, which attribute/s needs improvement. Elaborate in the comment box.",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "None,Appearance,Aroma,Taste,Aromatics to Flavor,Texture"
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
}'
        ;
        
        $data = ['name'=>'Crunchy Chocolate','keywords'=>"Crunchy Chocolate",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
