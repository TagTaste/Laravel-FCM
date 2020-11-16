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


        ['header_name' => "APPEARANCE", "header_info" => ["text" => "Please open the wrapper (butter paper) and examine the product visually. Answer the questions outlined below. <b>Please don't eat</b> in this entire appearance section."],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are only assessing the aromas (odors through the nose), so please don't eat/ drink it yet. Now bring the product closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/7dcl0du5gurpcez5cfa97.png"]],'header_selection_type'=>"1"],


        ['header_name' => "TASTE","header_info" => ["text" => "Please eat normally to assess the temperature and tastes."],'header_selection_type'=>"1"],

['header_name' => "Aromatics To Flavor","header_info" => ["text" => "Aromatics is the odor/s of food/ beverage coming from inside the mouth.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/2bj2vyw320yvn55lzrbw0k.png"]],'header_selection_type'=>"1"],
       
        ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

];
        $questions2 = '
{

    "INSTRUCTIONS": [
      {
        "title": "Instruction",
        
        "subtitle": "<b>Welcome to the Product Review!</b>\n\nIf a product involves mixing, serving temperature instructions etc., then the taster must follow them fully, as mentioned on the packaging.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i)/ \'Learn\' on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        
        "select_type": 4

    }
    ],
    
    "Your Food Shot": [



        {




            "title": "Take a selfie with the product",



            "subtitle": "Reviews look more authentic when you post them with a photograph.",



            "select_type": 6




        }








    ],
    
    "APPEARANCE": [
      {
            "title": "How is the visual impression of the product?",
            "subtitle":"Just view the product kept on the butter paper.",
            
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
                    "value": "Toasted",
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
            "title": "Hold the product in your hand/s. How will you describe your experience?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/xlasdvxae1ng6pzqdaqev.png"]

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
                   "value": "Flattened",
                   
                    "is_intensity": 0
                    
                },
                {
                   "value": "Smooth bun",
                  
                    "is_intensity": 0
                    
                },
                {
                   "value": "Wrinkled (shrivelled) bun",
                   
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
                    "value": "Any other (Be specific)",
                    "option_type": 1,
                    "is_intensity": 0
                    
                }
                
            ]
        },
        {
            "title": "Burgers always have components with different temperatures, textures and different tastes. However, a good burger follows a sequence in stacking these components (sequence is mentioned below) from heel to crown. Has this sequence been followed?",
            "subtitle": "Heel/ Bottom Bun (toasted), Condiments (Mustard or Ketchup), Pickle/Seasoning, Patty, Cheese (melting), Tomato, Lettuce, Onion, Condiments (Mayonnaise), Top Bun/ Crown (toasted). Your burger may not have some of the components but the sequence should still be followed. Proper sequencing ensures the best sensory experience. For e.g., cheese slice should always be placed only on the hot patty so that the cheese melts.",
           
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/fmjhfgtbo17lb6s42vlk5g.png"]

                },
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
            "title": "Select options that are relevant for your product?",
       
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
                
            "option": [{
                    "value": "Slightly toasted bun",
                    "is_intensity": 0
                },
                {
                    "value": "Cheese",
                    "is_intensity": 0
                },
                {
                    "value": "Egg patty",
                    "is_intensity": 0
                },
                {
                    "value": "Onion",
                    "is_intensity": 0
                },
                {
                    "value": "Tomato",
                    "is_intensity": 0
                },
                {
                    "value": "Lettuce",
                    "is_intensity": 0
                },
                {
                    "value": "Mayonnaise / sauce",
                    "is_intensity": 0
                },
                {
                    "value": "Ketchup",
                    "is_intensity": 0
                },
                {
                    "value": "All of them",
                    
                    "option_type": 2,
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
            "title": "In terms of quantity, what is the proportion of the ingredients in the product?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            
            "option": [
              
              {
              
                    "value": "Bun",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
                },
                {
              
                    "value": "Cheese",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
                },
                {
                    "value": "Egg patty",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
                },
                {
                    "value": "Veggies",
                  
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
                },
                {
                    "value": "Condiments",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                   "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Any,Very Less,Less,Sufficient,Little Extra,Extra,Excess"
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
            "nested_option_list": "AROMA",
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
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/t1aidimrx49f7679sq5f6.png"]

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
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/s84sogo8lzism850n7g9xd.png"]

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

    "AROMATICS TO FLAVOR": [
      {
            "title": "What all aromatics have you sensed?",
            "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",
            "select_type": 2,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/2dy6cvw16goty0xcwsp39k.png"]

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
            "nested_option_list": "AROMA"
        },
{
            "title": "Please swallow the product and pause, how is the aftertaste?",
          
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
            "title": "Which <b>prominent flavor</b> do you experience inside the mouth?",
            
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Bun",
                    "is_intensity": 0
                },
                {
                    "value": "Boiled egg",
                    "is_intensity": 0
                },
                {
                    "value": "Half boiled egg",
                    "is_intensity": 0
                },
                {
                    "value": "Raw egg",
                    "is_intensity": 0
                },
                {
                    "value": "Fried egg",
                    "is_intensity": 0
                },
                {
                    "value": "Only yolk",
                    "is_intensity": 0
                },
                {
                    "value": "Onion",
                    "is_intensity": 0
                },
                {
                    "value": "Mayonnaise",
                    "is_intensity": 0
                },
                {
                    "value": "Cheese",
                    "is_intensity": 0
                },
                {
                    "value": "Spices/ Seasoning",
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
            "title": " How is the overall flavor experience?",
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

    "TEXTURE": [
      {
        
            "title": "How much force is needed to bite through the product?",
           
           
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
                    "value": "Moderate",
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
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/s84sogo8lzism850n7g9xd.png"]

                },
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
                }
            ]
        },
            {
            "title": "How does the egg patty feel inside your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Fluffy",
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
                    "value": "Firm",
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
            "title": "How is the egg patty cooked?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Well cooked",
                    "is_intensity": 0
                },
                {
                    "value": "Overcooked",
                    "is_intensity": 0
                },
                {
                    "value": "Undercooked",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "While chewing, which textures can you feel inside your mouth?",
            "subtitle": "Please select a maximum of 4 options.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Soft",
                    "is_intensity": 0
                },
                {
                    "value": "Mushy",
                    "is_intensity": 0
                },
                {
                    "value": "Crispy",
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
                    "value": "Sticky",
                    "is_intensity": 0
                },
                {
                    "value": "Gooey",
                    "is_intensity": 0
                },
                {
                    "value": "Firm",
                    "is_intensity": 0
                },
                {
                    "value": "Gritty",
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
            "title": "How easy/difficult is the product to swallow?",
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
            "title": "After swallowing the product, do you feel anything left inside the mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Oily film",
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
                    "value": "Chalky",
                    "is_intensity": 0
                },
                {
                    "value": "Dry crumb",
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


        $data = ['name'=>'public_McD_egg_burger_with_cheese','keywords'=>"public_McD_egg_burger_with_cheese",'description'=>null,

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