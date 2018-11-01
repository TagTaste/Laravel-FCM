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

            ['header_name'=>"APPEARANCE","header_info"=>"Examine the product like its color, shape, surface appearance and texture etc. and answer the questions given below.Any attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the section."],

            ['header_name'=>"AROMA","header_info"=>"Aroma coming from the product can be traced to ingredients and process/es (like baking, cooking, fermentation etc.) which the product has undergone. Now smell it vigorously through your nose; at this stage, we are only assessing the aroma (odor through the nose), so please don't take a bite yet. Bring the product closer to your nose and take a deep breath. Further, take short, quick and strong sniffs like how a dog sniffs. Anything that stands out as either too good or too bad, may please be highlighted in the comment box."],
            ['header_name'=>"TASTE","header_info"=>"Tasting Time! Please take a bite, eat normally and assess the taste or tastes. Anything that stands out as too good or too bad, may please be highlighted in the comment box. "],
            ['header_name'=>"AROMATICS","header_info"=>"Aromatics is different from the aroma, it is about experiencing odor/s inside the mouth, as you eat. Please take a bite again, eat normally, keeping your mouth closed and exhale through the nose. Identify the odors using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],
            ['header_name'=>"FLAVOR","header_info"=>"As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting."],
            ['header_name'=>"ORAL TEXTURE","header_info"=>"Let us assess all the elements of texture, please follow steps, as outlined below:"],
            ['header_name'=>"OVERALL PREFERENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]
        ];


        $questions2 = '{

  "INSTRUCTIONS": [
    {
      "title": "INSTRUCTION",
      "subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. Anything that stands out as either too good or too bad, may please be highlighted in the comment box.",
      "select_type": 4
    }
  ],

  "APPEARANCE": [
    {
      "title": "Visual Observation",
      "subtitle": "Visually observe the product (without removing the liner from the muffin) and assess below questions.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Identify the overall color complex and its intensity.",
          "select_type": 1,
          "is_intensity": 1,
          "is_mandatory": 1,
          "subtitle": "If selected \"any other\" option please mention it in the comment box",
          "intensity_type": 2,
          "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
          "is_nested_question": 0,
          "option": "Tan,Beige,Sand,Hazelnut,Yellow,Gold,Butter,Mustard,Honey,Pineapple,Caramel,Coffee,Peanut,Wood,Brown,Cinnamon,Chocolate,Dark Chocolate,Charcoal,Any other"
        },
        {
          "title": "Distribution of color on the surface",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "option": "Even,Uneven"
        },
        {
          "title": "Surface appearance",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "option": "Bright,Dull,Shiny,Glazed,Oily"
        },
        {
          "title": "Surface texture ",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Moist,Dry,Sticky,Rough,Smooth,Loose particles,Baked,Crumpled,Blistered,Cracked"
        },
        {
          "title": "Shape of crust",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "option": "Perfect rising,Balloon like,Separated crust,Flat,Collapsed dome"
        },
        {
          "title": "Sponginess in the muffin",
          "subtitle": "Place the index finger in the centre and press down with moderate force",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Tough,Weak,Dense,Light,Compact,Fluffy"
        }
      ]
    },
    {
      "title": "Cross section view ",
      "subtitle": "Now remove the liner of the muffin and break the muffin into two halves.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Identify the color of the filling ",
          "select_type": 1,
          "is_intensity": 0,
          "is_mandatory": 1,
          "is_nested_question": 0,
          "option": "Purple,Lavender,Plum,Magenta,Black grapes,Pearl,Snow,Cream,Cotton,Bone,Red apple,Fire engine red,Cherry,Cherry,Red rose,Ruby"
        },
        {
          "title": "About color (of the filling)",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "option": "Bright,Dull,Glazed,Shiny"
        },
        {
          "title": "Quantity of the filling",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "option": "Scanty,Very Less,Less,Sufficient,Ideal( Balanced),Little extra,Extra,Excess"
        },
        {
          "title": "Consistency of the filling",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Gooey,Appears like fondue,Appears like ketchup,Appears like puree,Ideal (Balanced),Flows like honey,Flows like soup"
        },
        {
          "title": "Any Off Appearance",
          "subtitle": "If yes, describe in comment",
          "select_type": 1,
          "is_mandatory": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
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
      "title": "Aroma observed",
      "subtitle": "We have a list of more than 400 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to access the aroma list. Please select the maximum of 4 dominant aromas.",
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
      "title": "If you experienced any Off (bad)- aroma, please indicate the intensity.",
      "select_type": 2,
      "is_mandatory": 0,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Very Mild,Mild,Distinct mild,Distinct,Distinct strong,Strong,Overwhelming",
      "is_nested_question": 0,
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
      "option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
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

  "AROMATICS": [
    {
      "title": "Please identify aromatics experienced from the list.",
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
      "title": "Identify any off (bad)-aromatics",
      "select_type": 2,
      "is_intensity": 1,
      "intensity_type": 2,
      "intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
      "is_nested_question": 0,
      "is_mandatory": 0,
      "is_nested_option": 1,
      "nested_option_list": "OFFAROMA"
    },
    {
      "title": "Aftertaste",
      "subtitle": "Please swallow the product and focus on the sensation and experience.",
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

  "FLAVOR": [
    {
      "title": "Are you satisfied with the Flavors?",
      "subtitle": "If you want any change in Flavor like intensity or any other change then mention it in the comment box",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Yes,No"
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

  "ORAL TEXTURE": [
    {
      "title": "Texture",
      "subtitle": "Hold the product between the lips in such a manner that your lips are touching filling and sponge",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Rough,Smooth,Loose particles,Moist,Oily,Buttery,Dry,Sticky,Crumbly"
    },
    {
      "title": "First chew",
      "subtitle": "Take a bite with the filling. Chew for 3-4 times and pause.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Burst of flavor",
          "subtitle": "Moisture release / Juiciness",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dehydrated,Dry,Juiceless,Slightly Juicy,Juicy,Succulent,Syrupy,Mouthwatering"
        },
        {
          "title": "Uniformity of bite",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Uniform,Non- Uniform"
        },
        {
          "title": "Denseness",
          "subtitle": " Compactness of sponge",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Dense,Fluffy,Airy,Tight,Rubbery"
        },
        {
          "title": "Melt in the mouth",
          "subtitle": "Amount of saliva and time (rate) needed for the sample to melt",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Doesn\'t melt,Melts slowly,Melts moderately,Melts quickly"
        }
      ]
    },
    {
      "title": "Partial compression",
      "subtitle": "Consider your first chew experience and assess the below questions.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Is the sample sticking ",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Teeth,Palate,None"
        },
        {
          "title": "Adhesiveness to the palate",
          "subtitle": "Force needed to remove the product that has stuck to the palate",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
          "option": "No Force,Barely Any Force,Very Slight Force,Slight Force,Moderate Force,Moderately-Strong Force,Strong Force,Extremely Strong Force"
        }
      ]
    },
    {
      "title": "Chew down",
      "subtitle": "Chew the sample 8-10 times until it becomes pulpy.",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "Moisture absorption",
          "subtitle": "Amount of saliva absorbed by the sample",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Saliva Absorbed,Barely Any Saliva Absorbed,Very Slightly Saliva Absorbed,Slightly Saliva Absorbed,Moderately Saliva Absorbed,Plenty Saliva Absorbed,Loads of Saliva Absorbed,Extremely high quantity of Saliva Absorbed"
        },
        {
          "title": "Chew down Experience",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Sticky texture,Pasty texture,Dough like texture,Sandy"
        },
        {
          "title": "Bite length",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Short bite,Medium bite,Long bite"
        },
        {
          "title": "Cohesiveness of mass",
          "subtitle": "After chewing small particles may or may not come together to form a mass",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "No Mass Formed,Partial Mass Formed,Tight Mass Formed"
        },
        {
          "title": "Texture of the particle that does not form a mass",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Unchewable,Loose particles,Lumpy,Chalky,Abrasive"
        }
      ]
    },
    {
      "title": "Residual",
      "is_nested_question": 1,
      "is_mandatory": 1,
      "question": [
        {
          "title": "After swallowing did you feel anything left in the mouth ?",
          "select_type": 1,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 1,
          "option": "Yes,No"
        },
        {
          "title": "If yes",
          "select_type": 2,
          "is_intensity": 0,
          "is_nested_question": 0,
          "is_mandatory": 0,
          "option": "Oily film,Loose particles,Chalky"
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

  "OVERALL PREFERENCE": [
    {
      "title": "To make it more appealing, which attribute/s needs improvement. Elaborate in the comment box.",
      "select_type": 2,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "Appearance,Aroma,Taste,Aromatics,Flavor,Texture,None"
    },
    {
      "title": "Is the product sample acceptable?",
      "select_type": 1,
      "is_intensity": 0,
      "is_nested_question": 0,
      "is_mandatory": 1,
      "option": "yes,No"
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
}';

        $data = ['name'=>'Muffins','keywords'=>"Muffins",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
