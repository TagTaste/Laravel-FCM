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



        ['header_name' => "APPEARANCE", "header_info" => ["text" => "Take a slice of bread & place it on a white plate. Examine the product visually and answer the questions outlined below.\nFor this tasting process, the top and bottom most slice should be <b>excluded</b>.","images" => ["https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/Public.png"]],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Aroma+(1)+(1).png"]],'header_selection_type'=>"1"],




        ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Taste-2.png"]] ,'header_selection_type'=>"1"],


        ['header_name' => "AROMATICS TO FLAVORS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these identified odors are called Aromatics.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Aromatics+(1).png"]],'header_selection_type'=>"1"],



        ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],



    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]


    ];

        $questions2 = '

{

    "INSTRUCTIONS": [{
        "title": "Instruction",
        "subtitle": "<b>Welcome to Fruit Bread Tasting!</b>\n\nHope you have attended the training on sensory tasting. Now please answer the questions. Every question will have many options. To answer, please select option/s which match your feelings and observations. For help, please click \"i\" on every page/screen.\n\nRemember, there are no right or wrong answers. If you feel anything about the bread to be very good or very bad, then please write about it in the comment box. You will find the comment box at the end of every section.\n\nPlease make sure your fruit bread is at room temperature. Now let\'s start by opening the package. Please select any slice from the centre of the bread.",
        "select_type": 4,
        "question_info": {
          "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/sample/artboard1_4x.png"]
           
        }

    }],
   
   

    "APPEARANCE": [{
            "title": "What is the serving temperature of the slice of bread?",
            "subtitle": "You may also touch the bread to assess the serving temperature.",
           
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
            "title": "How does this slice of bread appear to you?",
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
                    "value": "Fresh",
                    "is_intensity": 0
                },
                {
                    "value": "Stale",
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
          
            "title": "What is the colour of the crumb (inner part of the bread)?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
           
            "option": [{
                    "value": "White",
                    
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
                    "value": "Brown",
                    
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
            "title": "What are the colors of tutti frutti in the crumb (inner part of the bread). Also, please give the intensity of the colors.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            
            "option": [{
                    "value": "Yellow",
                   
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Red",
                   
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                
                {
                    "value": "Green",
                    
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any other",
                     
                    "option_type": 1,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "What is the size of the slice of bread?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Very small",
                    "is_intensity": 0
                },
                {
                    "value": "Small",
                    "is_intensity": 0
                },
                {
                    "value": "Medium",
                    "is_intensity": 0
                },
                {
                    "value": "Large",
                    "is_intensity": 0
                },
                {
                    "value": "Very large",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "What is the quantity of (tutti frutti) on crumb (inner part of the bread)?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Very less",
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
            "title": "Please touch the bread with your finger. What do you feel?",
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
            "title": "How spongy is this slice of bread?",
            "subtitle": "Place one finger in the centre of the bread and press down with normal force.",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Very spongy",
                    "is_intensity": 0
                },
                {
                    "value": "Spongy",
                    "is_intensity": 0
                },
                {
                    "value": "Dense",
                    "is_intensity": 0
                },
                {
                    "value": "Very dense",
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
            "is_mandatory": 1,
            "is_nested_option": 1,
            "nested_option_title": "AROMATICS",
            "nested_option_list": "AROMA"
        },
        {
            "title": "Please swallow the slice of bread and pause. How is the aftertaste?",
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
            "title": "Place the slice of bread between the lips. What do you feel?",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Smooth",
                    "is_intensity": 0
                },
                {
                    "value": "Rough",
                    "is_intensity": 0
                },
                {
                    "value": "No loose particles",
                    "is_intensity": 0
                },
                {
                    "value": "Few loose particles",
                    "is_intensity": 0
                },
                {
                    "value": "Many loose particles",
                    "is_intensity": 0
                },
                {
                    "value": "No moisture (Dry)",
                    "is_intensity": 0
                },
                {
                    "value": "Moist",
                    "is_intensity": 0
                },
                 {
                    "value": "Damp",
                    "is_intensity": 0
                },
                {
                    "value": "Wet",
                    "is_intensity": 0
                },
                {
                    "value": "Oily",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How much <b>force</b> is needed to chew the bread? ",
            "subtitle": "Please chew the product 3-4 times and pause.",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Less force",
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
            "title": "While chewing, which textures can you feel inside your mouth?",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Soft",
                    "is_intensity": 0
                },
                {
                    "value": "Spongy",
                    "is_intensity": 0
                },
                {
                    "value": "Lumpy",
                    "is_intensity": 0
                },
                {
                    "value": "Sticky",
                    "is_intensity": 0
                },
                {
                    "value": "Chewy",
                    "is_intensity": 0
                },
                {
                    "value": "Rough",
                    "is_intensity": 0
                },
                {
                    "value": "Firm",
                    "is_intensity": 0
                },
                 {
                    "value": "Rubbery",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                     "option_type": 1,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How does the bread <b>dissolve</b> in your mouth?",
            "subtitle": "Eat normally and pause. Please don\'t swallow the bread yet.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Dissolves slowly",
                    "is_intensity": 0
                },
                {
                    "value": "Dissolves normally",
                    "is_intensity": 0
                },
                {
                    "value": "Dissolves quickly",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "After swallowing the bread, do you feel anything left inside your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Oily layer",
                    "is_intensity": 0
                },
                {
                    "value": "Loose particles",
                    "is_intensity": 0
                },
                {
                    "value": "Sticking on tooth/ palate",
                    "is_intensity": 0
                },
                {
                    "value": "Stuck between teeth",
                    "is_intensity": 0
                },
                {
                    "value": "Powdery layer",
                    "is_intensity": 0
                },
                {
                    "value": "Any other",
                     "option_type": 1,
                    "is_intensity": 0
                },
                {
                    "value": "No residue",
                     "option_type": 2,
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
   

    "PRODUCT EXPERIENCE": [
      
        {
            "title": "Did this bread succeed in satisfying your basic senses?",
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
                    "value": "Aromatics to flavor",
                    "is_intensity": 0
                },
                {
                    "value": "Texture",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced product",
                     "option_type": 2,
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
            "is_mandatory": 1,
            "is_nested_question": 0
        }
    ]
}';

        $data = ['name'=>'private_modern_fruit_bread','keywords'=>"private_modern_fruit_bread",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true),'track_consistency'=>$track_consistency];
        \DB::table('global_questions')->insert($data);


    }
}