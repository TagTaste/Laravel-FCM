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

        ['header_name' => "APPEARANCE", "header_info" => ["text" => "To attempt the appearance section, please pour the product in a white transparent glass. Now examine the product visually and answer the questions outlined below. <b>Please don't drink in this entire section.</b>"],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are only assessing the aromas (odors through the nose), <b>so please don't drink it yet.</b> Now bring the product closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/mejjzm4i6ci71sioa2svj9.png"]],'header_selection_type'=>"1"],


        ['header_name' => "TASTE","header_info" => ["text" => "Slurp noisily and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/i152gxwjvrgcxhxj44u5.png"]],'header_selection_type'=>"1"],

['header_name' => "Aromatics To Flavor","header_info" => ["text" => "1. Slurp noisily again.\n2. Pinch your nose while keeping your mouth closed for minimum 5-6 seconds.\n3. Release and exhale through the nose.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/7aq677n1faseu077bkyeff.png"]],'header_selection_type'=>"1"],
       
        ['header_name' => "MOUTHFEEL","header_info" => ["text" => "Take a sip of the sample amounting to around 15 - 20 ml. Allow it to rest on the tongue, then distribute throughout the mouth. Lead the beverage to the back portion of the tongue and swallow. This process allows for the evaluation of texture, structure, lightness and mouthfeel."],'header_selection_type'=>"1"],

    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

 

];
        $questions2 = '{

    "INSTRUCTIONS": [{
        "title": "Instruction",
        "subtitle": "Welcome to the Product Review!\n\n<b>If a product involves stirring, shaking  (like cold coffee), serving temperature then the taster must follow the instructions fully, as mentioned on the package.</b>\n\nTo review, follow the questionnaire and select the answers that match your observations. Please <b>click (i) / \'Learn more\'</b> on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        
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
                    "value": "Steaming hot",
                    "is_intensity": 0
                }
                
            ]
        },
        {
            "title": "How is the appeal of the product?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Deep",
                    "is_intensity": 0
                },
                {
                    "value": "Bright",
                    "is_intensity": 0
                },
                {
                   "value": "Dull",
                    "is_intensity": 0
                    
                },
                {
                    "value": "Glowing",
                    "is_intensity": 0
                },
                {
                    "value": "Dark",
                    "is_intensity": 0
                },
                {
                   "value": "Neutral",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Mellow",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Pale",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Washed out",
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
                    
                }
                
            ]
        },
        {
            "title": "How is the clarity of the product?",
           
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/t8ywpx4noim180iuyr7stv.png"]

                },
                
            "option": [{
                    "value": "Clear (Transparent)",
                    "is_intensity": 0
                },
                {
                    "value": "Cloudy/ Hazy",
                    "is_intensity": 0
                },
                {
                    "value": "Opaque (Can\'t see through)",
                    "is_intensity": 0
                }
                
            ]
        },
       {
            "title": "How is the visual texture?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Plain",
                    "is_intensity": 0
                },
                {
                    "value": "Smooth",
                    "is_intensity": 0
                },
                {
                    "value": "Fizzy/ Carbonated",
                    "is_intensity": 0
                },
                {
                    "value": "Bubbly",
                    "is_intensity": 0
                },
                {
                    "value": "Sediments",
                    "is_intensity": 0
                },
                {
                    "value": "Shaved ice",
                    "is_intensity": 0
                },
                {
                    "value": "Icey",
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
            "title": "What all aroma/s have you sensed?",
            "subtitle": "Directly use the search box to select the aromas that you identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\". ",
            "select_type": 2,
            "is_intensity": 1,
            "intensity_type": 2,
             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
            "is_nested_question": 0,
            "is_mandatory": 1,
            "is_nested_option": 1,
            "can_select_parent": 1,
            "nested_option_list": "WATER_AROMA_LIST",
            "nested_option_title": "AROMAS",
            "nested_option_type":2
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
            "title": "Which Basic Taste/s have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/bdxe5oqld0ihidvgc5ovd.png"]

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
            "title": "Which Ayurvedic Taste/s have you sensed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/yv3k5hqe4wegn42busj6i.png"]

                },
            "option": [
              
              {
              
                    "value": "Astringent (Pukering - Raw Banana)",
                     
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
            "title": "Head notes are the first impression of flavor we perceive immediately but does not last long. <b>Which head note/s have you perceived?</b>",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/fdhp5gnf1z43nqc0arheld.png"]

                },
            "option": [
              
              {
              
                    "value": "Earthy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Plants/ Herbaceous",
                     
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
                    "value": "Nutty/ Milky",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Sweet",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fire/ Animal",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Spicy",
                  
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh/ Candied fruit",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Marine",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Mineral",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                   "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Absent",
                    
                    "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Body notes: Powerful and stable aromatics that give the overall impression of the tea. <b>Which head note/s have you perceived?</b>",
            "subtitle":"To experience body notes, please follow the same instructions under \'i\'/ \'Learn more\' as mentioned in the previous question.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            
            "option": [
              
              {
              
                    "value": "Earthy > Forest",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Earthy > Wood",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Plant/ Herbaceous > Grass",
                  
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Plant/ Herbaceous > Vegetables",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Plant/ Herbaceous > Herbs",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Floral > Summer meadow",
                  
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Floral > Garden flowers",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Spicy > Hot/ Cooling",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh or Candied fruit > Tropical",
                  
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh/ Candied fruit > Stone & Vine fruit",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh/ Candied fruit > Citrus",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Fresh/ Candied fruit > Berry",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Any other (Be specific)",
                    
                    "option_type": 1,
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Absent",
                    
                    "option_type": 2,
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Tail notes: Aromatics that linger in the mouth after swallowing the tea (after taste).  <b>Which tail note/s have you perceived?</b>",
            "subtitle": "To select the tail notes please follow the same process as done in Aroma.",
            "select_type": 2,
            "is_intensity": 1,
            "intensity_type": 2,
             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
            "is_nested_question": 0,
            "is_mandatory": 1,
            "is_nested_option": 1,
             "can_select_parent": 1,
            "nested_option_type":2,
           
            "nested_option_title": "AROMATICS",
            "nested_option_list": "WATER_AROMATICS_LIST"
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

    "MOUTHFEEL": [{
            "title": "How is the oral texture of the product?",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Refreshing",
                    "is_intensity": 0
                },
                {
                    "value": "Smooth",
                    "is_intensity": 0
                },
                {
                    "value": "Velvety",
                    "is_intensity": 0
                },
                 {
                    "value": "Mellow",
                    "is_intensity": 0
                },
                {
                    "value": "Bitting",
                    "is_intensity": 0
                },
                 {
                    "value": "Drying",
                    "is_intensity": 0
                },
                {
                    "value": "Weak",
                    "is_intensity": 0
                },
                 {
                    "value": "Gritty",
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
            "title": "How is the body of the product?",

            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Plain like water",
                    "is_intensity": 0
                },
                {
                    "value": "Thin",
                    "is_intensity": 0
                },
                {
                    "value": "Wispy (Weak)",
                    "is_intensity": 0
                },
                {
                    "value": "Full body",
                    "is_intensity": 0
                },
                {
                    "value": "Thick",
                    "is_intensity": 0
                }
            ]
        },
      
        {
            "title": "After ingesting the product, do you feel any mouth coating and to what extent?",
            
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
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "No",
                     
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "Other than mouth coating, do you feel anything left inside the mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
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
                    "value": "Stuck between teeth",
                    "is_intensity": 0
                },
                {
                    "value": "Chalky (powdery film)",
                    "is_intensity": 0
                },
                {
                    "value": "Any other (Be specific)",
                    "is_intensity": 0,
                    "option_type": 1
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
        }
    ],
   

    "PRODUCT EXPERIENCE": [
      
       {
            "title": "Did this product succeed in the basic senses?",
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
                    "value": "Balanced",
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

        $data = ['name'=>'Public_Iced_Tea','keywords'=>"Public_Iced Tea",'description'=>null,
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