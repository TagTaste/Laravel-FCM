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

   ['header_name'=>"APPEARANCE","header_info"=>"Observe the visual aspect of the product like it's color, appearance, texture etc. 
Anything too good or too bad observed please highlight in the comment box at the end of the section. "],

   ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't take a bite yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. Anything that stands out as either too good or too bad, may please be highlighted in the comment box."],

   ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste as mentioned in the section. Anything too good or too bad observed please highlight in the comment box at the end of the section. "],

   ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Odour through the mouth. Take a bite again eat normally keeping your mouth closed and exhale through the nose. Identify the odours sensed using the aroma list and the search option. Anything too good or too bad observed please highlight in the comment box at the end of the section."],

   ['header_name'=>"ORAL TEXTURE","header_info"=>"How a food feels inside the mouth (oraI texture) affects our enjoyment. Take a bite again but proceed only as per the instructions mentioned in the sub section . 
"],

   ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]

];



        $questions2 = '{
  "INSTRUCTIONS": [
    {
      "title": "INSTRUCTION",
      "subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during the product tasting. Remember, there are no right or wrong answers.                                   Incase you observe something missing in the options kindly write it in the comments box.                                                      Any attribute too good or too bad should also be highlighted in the comments box at the end of each section.",
      "select_type": 4
    }
  ],
  "APPEARANCE": [
    {
      "title": "How  did you find the color of the cooked dish",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Natural,Artificial "
    },
    {
      "title": "Now assess the color complex of the cooked dish",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Tempting,Appealing,Weak,Strong"
    },
    {
      "title": "How well has the product mixed in the cooked food",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Clots,Evenly mixed,Unevenly mixed"
    },
    {
      "title": "How is the texture of cooked dish",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Granular,Spongy,Sticky,Smooth,Firm,Dry"
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
    },
    {
      "title": "Comments",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0
    }
  ],
  "AROMA": [
    {
      "title": "Assess the aroma of the cooked dish",
      "subtitle": "We have list of aroma around 400 grouped under 11 heads. If you select \"any other\" option please write the aroma in the comment box. Use search box to locate the selected aroma from the list.",
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
      "title": "If you experienced any Off (bad)- aroma/s, please indicate the intensity.",
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
      "is_nested_question": 0,
      "is_mandatory": 0
    }
  ],
  "TASTE": [
    {
      "title": "Basic Taste",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
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
      "is_mandatory": 1,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
      "is_nested_question": 0,
      "option": "Astringent (Dryness),Pungent (Spices/Garlic),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
    },
    {
      "title": "As per your expectation, assess the role of the product in development of taste of the dish ( elaborate the answer in the comment box)",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Enhances Taste,Diminishes Taste,Meets Expectation,Can\'t Say"
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
    },
    {
      "title": "Comments",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0
    }
  ],
  "AROMATICS TO FLAVORS": [
    {
      "title": "Assess aromatics of the cooked dish (you can refer again to aroma section)",
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
      "subtitle": "Swallow the product and assess the sensation inside your mouth.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "How was the aftertaste?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Pleasant,Unpleasant"
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
      "question": [
        {
          "title": "Did you experience any Flavor/s.\n If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the comment box.",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Flavor,Can\'t say,Desirable Flavor,Undesirable Flavor"
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
    },
    {
      "title": "Comments",
      "select_type": 3,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0
    }
  ],
  "ORAL TEXTURE": [
    {
      "title": "Surface texture of the cooked dish inside your mouth ",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Fluffy,Chewy,Bit sticky,Gummy"
    },
    {
      "title": "First Chew",
      "subtitle": "Chew for 3-4 times and pause.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Burst of flavor ( Moisture release / Juiciness)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
        },
        {
          "title": "Do you feel any granular particles of the product in your mouth",
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
      "subtitle": "Chew the product 8-10- times to make pulp.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Moisture absorption (Amount of saliva absorbed by the product)",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Saliva Absorbed,Barely Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely High quantity of Saliva Absorbed,"
        }
      ]
    },
    {
      "title": " Residual (After feel)",
      "subtitle": "After swallowing what is the feeling inside the mouth.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Did you feel anything left in mouth ?",
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
      "is_nested_question": 0,
      "is_mandatory": 0
    }
  ],
  "OVERALL PRODUCT EXPERIENCE": [
    {
      "title": "What is your perception about this cooking process",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Tedious,Hasel free,Fine,"
    },
    {
      "title": "Is the consumption of this cooked dish ",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Kids friendly,Not kids friendly,Elderly friendly,Not elderly friendly"
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
}'
        ;
        
        $data = ['name'=>'Condiments- Flavoring Masala','keywords'=>"Condiments- Flavoring Masala",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
