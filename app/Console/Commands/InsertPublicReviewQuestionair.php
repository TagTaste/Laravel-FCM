<?php

namespace App\Console\Commands;

use App\PublicReviewProduct\Questions;
use Illuminate\Console\Command;

class InsertPublicReviewQuestionair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'public:review:globalquestion:insert';

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

 ['header_name' => "Your Food Shot",'header_selection_type' => "3"],

        ['header_name' => "APPEARANCE", "header_info" => ["text" => "Take a piece of the product and keep it on a white plate or white paper. Examine the product and answer the questions outlined below. Please don't eat in this entire appearance section."],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "1. At this stage, we are only assessing the aromas (odors through the nose), <b>so please don't eat it yet</b>.\n2.Now bring the product closer to your nose and take a deep breath.\n3. You may also try taking 3-4 short, quick and strong sniffs.\n4. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/t24bgx96sv5gd0esaikpf.png"]],'header_selection_type'=>"1"],


        ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes."],'header_selection_type'=>"1"],

['header_name' => "Aromatics To Flavor","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE or instead of exhaling you can simply PINCH YOUR NOSE for minimum 5-6 seconds and release. Identify the odors that come from inside the mouth; these identified odors are called Aromatics.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/f9xhjpg5njekkwxnf8pj.png"]],'header_selection_type'=>"1"],
       
        ['header_name' => "MOUTHFEEL","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]


 

];
        $questions2 = '{

    "INSTRUCTIONS": [{
        "title": "Instruction",
        
        "subtitle": "Welcome to the Product Review!\n\nIf the product involves cooking (like noodles) then the taster must follow the instructions fully, as mentioned on the packaging.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please <b>click (i)/ \'Learn\'</b> on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        
        "select_type": 4

    }],
    
     "Your Food Shot": [



        {



            "title": "<b>Take</b> a selfie with the product",



            "subtitle": "Reviews look more authentic when you post them with a photograph.",

      "placeholder_image": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png",


            "select_type": 6



        }



    ],
    
    "APPEARANCE": [{
            "title": "What is the serving temperature of the product?",
            
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
            "title": "How is the visual impression of the product?",
           
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
                    "value": "Glazed",
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
                   "value": "Fresh",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Stale",
                    "is_intensity": 0
                    
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                    "is_intensity": 0
                    
                }
                
            ]
        },
        {
            "title": "What is the prominent color of the product?",
           
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
                
            "option": [{
                    "value": "Light brown",
                    "is_intensity": 0
                },
                {
                    "value": "Pale brown",
                    "is_intensity": 0
                },
                {
                    "value": "Golden brown",
                    "is_intensity": 0
                },
                {
                    "value": "Dark brown",
                    "is_intensity": 0
                },
                {
                    "value": "Yellow",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                    "is_intensity": 0
                    
                }
            ]
        },
       {
            "title": "How is the size of single piece of product?",
           
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
                    "value": "Extra large",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the shape of the product?",
            "subtitle": "Dome - Arch shaped",
            
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/68n7x79cw6a5m1ik5rld.png"]

                },
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "All sides uniform",
                    "is_intensity": 0
                },
                {
                    "value": "Domed",
                    "is_intensity": 0
                },
                {
                    "value": "Broken rusk",
                    "is_intensity": 0
                },
                {
                    "value": "Wavy sides",
                    "is_intensity": 0
                },
                {
                    "value": "Partially separated crust",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                    "is_intensity": 0
                    
                }
            ]
        },
        {
            "title": "How does the crumb appear to you?",
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/52gsgj5kplydcw9preocre.png"]

                },
           
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Very dense (Compact)",
                    "is_intensity": 0
                },
                {
                    "value": "Dense",
                    "is_intensity": 0
                },
                {
                    "value": "Balanced",
                    "is_intensity": 0
                },
                {
                    "value": "Airy",
                    "is_intensity": 0
                },
                {
                    "value": "Very airy (Porous)",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How does the product appear to you?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Evenly baked",
                    "is_intensity": 0
                },
                {
                    "value": "Unevenly baked",
                    "is_intensity": 0
                },
                {
                    "value": "Burnt",
                    "is_intensity": 0
                },
                {
                    "value": "Spots",
                    "is_intensity": 0
                },
                {
                    "value": "Local bakery-made",
                    "is_intensity": 0
                },
                {
                    "value": "Factory-made",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                    "is_intensity": 0
                    
                }
            ]
        },
        {
            "title": "Hold the product in your hand. What do you observe?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Dry",
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
                    "value": "Oily",
                    "is_intensity": 0
                },
                {
                    "value": "Moist",
                    "is_intensity": 0
                },
                {
                    "value": "Loose particles",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    
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
        }
    ],

    "AROMA": [
        {
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
            "can_select_parent": 0,
            "nested_option_list": "ICE_TEA_AROMA_LIST",
            "nested_option_title": "AROMAS",
            "nested_option_type":1
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
        }
    ],
   

    "TASTE": [{
            "title": "Which Basic tastes have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/a9gs3r2bt1kpxo26zhmkg.png"]

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
            "title": "Which Ayurvedic tastes have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/5pvwqbsi9bbpamvl612nab.png"]

                },
            "option": [
              
              {
              
                    "value": "Astringent (Dryness - Raw Banana)",
                     
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
                    "value": "No Ayurvedic Taste",
                    
                    "option_type": 2,
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
        }
    ],

    "Aromatics To Flavor": [
      {
            "title": "What all aromatics have you sensed?",
            "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",
            "select_type": 2,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/7vdbdanshc3h22fymkdxo4.png"]

                },
            "is_intensity": 1,
            "intensity_type": 2,
             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
            "is_nested_question": 0,
            "is_mandatory": 1,
            "is_nested_option": 1,
             "can_select_parent": 0,
            "nested_option_type":1,
           
            "nested_option_title": "AROMATICS",
            "nested_option_list": "ICE_TEA_AROMA_LIST"
        },
{
            "title": "Please swallow the product and pause. How is the aftertaste?",
          
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
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
            "title": "How is the flavor experience?",
            "subtitle":"Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
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
        }
    ],

    "MOUTHFEEL": [
      {
            "title": "Take sufficient quantity of the product. Bite 2-3 times and pause. What kind of sound do you hear?",
            "subtitle":"Crispy- one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events,e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product,e.g., Puffed rice",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
              {
              
                    "value": "Crispy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Crunchy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Crackly",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "No Sound",
                      "option_type": 2,
                    "is_intensity": 0
                   
                }
                
                
            ]
        },
      {
            "title": "How much force is required to chew the product?",
           
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
                    "value": "Mild",
                    "is_intensity": 0
                },
                 {
                    "value": "Moderate",
                    "is_intensity": 0
                },
                {
                    "value": "Extra",
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
            "title": "As you chew, what is your opinion about the product?",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [
              {
              
                    "value": "Dense",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Airy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                }
                
            ]
        },
        {
            "title": "How does the product feel inside the mouth?",

            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Dry",
                    "is_intensity": 0
                },
                {
                    "value": "Hard",
                    "is_intensity": 0
                },
                {
                    "value": "Crunchy",
                    "is_intensity": 0
                },
                {
                    "value": "Chewy",
                    "is_intensity": 0
                },
                {
                    "value": "Prickly",
                    "is_intensity": 0
                },
                {
                    "value": "Crumbly",
                    "is_intensity": 0
                },
                {
                    "value": "Oily",
                    "is_intensity": 0
                },
                {
                    "value": "Lumpy",
                    "is_intensity": 0
                },
                {
                    "value": "Firm",
                    "is_intensity": 0
                },
                {
                    "value": "Mushy",
                    "is_intensity": 0
                },
                {
              
                    "value": "Any other (Be specific)",
                      "option_type": 1,
                    "is_intensity": 0
                   
                }
            ]
        },
      
        {
            "title": "As you chew, which of these can you sense/feel inside your mouth?",
            "subtitle":"Please chew the product 3-4 times and pause.",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
              {
              
                    "value": "Oil",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Syrup",
                     
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
              
                    "value": "Starch",
                     
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
              
                    "value": "Any other (Be specific)",
                      "option_type": 1,
                    "is_intensity": 0
                   
                }
            ]
        },
        {
            "title": "Does the product dissolve in mouth?",
           
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [
              {
              
                    "value": "Yes",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely,Very Slow,Slow,Moderate,Quick,Very Quick,Extremely Quick"
                },
                {
              
                    "value": "No",
                      "option_type": 2,
                    "is_intensity": 0
                   
                }
            ]
        },
        {
            "title": "What kind of mass is being formed?",
            "subtitle":"Take a bite of the product, chew it for minimum 8-10 times and pause.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Tight mass",
                    "is_intensity": 0
                },
                {
                    "value": "Pulpy mass",
                    "is_intensity": 0
                },
                {
                    "value": "Barely any mass",
                    "is_intensity": 0
                },
                {
                    "value": "No mass",
                    "is_intensity": 0
                }
            ]
        },
       {
            "title": "How easy or difficult is this product to swallow?",
          
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Easy",
                    "is_intensity": 0
                },
                {
                    "value": "Somewhat easy",
                    "is_intensity": 0
                },
                {
                    "value": "Moderate",
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
            "title": "After swallowing the product, do you feel anything left in the mouth?",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
              {
              
                    "value": "Oily film",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Loose particles",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Sticking on tooth",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Stuck between teeth",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Chalky",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Any other (Be specific)",
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
        }
    ],
   

    "PRODUCT EXPERIENCE": [
      
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
                    "is_intensity": 0,
                    "option_type": 2
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

        $data = ['name'=>'Public_Rusk','keywords'=>"Public_Rusk",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];

        \DB::table('public_review_global_questions')->insert($data);

        $globalQuestion = \DB::table('public_review_global_questions')->orderBy('id', 'desc')->first();

        $headerData = [];
        // header_selection_type
        // for instruction = 0  , overall preferance = 2 others = 1
        foreach ($headerInfo2 as $item)
        {
            $headerData[] = ['header_type'=>$item['header_name'],'is_active'=>1,'header_selection_type'=>$item['header_selection_type'],
                'global_question_id'=>$globalQuestion->id,'header_info'=>isset($item['header_info']) ? json_encode($item['header_info']) : null];
        }
        \Log::info($headerData);
        \DB::table('public_review_question_headers')->insert($headerData);

        $questions = $questions2;
        $questions = json_decode($questions,true);

        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('public_review_question_headers')->select('id')->where('header_type','like',$key)
                ->where('global_question_id',$globalQuestion->id)->first();
            $headerId = $header->id;
            \Log::info("header id ".$headerId);
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['is_nested_question']) && $item['is_nested_question'] == 1 ? 1 : 0;
                $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                $option = isset($item['option']) ? $item['option'] : null;
                
                if(isset($item['select_type']) && !is_null($option))
                {
                    $value = $item['option'];
                    if(is_string($value))
                    {
                        $value = explode(',',$option);
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(is_null($v) || empty($v))
                                continue;
                            if($v == 'Any other' || $v == 'any other')
                                $option_type = 1;
                            else if($v == 'none' || $v == 'None')
                                $option_type = 2;
                            else
                                $option_type = 0;
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type' => $option_type
                            ];
                            $i++;
                        }
                    }
                    else
                    {
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(!isset($v['value']))
                            {
                                continue;
                            }
                            $option[] = [
                                'id' => $i,
                                'value' => $v['value'],
                                'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0,
                                'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                            ];
                            $i++;
                        }
                    }
                }
                else
                {
                    $value = explode(',',$option);
                    $option = [];
                    $i = 1;
                    foreach($value as $v){
                        if(is_null($v) || empty($v))
                            continue;
                        if($v == 'Any other' || $v == 'any other')
                            $option_type = 1;
                        else if($v == 'none' || $v == 'None')
                            $option_type = 2;
                        else
                            $option_type = 0;
                        $option[] = [
                            'id' => $i,
                            'value' => $v,
                            'option_type'=>$option_type
                        ];
                        $i++;
                    }
                }
                if(count($option))
                    $item['option'] = $option;
                unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,
                    'questions'=>json_encode($item,true),'parent_question_id'=>null,
                    'header_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','global_question_id'=>$globalQuestion->id];
                \Log::info("question ");
                \Log::info($data);
                $x = Questions::create($data);

                $nestedOption = json_decode($x->questions);
                $extraQuestion = [];
                if(isset($nestedOption->is_nested_option))
                {
                    if($nestedOption->is_nested_option)
                    {

                        if(isset($nestedOption->nested_option_list))
                        {
                            echo $nestedOption->nested_option_list;
                            $extra = \Db::table('public_review_global_nested_option')->where('is_active',1)->where('type','like',$nestedOption->nested_option_list)->get();
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $option_type = isset($nested->option_type) ? $nested->option_type : 0;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description,'is_intensity'=>$nested->is_intensity, 'option_type'=>$option_type];
                            }
                        }
                        else if(isset($nestedOption->nested_option_array))
                        {
                            $extra = $nestedOption->nested_option_array;
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>$nested->is_active, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,
                                    'description'=>$description,'is_intensity'=>$nested->is_intensity,'option_type'=>0];
                            }
                        }
                        else
                        {
                            echo "something wrong in nested option value";
                            return 0;
                        }
                        print_r($extraQuestion);
                        \DB::table('public_review_nested_options')->insert($extraQuestion);


                        $paths = \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                            ->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                ->where('id',$path->id)->update(['path'=>$path->value,'parent_sequence_id'=>$path->sequence_id]);
                        }
                        $questions = \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',
                            $globalQuestion->id)->get();

                        foreach ($questions as $question)
                        {
                            $checknestedIds = \DB::table('public_review_nested_options')->where('question_id',$x->id)
                                ->where('global_question_id',$globalQuestion->id)
                                ->where('parent_id',$question->sequence_id)->get()->pluck('id');

                            if(count($checknestedIds))
                            {
                                $pathname =  \DB::table('public_review_nested_options')->where('question_id',$x->id)
                                    ->where('global_question_id',$globalQuestion->id)
                                    ->where('sequence_id',$question->sequence_id)->first();
                                \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                    ->whereIn('id',$checknestedIds)->update(['path'=>$pathname->path,'parent_sequence_id'=>$pathname->parent_sequence_id]);
                                \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                    ->where('id',$question->id)->update(['is_nested_option'=>1]);
                            }

                        }
                        $paths = \DB::table('public_review_nested_options')->where('question_id',$x->id)
                            ->where('global_question_id',$globalQuestion->id)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                ->where('id',$path->id)->update(['path'=>null,'parent_sequence_id'=>null]);
                        }
                    }
                }

                foreach ($subquestions as $subquestion)
                {
                    $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                    $isNested = isset($subquestion['is_nested_question']) && $subquestion['is_nested_question'] == 1 ? 1 : 0;
                    $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                    // for sub questions
                    $option = isset($subquestion['option']) ? $subquestion['option'] : null;
                    if(isset($subquestion['select_type']) && !is_null($option))
                    {
                        $value = $subquestion['option'];
                        if(is_string($value))
                        {
                            $value = explode(',',$option);
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(is_null($v) || empty($v))
                                    continue;
                                if($v == 'Any other' || $v == 'any other')
                                    $option_type = 1;
                                else if($v == 'none' || $v == 'None')
                                    $option_type = 2;
                                else
                                    $option_type = 0;
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type'=>$option_type,
                                    'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                        else
                        {
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(!isset($v['value']))
                                {
                                    continue;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v['value'],
                                    'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                    'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                    'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                    'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                    'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0,
                                    'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                    }
                    else {
                        $value = explode(',', $option);
                        $option = [];
                        $i = 1;
                        foreach ($value as $v) {
                            if (is_null($v) || empty($v))
                                continue;
                            if($v == 'Any other' || $v == 'any other')
                                $option_type = 1;
                            else if($v == 'none' || $v == 'None')
                                $option_type = 2;
                            else
                                $option_type = 0;
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type'=>$option_type,
                                'image_url'=>isset($v['image_url']) ? $v['image_url'] : null
                            ];
                            $i++;
                        }
                    }
                    if(count($option))
                        $subquestion['option'] = $option;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,
                        'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active'=>1,'global_question_id'=>$globalQuestion->id];
                    \Log::info("question sub ");
                    \Log::info($subData);
                    Questions::create($subData);

                }
            }
        }
    }
}