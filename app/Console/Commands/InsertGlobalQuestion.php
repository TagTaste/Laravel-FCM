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
        $track_consistency = 0;
          $headerInfo2 = [
['header_name' => "INSTRUCTIONS",'header_selection_type'=>"0"],



        ['header_name' => "APPEARANCE", "header_info" => ["text" => "Please open the wrapper (butter paper) and examine the product visually. Answer the questions outlined below. <b>Please don't eat</b> in this entire appearance section."],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are only assessing the aromas (odors through the nose), <b>so please don't eat/ drink it yet</b>. Now bring the product closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/c4pm1dnfxg6i22pxdwty1q.png"]],'header_selection_type'=>"1"],



        ['header_name' => "TASTE","header_info" => ["text" => "Eat normally to assess the temperature and tastes."] ,'header_selection_type'=>"1"],


        ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Aromatics is the odor/s of food/ beverage coming from inside the mouth.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/2qk5m2kpffqxhy96pevkd.png"]],'header_selection_type'=>"1"],



        ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],



    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

       
    ];

        $questions2 = '
{

    "INSTRUCTIONS": [{
        "title": "<b>Welcome to the Product Review!</b>\n\nIf a product involves mixing, serving temperature instructions etc., then the taster must follow them fully, as mentioned on the packaging.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i)/ \'Learn\' on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        "select_type": 4

    }],
    
"APPEARANCE": [{
            "title": "How is the visual impression of the product?",
             "subtitle": "Just assess the look of the product.",

           
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
                    "value": "Shiny (Oily)",
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
                    "value": "Dry",
                    "is_intensity": 0
                },
                {
                    "value": "Natural",
                    "is_intensity": 0
                },
                {
                    "value": "Processed",
                    "is_intensity": 0
                },
                {
                   "value": "Any other (Be specific)",
                    "is_intensity": 0,
                     "option_type": 1
                }
               
            ]
        },
       
        {
            "title": "Hold the product in your hand/s. How will you describe your experience?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/8oie7lkgje4mgihfqqd21.png"]

                },
            "option": [{
                    "value": "Messy & fun",
                    "is_intensity": 0
                },
                {
                    "value": "Popping out",
                    "is_intensity": 0
                },
                {
                    "value": "Dripping cheese",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced",
                    "is_intensity": 0
                },
                {
                    "value": "Clean",
                    "is_intensity": 0
                },
                {
                    "value": "Messy & falling apart",
                    "is_intensity": 0
                },
                {
                    "value": "Flattened ",
                    "is_intensity": 0
                },
                {
                    "value": "Smooth bun",
                    "is_intensity": 0
                },
                {
                    "value": "Wrinkled (Shrivelled) bun",
                    "is_intensity": 0
                },
                {
                    "value": "Cracked bun",
                    "is_intensity": 0
                },
                {
                    "value": "Colorful",
                    "is_intensity": 0
                },
                {
                    "value": "Plain",
                    "is_intensity": 0
                },
                {
                    "value": "Oily",
                    "is_intensity": 0
                },
                {
                    "value": "Loose Egg (Runny)",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
                }
            ]
        },
       
        {
            "title": "In terms of quantity, what is the proportion of the ingredients in the product?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/ude8a9hu1lz2ibi08666f.png"]

                },
            "option": [{
                    "value": "Toasted Bun",
                   
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Cheese",
                   
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               
                {
                    "value": "Omelette",
                   
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
            ]

        },
        {
            "title": "What is the color of the omelette?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "White",
                    "is_intensity": 0
                },
                {
                    "value": "Beige ",
                    "is_intensity": 0
                },
                {
                    "value": "Yellow",
                    "is_intensity": 0
                },
                {
                    "value": "Brown spots",
                    "is_intensity": 0
                },
                {
                    "value": "Burnt spots",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
                   
                }
            ]
        },
        {
         "title": "How does the omelette appear to you?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Flat ",
                    "is_intensity": 0
                },
                {
                    "value": "Fluffy",
                    "is_intensity": 0
                },
                {
                    "value": "Oily/Greasy",
                    "is_intensity": 0
                },
                {
                    "value": "Very oily/greasy",
                    "is_intensity": 0
                },
                {
                    "value": "Creamy",
                    "is_intensity": 0
                },
                {
                    "value": "Dry",
                    "is_intensity": 0
                },
                {
                    "value": "Rubbery",
                    "is_intensity": 0
                },
                {
                    "value": "Overcooked",
                    "is_intensity": 0
                },
                {
                    "value": "Undercooked",
                    "is_intensity": 0
                },
                {
                    "value": "Well cooked",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
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
            "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\".",
           
            "select_type": 2,
            "is_intensity": 1,
            "intensity_type": 2,
             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
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
    
    "TASTE": [
      {
    "title": "What is the temperature of the product?",
            "subtitle": "Please eat normally.",
           
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
         "title": "Which Basic tastes have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/fms5pe12t58z2jiyonugm9.png"]

                },
            "option": [{
                    "value": "Sweet",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Salt",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Sour",
                 
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic,Moderately Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
                },
                {
                    "value": "Bitter",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Umami",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
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
            "title": "Which Ayurvedic tastes have you sensed?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/w1fav2g56qlw3c5tr8q7g.png"]

                },
            "option": [{
                    "value": "Astringent (Puckery - Raw Banana)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent (Spices / Garlic)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate, Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent Cool Sensation (Mint)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent Chilli",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
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
             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
            "is_nested_question": 0,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/ztcnnowqkwg9in8yhuohi.png"]

                },
            "is_mandatory": 1,
            "is_nested_option": 1,
            "nested_option_title": "AROMATICS",
            "nested_option_list": "AROMA"
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
                    "value": "Processed but pleasant",
                    "is_intensity": 0
                },
                {
                    "value": "Processed & unpleasant",
                    "is_intensity": 0
                },
                {
                    "value": "Bland",
                    "is_intensity": 0
                },
                {
                    "value": "Strong",
                    "is_intensity": 0
                },{
                    "value": "Balanced",
                    "is_intensity": 0
                },
                {
                    "value": "Weak",
                    "is_intensity": 0
                }
            ]
        },
 {
            "title": "Which <b>prominent flavor</b> do you experience in an omelette?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Bun",
                    "is_intensity": 0
                },
                {
                    "value": "Omelette",
                    "is_intensity": 0
                },
                {
                    "value": "Cheese ",
                    "is_intensity": 0
                },
                {
                    "value": "Masala ",
                    "is_intensity": 0
                },
                {
                    "value": "Egg",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                "option_type": 1
                }
            ]
        },
         {
            "title": "Please swallow the product and pause, how is the aftertaste?",
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
            "title": "In terms of aftertaste, what is lingering in your mouth?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Masala",
                    "is_intensity": 0
                },
                {
                    "value": "Egg",
                    "is_intensity": 0
                },
                {
                    "value": "Masala omelette",
                    "is_intensity": 0
                },
                {
                    "value": "Omelette",
                    "is_intensity": 0
                },
                 {
                    "value": "Cheese",
                    "is_intensity": 0
                },
                 {
                    "value": "Bun",
                    "is_intensity": 0
                },
                 {
                    "value": "Oil/ Butter",
                    "is_intensity": 0
                },
                 {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
                   
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
            "title": "How much force is needed to bite through the entire product?",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Barely any",
                    "is_intensity": 0
                },
                {
                    "value": "Weak",
                    "is_intensity": 0
                },
                {
                    "value": "Moderate ",
                    "is_intensity": 0
                },
                {
                    "value": "Extra",
                    "is_intensity": 0
                },
                {
                    "value": "Excess",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the cheese experience inside the mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
             "option": [
               {
                    "value": "Creamy",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Solid",
                    "is_intensity": 0
                   
                },
                {
                    "value": "Not applicable",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
              ]
        },
                 {
            "title": "How is the omelette cooked?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Well cooked ",
                    "is_intensity": 0
                },
                {
                    "value": "Overcooked ",
                    "is_intensity": 0
                },
                {
                    "value": "Undercooked ",
                    "is_intensity": 0
                }
            ]
        },
         {
            "title": "How does the <b>omelette</b> feel inside your mouth?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Fluffy",
                    "is_intensity": 0
                },
                {
                    "value": "Oily/ Buttery",
                    "is_intensity": 0
                },
                {
                    "value": "Tender",
                    "is_intensity": 0
                },
                 {
                    "value": "Velvety",
                    "is_intensity": 0
                },
                 {
                    "value": "Gooey",
                    "is_intensity": 0
                },
                 {
                    "value": "Dense",
                    "is_intensity": 0
                },
                 {
                    "value": "Moist",
                    "is_intensity": 0
                },
                 {
                    "value": "Chewy",
                    "is_intensity": 0
                },
                 {
                    "value": "Dry",
                    "is_intensity": 0
                },
                 {
                    "value": "Rubbery",
                    "is_intensity": 0
                },
                 {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
                }
            ]
        },
        {
            "title": "What is the proportion of egg and masala in an omelette?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
             "option": [
               {
                    "value": "Masala",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                
                {
                    "value": "Egg",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
              ]
        },
 
 {
            "title": "While chewing (the entire product), which textures can you feel inside your mouth?",
             "subtitle": " Please select a maximum of 4 options.",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Soft",
                    "is_intensity": 0
                },
                {
                    "value": "Mushy",
                    "is_intensity": 0
                },
                {
                    "value": "Skin awareness (peels)",
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
                    "value": "Rubbery",
                    "is_intensity": 0
                },
                {
                    "value": "Rough",
                    "is_intensity": 0
                },
                {
                    "value": "Grainy",
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
                    "value": "Gritty",
                    "is_intensity": 0
                },
                {
                    "value": "Firm",
                    "is_intensity": 0
                },
                {
                    "value": "Hard",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
                }
            ]
        },
         {
            "title": "How easy/difficult is the product to swallow?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "Easy",
                    "is_intensity": 0
                },
                {
                    "value": "Somewhat easy",
                    "is_intensity": 0
                },
                {
                    "value": "Moderate ",
                    "is_intensity": 0
                },
                {
                    "value": "Somewhat difficult",
                    "is_intensity": 0
                },
                 {
                    "value": "Difficult",
                    "is_intensity": 0
                }
            ]
        },
   {
            "title": "After swallowing, do you feel anything left inside the mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Oily/ Buttery film",
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
                    "value": "Stuck between teeth",
                    "is_intensity": 0
                },
                 {
                    "value": "Chalky",
                    "is_intensity": 0
                },
                {
                    "value": "Dry crumb",
                    "is_intensity": 0
                },
                {
                    "value": "No residue",
                    "is_intensity": 0,
                   
                    "option_type": 2
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
  "PRODUCT EXPERIENCE": [
     
        {
            "title": "Which bun masala omelette did you like/enjoy the most?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "This is Perfect",
                    "is_intensity": 0
                },
                {
                    "value": "Street style",
                    "is_intensity": 0
                },
                {
                    "value": "Homemade",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
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
            "title": "The obvious names for this product can be Masala Omelette Burger, Masala Omelette Sandwich and Masala Bun Omelette. Will you like to suggest any other name to personify the experience of this product inside your mouth or go with one these obvious options. ",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Masala Omelette Burger",
                    "is_intensity": 0
                },
                {
                    "value": "Masala Omelette Sandwich",
                    "is_intensity": 0
                },
                {
                    "value": "Masala Omelette Bun",
                    "is_intensity": 0
                },
                {
                    "value": "Bun Masala Omelette",
                    "is_intensity": 0
                },
                {
                    "value": "BMO",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
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
            "is_mandatory": 1,
            "is_nested_question": 0
        }
    ]
}';

        $data = ['name'=>'Private_McD_Bun_Omelette_CheeseSlice','keywords'=>"Private_McD_Bun_Omelette_CheeseSlice",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true),'track_consistency'=>$track_consistency];
        \DB::table('global_questions')->insert($data);


    }
}