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
['header_name' => "Instructions",'header_selection_type'=>"0"],

        ['header_name' => "Appearance", "header_info" => ["text" => "Examine the Hyderabadi Biryani visually and answer the questions outlined below. Please DO NOT EAT IN THIS ENTIRE SECTION."],'header_selection_type'=>"1"],


        ['header_name' => "Aroma","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so Please DO NOT EAT IN THIS ENTIRE SECTION. Now bring the Biryani closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the Biryani can be traced to the ingredients (Rice, Spices, etc.) and the processes (like dum, cooking, marination etc.) which the Biryani might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/u5yypm6hxb8ujwb528tw4r.png"]],'header_selection_type'=>"1"],


        ['header_name' => "Taste","header_info" => ["text" => "Eat normally and assess the tastes."],'header_selection_type'=>"1"],


        ['header_name' => "Aromatics To Flavor","header_info" => ["text" => "Aromatics is the odor/s of food/beverage coming from inside the mouth.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/jgjdj9w7ajaaw5f7cc1ee.png"]],'header_selection_type'=>"1"],

        ['header_name' => "Texture","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

    ['header_name' => "Product Experience","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

    ];

        $questions2 = '{
    "Instructions": [{
        "title": "Instruction",
        "subtitle": "<b>Welcome to the Hyderabadi Biryani Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of every section.\n\nPlease click \"Learn\"/(i) on every screen/page for guidance related to questions. Kindly start the review but please don\'t eat it yet.",
        
        "select_type": 4
    }],
    
  
    "Appearance": [
      
      {
      
            "title": "What is the serving temperature of the Hyderabadi Biryani?",
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
                    "value": "Very Hot",
                    "is_intensity": 0
                }
            ]
        },
        {
      
            "title": "Visually, which style best describes this Biryani?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Home Style Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Dhaba Style Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Restaurant Style Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Hyderabadi Dum Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                    "is_intensity": 0
                }
            ]
        },
        {
      
            "title": "How does this Biryani appeal to you?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Appealing & Appetizing",
                    "is_intensity": 0
                },
                {
                    "value": "Not Appetizing",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the visual impression of this Biryani?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Bright",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Dull",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Shiny",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Oily",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Light",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Dark",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Natural",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Artificial",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Stale",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any Other (Be Specific)",
                     "option_type": 1,
                      "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
                
            ]
        },
        {
            "title": "Which aspects of this Biryani are making it Special?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "option": [{
                    "value": "Masaledar",
                    "is_intensity": 0
                },
                {
                    "value": "Colorful Vegetables",
                    "is_intensity": 0
                },
                {
                    "value": "Fluffy Rice",
                    "is_intensity": 0
                },
                {
                    "value": "Long Grain Basmati Rice",
                    "is_intensity": 0
                },
                {
                    "value": "Dry Fruits",
                    "is_intensity": 0
                },
                {
                    "value": "Fresh Herbs",
                    "is_intensity": 0
                },
                 {
                    "value": "None",
                    "option_type": 2,
                    "is_intensity": 0
                }, 
               {
                    "value": "All of these",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                     "option_type": 1,
                     "is_intensity": 0
                }
                
            ]
        },
        {
            "title": "What do most of the rice look like?",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "option": [{
                    "value": "Fluffy & Separated",
                    "is_intensity": 0
                },
                {
                    "value": "Not Fluffy but Separated",
                    "is_intensity": 0
                },
                {
                    "value": "Sticky & Lumpy",
                    "is_intensity": 0
                },
                {
                    "value": "Broken & Separated",
                    "is_intensity": 0
                },
                {
                    "value": "Broken & Mushy",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                     "option_type": 1,
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
    "Aroma": [
      {
            "title": "Which of the following feeling is more pronounced than the others?",
            "subtitle": "Take in the Aromas of this Biryani. It is possible that many feelings come over you.",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [{
                    "value": "Mouth Watering",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Irritating",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Soothing",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                 {
                    "value": "Not Appetizing",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No Aroma, so No Feeling",
                     "option_type": 2,
                     "is_intensity": 0
                },
                {
                    "value": "Has Aroma but No Pronounced Feeling",
                    "is_intensity": 0
                    
                },
                {
                    "value": "Any Other (Be Specific)",
                     "option_type": 1,
                     "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
            ]
        },
        {
            "title": "What is your one clear perception about the Bouquet of Aromas in this Biryani?",
            "subtitle": "Savoury can be defined as a pleasant aroma which is Salty or Spicy in nature, but not Sweet.",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/fgr35aq5vkd15xn6w73mvv.jpg"]
                },
            "option": [{
                    "value": "Sweet Perception",
                    "is_intensity": 0
                },
                {
                    "value": "Savory Perception",
                    "is_intensity": 0
                },
                {
                    "value": "Chilli Perception",
                    "is_intensity": 0
                },
                {
                    "value": "Spicy (Masala) Perception",
                    "is_intensity": 0
                },
                {
                    "value": "No Clear Perception",
                    "is_intensity": 0
                }
                
            ]
        },
      {
            "title": "What are the Aromas that you can sense?",
            "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any Other\" and if unable to sense any aroma at all, then select \"Absent\".",
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
"Taste": [
  {
            "title": "Which Basic tastes can you sense?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
              "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Basic+Tastes.png"]
                },
           
            "option": [
              {
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
            "title": "In addition to Basic tastes, there are two more tastes namely, Astringent and Pungent. Are you experiencing these two tastes in your mouth?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
              "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/wcgu4hreg2o7sia52u8r6m.png"]
                },
            "option": [{
                    "value": "Astringent (Puckery-Raw Banana)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pungent (Spices i.e. Masale/Garlic)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
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
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No Additional Taste",
                    "is_intensity": 0,
                    "option_type": 2
                   
                }
            ]
        },
        {
            "title": "While chewing normally, which sensations do you feel inside your mouth?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
             
            "option": [{
                    "value": "Bite (Sting)",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Itching",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Warming",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Tingling",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Burning",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Numbing",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Dry & Puckery",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Cooling",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any Other (Be Specific)",
                    "is_intensity": 1,
                    "option_type": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                   
                },
                {
                    "value": "No Sensation Felt",
                    "is_intensity": 0,
                    "option_type": 2
                   
                }
            ]
        },
        {
            "title": "Where can you feel the sensations caused by the Spices (Masale) along with Chillies and to what extent?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
             
            "option": [{
                    "value": "Lips",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Inner Cheeks",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Nose",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Palate",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Ears",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Eyes",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Back of the Throat",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Forehead",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Head",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any Other (Be Specific)",
                    "is_intensity": 1,
                    "option_type": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                   
                },
                {
                    "value": "No Sensation Felt",
                    "is_intensity": 0,
                    "option_type": 2
                   
                }
            ]
        },
        {
            "title":  "In one word, how do you find the taste of this Biryani?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Fingerlicking Good",
                    "is_intensity": 0
                },
                {
                    "value": "Addictive",
                    "is_intensity": 0
                },
                {
                    "value": "Usual",
                    "is_intensity": 0
                },
                {
                    "value": "It Hurts",
                    "is_intensity": 0
                },
                {
                    "value": "Bland/No Impact",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
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
 "Aromatics To Flavor": [
   {
            "title": "Which are the aromatics that you can sense?",
            "subtitle" : "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any Other\" and if unable to sense any aromatics at all, then select \"Absent\".",
            "info":
               {
               "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/drwh31f30u52ecbt8far.jpeg"]
               },
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
            "title": "Do you feel that the Spice & Chilli Quotient (Mirch Masala) of this Biryani is perfect or is there any spice/flavor missing in it?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Too Much Masala",
                    "is_intensity": 0
                },
                {
                    "value": "Perfect Amount of Masala",
                   "is_intensity": 0
                },
                 {
                    "value": "Missing Masala/e (Be Specific)",
                    "option_type": 1,
                   "is_intensity": 0
                },
                {
                    "value": "Perfect Balance of Both Mirch & Masala",
                   "is_intensity": 0
                },
                {
                    "value": "Excess Amount of Both Mirch & Masala",
                   "is_intensity": 0
                },
                
                 {
                    "value": "Bland-Lacks Both Mirch & Masala",
                     "is_intensity": 0
                },
                {
                    "value": "Too Much Chilli",
                     "is_intensity": 0
                },
                {
                    "value": "Perfect Amount of Chilli",
                     "is_intensity": 0
                },
                {
                    "value": "Lacks Chilli",
                     "is_intensity": 0
                }
            ]
        },
        {
            "title": "In terms of Flavor, which style best describes this Hyderabadi Biryani?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Home Style Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Dhaba Style Biryani",
                   "is_intensity": 0
                },
                 {
                    "value": "Restaurant Style Biryani",
                   "is_intensity": 0
                },
                {
                    "value": "Hyderabadi Dum Biryani",
                   "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                   "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the flavor experience?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Fresh, Natural & Pleasant",
                    "is_intensity": 0
                },
                {
                    "value": "Natural but Unpleasant",
                   "is_intensity": 0
                },
                 {
                    "value": "Artificial but Pleasant",
                   "is_intensity": 0
                },
                {
                    "value": "Stale, Artificial & Unpleasant",
                   "is_intensity": 0
                },
                {
                    "value": "Stale & Unpleasant",
                   "is_intensity": 0
                },
                
                 {
                    "value": "Bland",
                     "is_intensity": 0
                }
            ]
        },
        {
            "title": "Please swallow a bite and pause. What is the length of the aftertaste?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
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
            "title": "How is the aftertaste?",
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
                    "value": "Can\'t Say",
                   "is_intensity": 0
                }
            ]
        },
        {
            "title": "After Swallowing, which of these continue to linger in your mouth?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
             
            "option": [{
                    "value": "Balanced Flavor of Rice & Spices",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Refreshing Coriander & Mint",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Floral",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Spicy-Masaledar",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Aromatic Basmati Rice",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Teekhi Mirch",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fragrant Spices",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Vegetables",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
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
  "Texture": [
    {
            "title": "Did you experience any <b>Mouth Watering</b>? If yes, then to what extent?",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "option": [
              {
                    "value": "Yes",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                    
                },
                {
                    "value": "No",
                   "is_intensity": 0
                }
                
            ]
        },
        {
            "title": "Please take a bite of the Biryani and Chew 3-4 Times. Which of these are being released?",
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [
              {
                    "value": "Juicy Vegetables",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Oil/Fat",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Moisture",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Dry",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Starchy Paste",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
            ]
        },
        {
            "title": "Can you feel any gritty spices (Masale) inside your mouth?",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "option": [
              {
                    "value": "Yes",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                    
                },
                {
                    "value": "No",
                   "is_intensity": 0
                }
                
            ]
        },
        {
            "title": "How oily does this Biryani feel inside your mouth, after 8-10 chews?",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "option": [
              {
                    "value": "Oil free",
                   "is_intensity": 0
                },
              {
                    "value": "Oily",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                    
                }
                
                
            ]
        },
        {
            "title": "After a minimum of 8-10 chews what kind of mass is being formed?",
            "select_type": 1,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [
              {
                    "value": "Tight Mass-Difficult to Swallow",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pulpy Mass-Easy to Swallow",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Pasty Mass",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No Mass-Scattered Particles ",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
            ]
        },
        {
            "title": "After swallowing the product, do you feel anything left inside the mouth?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_mandatory": 1,
            "is_nested_question": 0,
            "is_nested_option": 0,
            "option": [
              {
                    "value": "Loose Particles",
                    "is_intensity": 0
                },
                {
                    "value": "Sticking on Tooth/Palate",
                    "is_intensity": 0
                },
                {
                    "value": "Stuck between Teeth",
                    "is_intensity": 0
                },
            {
                    "value": "Chalky Film (Powdery)",
                    "is_intensity": 0
                },
                {
                    "value": "Oily Film",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                     "option_type": 1,
                     "is_intensity": 0
                },
                {
                    "value": "No Residue",
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
 "Product Experience": [
   {
            "title": "Did this Biryani succeed in satisfying your basic senses?",
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
            "title": "Which Attributes can be improved further?",
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
                    "value": "Aromatics to Flavor",
                    "is_intensity": 0
                },
                {
                    "value": "Texture",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced Product",
                     "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
       
        {
            "title": "Is there anything in this Biryani that is Unforgettable?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Yes-Positive",
                    "option_type": 1,
                    "is_intensity": 0
                },
                {
                    "value": "Yes-Negative",
                    "option_type": 1,
                    "is_intensity": 0
                },
                {
                    "value": "No Differentiator",
                    "is_intensity": 0
                }
            ]
        },
       
        {
            "title": "Having tasted this Dish, what would you like to call it?",
            
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Biryani",
                    "is_intensity": 0
                },
                {
                    "value": "Pulao",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                    "is_intensity": 0
                }
            ]
        },
       {
            "title": "How often do you consume Biryani at home?",
            
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "More than once a Week",
                    "is_intensity": 0
                },
                {
                    "value": "Once a Week",
                    "is_intensity": 0
                },
                {
                    "value": "Once in 15 Days",
                    "is_intensity": 0
                },
                {
                    "value": "Once a Month",
                    "is_intensity": 0
                },
                {
                    "value": "Once in Three Months",
                    "is_intensity": 0
                },
                {
                    "value": "Once in Six Months",
                    "is_intensity": 0
                },
                {
                    "value": "Once a Year",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How would you rate this Biryani against the one you consume regularly?",
            
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Tastier",
                    "is_intensity": 0
                },
                {
                    "value": "Less Oil & Healthier",
                    "is_intensity": 0
                },
                {
                    "value": "Purer Spices (Masale)",
                    "is_intensity": 0
                },
                {
                    "value": "Natural Flavor",
                    "is_intensity": 0
                },
                {
                    "value": "Too Many Spices (Masale)",
                    "is_intensity": 0
                },
                {
                    "value": "Too Much Chilli",
                    "is_intensity": 0
                },
                {
                    "value": "Too Much Oil",
                    "is_intensity": 0
                },
                {
                    "value": "Just the Same",
                    "is_intensity": 0
                },
                {
                    "value": "Any Other (Be Specific)",
                    "option_type": 1,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "In which month have you done this Sensory Evaluation?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "January",
                    "is_intensity": 0
                },
                {
                    "value": "February",
                    "is_intensity": 0
                },
                {
                    "value": "March",
                    "is_intensity": 0
                },
                {
                    "value": "April",
                    "is_intensity": 0
                },
                {
                    "value": "May",
                    "is_intensity": 0
                },
                {
                    "value": "June",
                    "is_intensity": 0
                },
                {
                    "value": "July",
                    "is_intensity": 0
                },
                {
                    "value": "August",
                    "is_intensity": 0
                },
                {
                    "value": "September",
                    "is_intensity": 0
                },
                {
                    "value": "October",
                    "is_intensity": 0
                },
                {
                    "value": "November",
                    "is_intensity": 0
                },
                {
                    "value": "December",
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

        $data = ['name'=>'HR_HyderabadiBiryani_October21','keywords'=>"HR_HyderabadiBiryani_October21",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true),'track_consistency'=>$track_consistency];
        \DB::table('global_questions')->insert($data);


    }
}