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


            ['header_name'=>"APPEARANCE","header_info"=>" Examine the product and assess the questions outlined below.  Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],


            ['header_name'=>"AROMA","header_info"=>"Aroma/s arising from the product can be traced to the ingredients and the process/es (like baking, cooking, fermentation etc.), which the product has undergone. Now bring the product closer to your nose and take a deep breath. You may also try taking 3-4 short, quick and strong sniffs, like how a dog sniffs. At this stage, we are only assessing the aroma/s (odor/s through the nose), so please don't take a bite yet. Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],



            ['header_name'=>"TASTE","header_info"=>"Take a bite, eat normally and assess the taste/s and its intensity as mentioned in the section.  Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of the section."],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Aromatics is odour through the mouth. Take a bite again eat normally keeping your mouth closed and exhale through the nose. Identify the odours sensed using the aroma list and the search option. Anything too good or too bad observed please highlight in the comment box at the end of the section."],


            ['header_name'=>"ORAL TEXTURE","header_info"=>"We have covered taste and odor/s (inside and outside the mouth). Now it is the turn of ‘feel’ inside the mouth. ‘Feel’ starts when the food comes in contact with the mouth; the ‘feel’ changes as the food is processed inside the mouth because of chewing (Applied Pressure) and the ‘feel’ may even last after the food has been swallowed. Foods when chewed may make SOUND (like chips), give us joy (like creamy foods), pain (like sticky foods) or even disgust for some (like rubbery foods -mushroom). Texture (mouthfeel) is all about the joy we get from what we eat.

"],

            ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]


        ];



        $questions2 = '{
  "INSTRUCTIONS": [
    {
      "title": "Instruction",
      "subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. Anything that stands out as either too good or too bad, may please be highlighted in the comments box.",
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
      "option": "Below room temperature,Room temperature,Slightly Hot,Hot,Very Hot,Burning Hot"
    },
    {
      "title": "What is the color of the crust? If you select \"Any other\" option then please mention it in the comment box.",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Hay,Straw,Golden,Yellow,Copper,Bronze,Light Brown,Brown,Any other"
    },
    {
      "title": "Considering the product served, is there any variation in the color?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Uniform,Non Uniform"
    },
    {
      "title": "How does the color appear?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Bright,Dull,Shiny,Dehydrated,Oily,Soggy,Limp,Firm"
    },
    {
      "title": "Considering the product served, what is your perception about its length?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Too long,Too short,Appropriate"
    
    },
    {
      "title": "Considering the product served, what is your perception about its thickness?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option":"Too thin,Too thick,Appropriate"
    },
    {
      "title": "Did you experience any off appearance? If yes, then please answer the questions given below.",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
    },
    {
      "title": "Did you find any of these irregularities on the surface of the served product?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0,
      "option": "Blisters,Blemishes,Dark ends,Black spots,Peels,Ruptured crust"
    },
    {
      "title": "Which of these irregular sizes did you spot?",
      "subtitle": "Slivers - Short and narrow \nNubbins - Lumps",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0,
      "option": "Slivers,Nubbins,Off cut,Feathered edges,Shattered,Broken,Ragged cuts,Side cuts"
    },
    {
      "title": "Press a single french fries between index finger and thumb with moderate force. How springy is the product?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Deforms and bounces back,Collapses,Tears,Hard"
    },
    {
      "title": "How does the centre of the product appear?",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Hollow,Raw,Cooked,Dense,Dry,Fluffy,Moist,Mushy"
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
      "is_mandatory": 0,
      "is_nested_question": 0
    }
  ],
  "AROMA": [
    {
      "title": "Which all aromas did you observe? It is normal to experience multiple aromas.",
      "subtitle": "Some aromas are easy to identify. Use the search box to locate such aromas. If you can\'t find the aroma/s identified by you through the search box, then please select \"Any other\" option and mention it in the comment box. Mostly however, aromas seem to be familiar but sometimes it is difficult to recall their name. In such a case, you can explore the global list of the aromas. In this list the aromas are grouped under various heads.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "is_nested_option": 1,
      "nested_option_list": "AROMA"
    },
    {
      "title": "If you experienced any Off (bad)- aroma, please indicate the intensity. ",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
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
      "is_mandatory": 0,
      "is_nested_question": 0
    }
  ],
  "TASTE": [
    {
      "title": "Basic Taste",
      "is_nested_question": 1,
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
          "option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
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
          "option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
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
      "is_intensity": 1,
      "intensity_type": 2,
     "intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
      "is_mandatory": 1,
      "is_nested_question": 0,
      "option": "Astringent (Dryness),Pungent (Spices/ Garlic),Pungent Cool Sensation (Mint),Pungent Chilli"
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
      "is_mandatory": 0,
      "is_nested_question": 0
    }
  ],
  
  "AROMATICS TO FLAVORS": [
    {
      "title": "Which all aromatics did you observe? It is normal to experience multiple aromatics.",
      "subtitle": "Some aromatics are easy to identify. Use the search box to locate such aromatics. If you can\'t find the aromatic/s identified by you through the search box, then please select \"Any other\" option and mention it in the comment box. Mostly however, aromatics seem to be familiar but sometimes it is difficult to recall their name. In such a case, you can explore the global list of the aromatics. In this list, aromatics are grouped under various heads.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "is_nested_option": 1,
      "nested_option_list": "AROMA"
    },
    {
      "title": "If you experienced any off (bad) aroma, please indicate the intensity.",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
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
           "option": "Pleasant,Unpleasant,Can\'t Say"
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
      "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together. Usually, taste has a lesser contribution and aromatics on the other hand has a greater contribution towards the development of the flavor.",
      "is_nested_question": 1,
      "question": [
        {
          "title": "Did you experience any Flavor/s. If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the comment box.",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Flavor,Can\'t Say,Desirable Flavor,Undesirable Flavor"
        },
        {
          "title": "Specific to Potato which prominent flavor did you identify? ",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Cooked,Boiled,Roasted,Raw,Baked,Freshly Fried,Fried,None"

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
      "option": "Dry,Moist,Oily,Rough,Smooth,Crystals,Sticky"
    },
    {
      "title": "Go ahead and please bite (only once) into 3 french fries at the same time. Assess the sound and its intensity. What was the sound like?", 
      "subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips \nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks \nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
          "intensity_value": "None,Barely detectable,Identifiable but not very intense ,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Crispy,Crunchy,Crackly"
    },
    {
      "title": "How did the french fries break into pieces during the first bite (fracturability)?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
       "option": "Cut instantly,Cut reluctantly,Ruptured,Hard"
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
          "option": "Dehydrated,Dry,Slightly Moist,Moist,Succulent"
        },
        {
          "title": "Assess the solids inside the mouth? What did you feel?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Pasty,Mushy,Fluffy,Chewy,Coarse,Hard"
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
          "option": "Uniform force,Non uniform force"
        },
        {
          "title": "While chewing, how much saliva was absorbed by the product?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
           "option": "None,Barely,Very Slight,Slight,Moderate,Plenty,High,Extremely High"
        },
        {
          "title": "During chewing, was the pulp formed or did the mass scatter?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dissolved,Scattered,Pulpy Mass,Tightly Bonded"
        },
        {
          "title": "Is the product sticking to teeth or palate?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        },
        {
          "title": "If yes, then how much force was needed to remove the product from the palate? ",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
          "option": "None,Barely,Very Slight,Slight,Moderate,Moderately Strong,Strong,Extremely Strong"
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
          "option": "Yes,No"
        },
        {
          "title": "If yes, what was left in your mouth?",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
           "option": "Oily film,Loose particles,Sticking on tooth,Chalky,Toothpack"
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
      "is_mandatory": 0,
      "is_nested_question": 0
    }
  ],
  "OVERALL PRODUCT EXPERIENCE": [
    {
      "title": "Which part of the french fries did you enjoy the most?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Outer crispy layer,Inner cooked potato,Salty crystals,Seasoning,None"
    },
    {
      "title": "Does any attribute/s need/s any improvement?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
    },
    {
      "title": "If yes, which attribute/s needs improvement? Elaborate in the comment box.",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 0,
      "option": "Appearance,Aroma,Taste,Aromatics to Flavor,Texture"
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
}';
        
        $data = ['name'=>'FRENCH FRIES - SALTY SNACKS','keywords'=>"FRENCH FRIES - SALTY SNACKS",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
