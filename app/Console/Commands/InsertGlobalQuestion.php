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


        ['header_name' => "APPEARANCE", "header_info" => ["text" => "Empty the package in a white bowl. Examine the product and answer the questions outlined below. <b>Please don't eat in this entire appearance section.</b>"],'header_selection_type'=>"1"],


        ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, <b>so please don't eat it yet.</b> Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/a1gaoh15hzv4hgsicr4m7h.png"]],'header_selection_type'=>"1"],


        ['header_name' => "TASTE","header_info" => ["text" => "Eat a little of this paste and assess the tastes."],'header_selection_type'=>"1"],

['header_name' => "AROMATICS TO FLAVOR","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE or instead of exhaling you can simply PINCH YOUR NOSE for minimum 5-6 seconds and release. Identify the odors that come from inside the mouth; these identified odors are called Aromatics.","images" => ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/q68nk9r6bpns5bj803k78.png"]],'header_selection_type'=>"1"],
       
        ['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

    ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]


    ];

        $questions2 = '
{

    "INSTRUCTIONS": [{
        "title": "Instruction",
        
        "subtitle": "Welcome to the Product Review!\n\nIf a product involves mixing, serving temperature instructions etc., then the taster must follow them fully, as mentioned on the packaging.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i)/ \'Learn\' on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of each section.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
        
        "select_type": 4

    }],
    
    "APPEARANCE": [{
            "title": "What is the serving temperature of the product?",
            "subtitle":"You may also touch the product to assess the serving temperature.",
            
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
            "title": "What is the color of the product?",
           
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Brown",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/nm13pnprtfcuo0porqfnq.jpg",
                    "is_intensity": 0
                },
                {
                    "value": "Coffee",
                     "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/8xapumdqxuw8djffnrhq2q.jpg",
                    "is_intensity": 0
                },
                {
                   "value": "Mocha",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/f5yz08bh3pk9k8b1fez464.jpg",
                    "is_intensity": 0
                    
                },
                {
                    "value": "Peanut",
                     "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/18xbp5hpqhh8t8vl88xwo4.jpg",
                    "is_intensity": 0
                },
                {
                    "value": "Carob",
                     "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/bfxlfkcl0fkuqjkloct94d.jpg",
                    "is_intensity": 0
                },
                {
                   "value": "Hickory",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/obst2pglfkm7pop8lduzi9.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Wood",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/rlpynwtzhgowoj20fjfepi.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Pecan",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/6hag32ak8uukngnshwira.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Walnut",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/f3w5xowwm0tc2tps1m8tal.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Caramel",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/77uakr4tco7700cz5gvtss.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Gingerbread",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/175l79uixmhibxvq7pur6v.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Syrup",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/btxn4kuktvj53bhljs0ye.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Chocolate",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/3nphkecdjfz0y6koeoqslt.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Tortilla",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/zasixzpqefz4vgxdnwxt.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Umber",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/b93egof0f599m8wi57k96n.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Tawny",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/693i41mx5ns8b1l0rih6e.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Brunette",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/rfrhi1l5br8i9o7ospxxm9.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Cinnamon",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/oziqti85bwns85if9xnf8.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Penny",
                    "image_url":"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/gucfwfxtiob9beh1aqd4xd.jpg",
                    "is_intensity": 0
                    
                },
                {
                   "value": "Cedar",
                    "image_url":" https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/g627ktgo6oe4zfilcc6i1a.jpg",
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
                    "value": "Natural",
                    "is_intensity": 0
                },
                 {
                    "value": "Processed",
                    "is_intensity": 0
                }
                
            ]
        },
        {
            "title": "How does the product feel between your fingers?",
           
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
                
            "option": [{
                    "value": "Smooth",
                    "is_intensity": 0
                },
                {
                    "value": "Sticky",
                    "is_intensity": 0
                },
                {
                    "value": "Pasty",
                    "is_intensity": 0
                },
                {
                    "value": "Oily",
                    "is_intensity": 0
                },
                {
                    "value": "Semi-gelled",
                    "is_intensity": 0
                },
                {
                    "value": "Pulpy",
                    "is_intensity": 0
                },
                {
                    "value": "Grainy",
                    "is_intensity": 0
                },
                {
                    "value": "Gritty",
                    "is_intensity": 0
                },
                {
                    "value": "Seed awareness",
                    "is_intensity": 0
                },
                {
                    "value": "Skin awareness",
                    "is_intensity": 0
                },
                {
                    "value": "Fibrous",
                    "is_intensity": 0
                },
                {
                    "value": "Slimy",
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
            "title": "How does the product drop / flow from the spoon?",
           "subtitle": "Take a teaspoonful of the product and tilt it slightly.",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Does not drop",
                    "is_intensity": 0
                },
                {
                    "value": "Flows reluctantly",
                    "is_intensity": 0
                },
                {
                    "value": "Flows slowly",
                    "is_intensity": 0
                },
                {
                    "value": "Flows moderately",
                    "is_intensity": 0
                },
                {
                    "value": "Flows quickly",
                    "is_intensity": 0
                },
                {
                    "value": "Flows slightly faster",
                    "is_intensity": 0
                },
                {
                    "value": "Flows freely (water)",
                    "is_intensity": 0
                }
            ]
        },
        {
            "title": "How is the consistency (uniformity) of the product?",
            
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            
            "option": [{
                    "value": "Homogenous",
                    "is_intensity": 0
                },
                {
                    "value": "Water separated",
                    "is_intensity": 0
                },
                {
                    "value": "Lumpy",
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
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/oltmsxj16b8kppgitb8yld.png"]

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
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/4c84c50v2oc1d92ch3x063.png"]

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
            "title": "How will you describe the acidity of this paste?",
            "subtitle": "To understand options, please go to \"i\" section.",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/6yhy9yhq4by1j6vgcqq4cw.png"]

                },
            "option": [
              
              {
              
                    "value": "Sour",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic,Moderately Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
                },
                {
                    "value": "Tart",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Tangy",
                  
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

    "AROMATICS TO FLAVOR": [
      {
            "title": "What all aromatics have you sensed?",
            "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",
            "select_type": 2,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/dashboard/images/9klac7hj64bntb23vjwdqh.png"]

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

    "TEXTURE": [
      {
            "title": "Did you experience any mouth - watering? If yes, then to what extent.",
            "subtitle":"Place appropriate quanity of this product on your tongue.",
            
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
                      "option_type": 2,
                    "is_intensity": 0
                   
                }
                
                
            ]
        },
        {
            "title": "While eating, which textures can you experience inside your mouth?",
            "subtitle":"Please select a maximum of 3 options.",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
              {
              
                    "value": "Smooth",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Pasty",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Silky",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Soft",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Pulpy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Lumpy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Sticky",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Grainy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Gritty",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Seeds",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Fibre",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
              
                    "value": "Skin",
                     
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
            "title": "How does the product <b>dissolve</b> in your mouth?",
            "subtitle": "Eat normally and pause. Please don\'t swallow the product yet.",
           
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Very quickly",
                    "is_intensity": 0
                },
                {
                    "value": "Quickly",
                    "is_intensity": 0
                },
                {
                    "value": " Moderately",
                    "is_intensity": 0
                },
                 {
                    "value": "Slowly",
                    "is_intensity": 0
                },
                {
                    "value": "Very slowly",
                    "is_intensity": 0
                },
                 {
                    "value": "Doesn\'t dissolve",
                    "is_intensity": 0
                }
            ]
        },
      
        {
            "title": "After swallowing the product, do you feel anything left inside the mouth?",
            
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
              
                {
              
                    "value": "Loose particles",
                     
                    "is_intensity": 0
                },
                {
              
                    "value": "Sticking on tooth / palate",
                     
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
              
                    "value": "Starchy",
                     
                    "is_intensity": 0
                },
                {
              
                    "value": "Oily mouth coating",
                     
                    "is_intensity": 0
                },
                
                {
              
                    "value": "No residue",
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
            "title": "In your opinion, this product is suitable to make which of the following foods/ beverages?",
            "select_type": 2,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Saunth",
                    "is_intensity": 0
                },
                {
                    "value": "Sambhar",
                    "is_intensity": 0
                },
                {
                    "value": "Beverage (Tamarind juice)",
                    "is_intensity": 0
                },
                {
                    "value": "Tamarind rice",
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

        $data = ['name'=>'BEC_private_tamarind_paste_v1','keywords'=>"BEC_private_tamarind_paste_v1",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true),'track_consistency'=>$track_consistency];
        \DB::table('global_questions')->insert($data);


    }
}