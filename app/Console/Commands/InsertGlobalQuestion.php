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
   ['header_name' => "Your Food Shot",'header_selection_type' => "3"],

   ['header_name' => "Appearance", "header_info" => ["text" => "Observe the Loaf of Bread and the Bread slice served on the plate and answer the questions.\nPlease <b>do not eat</b> in this entire appearance section.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/xv83ui7t8cbkrvygpg3uk.jpeg"]],'header_selection_type'=>"1"],

   ['header_name' => "Aroma","header_info" => ["text" => "At this stage, we are assessing only Aromas (odors) through the nose, so<b> please don't eat yet</b>. Now break the bread into two and bring it closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/uqdkbh81brajnfw3jlx2bc.jpeg"]],'header_selection_type'=>"1"],


   ['header_name' => "Taste","header_info" => ["text" => "Take the bite of the sample (including Crust and Crumb) Chew it 7-10 times.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/oai0nu9xyx315iyct44s.jpg"]],'header_selection_type'=>"1"],


   ['header_name' => "Aromatics To Flavor","header_info" => ["text" => "Aromatics is the odor/s of food/ beverage coming from inside the mouth. Take the bite of the sample (including Crust and Crumb) Chew it 7-10 times.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/zu55nivcnodxfwjcx0q6.jpeg"]],'header_selection_type'=>"1"],

   ['header_name' => "Texture","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

['header_name' => "Product Experience","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]


        ];

        $questions2 = '
        {
            "Instructions": [{
                "title": "Instruction",
                "subtitle": "Welcome to the Product Review!\n\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) for clarity in certain questions.\n\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of every section. \n\n\nRemember, there are no right or wrong answers.\n\nPlease, look at the Loaf of Bread and slices served to you.\nLet\'s Start.\nIn this tasting process, the top and bottom most slice should be excluded. ",
                
                "select_type": 4
            }],
            "Your Food Shot": [
                {
                    "title": "Take a selfie with the product",
                    "subtitle": "Reviews look more authentic when you post them with a photograph.",
        "placeholder_image": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png",
                    "select_type": 6
                }
            ],
            "Appearance": [
        
                
                {
                    "title": "Loaf - Crust - Color:\nWhat different colors can you spot on the crust of this loaf?",
                   
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Brown",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Very Light, Light, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                        
                       {
                            "value": "Beige",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Very Light, Light, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Yellow",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Very Light, Light, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "White",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Very Light, Light, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                     
                        {
                            "value": "Any Other (Be Specific)",
                             "option_type": 1,
                             "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Very Light, Light, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        }
                    ]
                },
                {
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },{
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
               {
                    "title": "Loaf :\nHow is the visual impression of this loaf of bread?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                      {
                            "value": "Bright",
                            "is_intensity": 0
                        },
                        {
                            "value": "Dull",
                            "is_intensity": 0
                        },
                        {
                            "value": "Glossy Shine",
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
                            "value": "Fresh ",
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
                    "title": "Loaf - Crust\nIs there any Glossy Shine on the crust?",
                   
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
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
                    "title": "Loaf - Crust - Visual Roughness:\nWill you call this crust Smooth or Rough and to what extent? ",
                   
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Smooth",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Rough",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        }
        
                    ]
                },
                {
                    "title": "Loaf - Shape:\nExamine the loaf of the bread from all sides. What is your impression about the sides of the bread?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "Uniform, like a box",
                            "is_intensity": 0
                        },
                        {
                            "value": "Non Uniform, Domed",
                            "is_intensity": 0
                        },
                        
                         {
                                    "value": "Non Uniform has defects ( Please Specify)",
                                     "option_type": 1,
                                    "is_intensity": 0
                                }
                        
                    ]
                },
              {
                    "title": "Loaf - Crust :\nTouch the crust of the loaf gently. What do you feel?",
                   
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Moist",
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
                            "value": "Sticky",
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
                            "value": "Loose Particles",
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
                    "title": "Crust -\nAssess the crust in this slice of bread. Is the crust flimsy, thin or flaky like paper?",
                    "subtitle": "The amount of baking and the handling of the bread affects the crust thickness and its qualities.",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                     "info": {
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/lj6mp3bg9sn3e2iz8rqiah.jpeg"]
                    },
        
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "Thin like Paper",
                            "is_intensity": 0
                        },
                        {
                            "value": "Flaky ",
                            "is_intensity": 0
                        },
                        {
                            "value": "Flimsy like Paper",
                            "is_intensity": 0
                        },
                       {
                            "value": "Moderately Thick",
                            "is_intensity": 0
                        },
                       {
                            "value": "Thick",
                            "is_intensity": 0
                        },
                        {
                            "value": "Very Thick",
                            "is_intensity": 0
                        }               
                    ]
                },
                {
                    "title": "Slice - \nIs there a gap between the crust and the crumb of this slice?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "No Gap",
                            "is_intensity": 0
                        },
                        {
                            "value": "Yes, there is a Little Gap",
                            "is_intensity": 0
                        },
                        {
                            "value": "Yes, there is a Lot of Gap",
                            "is_intensity": 0
                        }              
                    ]
                },
                 {
                    "title": " Slice - Thickness:\nFocus on the slice as a whole, how thick/thin is it in your opinion?",
                    "select_type": 2,
                    "is_intensity": 0,
                       "info": {
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/ivtacwtlfi80ca1h0pkd7.png"]
                    },
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "Very Thin ",
                            "is_intensity": 0
                        },
                        {
                            "value": "Thin",
                            "is_intensity": 0
                        },
                        {
                            "value": "Moderate",
                            "is_intensity": 0
                        },
                       {
                            "value": "Thick",
                            "is_intensity": 0
                        },
                        {
                            "value": "Very Thick",
                            "is_intensity": 0
                        }
                    ]
                },
                  {
                    "title": "Hold one slice of bread in your hand and assess the weight. How heavy does it feel in comparison to its size?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "It is Heavy for its size",
                            "is_intensity": 0
                        },
                        {
                            "value": "It is Light for its size",
                            "is_intensity": 0
                        }            
                    ]
                },
                {
                    "title": "Fold a slice of bread in half and assess the line along the fold. How does it behave?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [
                        {
                            "value": "Flexible/Pliant",
                            "is_intensity": 0
                        },
                       {
                            "value": "Stiff",
                            "is_intensity": 0
                        },
                       {
                            "value": "Breaks Partially",
                            "is_intensity": 0
                        },
                        {
                            "value": "Breaks",
                            "is_intensity": 0
                        }            
                    ]
                },
                {
                    "title": "Crumb:\nWhat is the colour of the crumb?",
                   
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "White",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": " Very Light, Light, Mild, Moderate, Intense, Very Intense , Extremely Intense"
                        },
                     {
                            "value": "Yellow",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": " Very Light, Light, Mild, Moderate, Intense, Very Intense , Extremely Intense"
                        },
                      {
                            "value": "Beige",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": " Very Light, Light, Mild, Moderate, Intense, Very Intense , Extremely Intense"
                        },
                      {
                            "value": "Brown",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": " Very Light, Light, Mild, Moderate, Intense, Very Intense , Extremely Intense"
                        },
                        {
                                    "value": "Any Other (Be Specific)",
                                     "option_type": 1,
                                     "is_intensity": 1,
                                    "intensity_type": 2,
                                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": " Very Light, Light, Mild, Moderate, Intense, Very Intense , Extremely Intense"
                                }
        
                    ]
                },
                {
                    "title": "What is the most prominent feature of the cell structure of this crumb?",
                       "subtitle": "Fine - Sponge like with small cells, closely packed.\nOpen - Sponge like with large cells ( same or different sizes), loosely packed.\nPorous - Small holes through which air or liquid may pass.\nTunnel - A large hole, forming a passage through which even solid can pass",
                 
                   "info": 
                        {
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/r7jjj937y3l647afvznxrv.jpeg"]
                        },
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Fine cells & Uniform All Over",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },
                      {
                            "value": "Open Cells & Uniform All Over",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },
                      {
                            "value": "Porous Holes",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },{
                            "value": "Tunnel",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },
                      {
                            "value": "Dense & Tense Uniform All Over",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },
                        {
                            "value": "Dough like & Non Uniform",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                        },
                      {
                                    "value": "Any Other (Be Specific)",
                                     "option_type": 1,
                                     "is_intensity": 1,
                                    "intensity_type": 2,
                                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                    "intensity_value": "Barely Any, Very Less, Less, Sufficient, Little Extra, Extra, Excess"
                                }
        
                    ]
                },
                {
                    "title": "Now touch the Bread slice (crumb) gently in centre. You can move your fingers gently on the Bread slice (crumb). How does it feel to you?",
                   
                     "is_intensity": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "select_type": 2,
        
                    
                    "option": [
                        {
                            "value": "Soft",
                            "is_intensity": 0
                        },
                        {
                            "value": "Hard",
                            "is_intensity": 0
                        },
                        {
                            "value": "Rigid",
                            "is_intensity": 0
                        },
                       {
                            "value": "Moist",
                            "is_intensity": 0
                        },
                        {
                            "value": "Sticky",
                            "is_intensity": 0
                        },
                       {
                            "value": "Dry & Crumbly",
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
                            "value": "Loose Particles on the Surface",
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
                    "title": "Press down the center of the bread using your forefinger with moderate force. Assess the ability of the crumb to resume shape. How does it behave?",
                  "subtitle": "Elastic crumb will bounce back whereas gummy crumb will deform.",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Springy",
                            "is_intensity": 0
                        },
                        {
                            "value": "Elastic",
                            "is_intensity": 0
                        },
                        {
                            "value": "Gluey",
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
                {
                    "title": "How strong is the overall aroma of this bread?",
           
                    "select_type": 1,
                    
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [{
                            "value": "Barely Any",
                            "is_intensity": 0
                        },
                        {
                            "value": "Very Less",
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
                            "value": "Little Extra",
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
                    "title": "Which of the following feeling is more pronounced than the others in the aroma of this bread?",
                   "subtitle": "It is possible that many feelings may come over you.",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Mouth-Watering",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        
                        {
                            "value": "Inviting",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Soothing Overall",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Enjoyable Impact Inside Nose",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                         {
                            "value": "Irritation Inside Nose",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Unpleasurable",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                         {
                            "value": "No Aroma, so No Feeling ",
                            "is_intensity": 0
                        },
                       {
                            "value": "Has Aroma but No Pronounced Feeling",
                            "is_intensity": 0
                        }
                    ]
                },
                  {
                    "title": "What is your one clear perception about the Bouquet of Aromas coming from this piece of Bread?",
                 
                    "select_type": 1,
                     "info": 
                        {
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/9s3ui8ga968ed7nzfwj6sl.jpeg"]
                        },
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
                    "option": [{
                            "value": "Grain",
                            "is_intensity": 0
                        },
                        {
                            "value": "White Flour",
                            "is_intensity": 0
                        },
                        {
                            "value": "Yeast/Fermented",
                            "is_intensity": 0
                        },
                        {
                            "value": "Sour",
                            "is_intensity": 0
                        },
                        {
                            "value": "Sweet",
                            "is_intensity": 0
                        },
                        
                        {
                            "value": "Dairy",
                            "is_intensity": 0
                        },
                        {
                            "value": "Nutty",
                            "is_intensity": 0
                        },                        
                       {
                            "value": "Toasted",
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
                    "title": "Which are the Aromas that you can sense?",
                    "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any Other\" (Be Specific) and if unable to sense any aroma at all, then select \"Absent\".",
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
                    "title": "Do you sense any off-aromas that are typically not associated with the Bread?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "option": [{
                            "value": "Alcoholic",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        
                        {
                            "value": "Acidic",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Too Yeasty",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Sour Dairy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                         {
                            "value": "Rancid Oil",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Moldy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                       {
                            "value": "Musty",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Burnt",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                       {
                            "value": "Over Fermented",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Vinegar like",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                      
                         {
                            "value": "No Off Aroma",
                             "option_type": 2,
                            "is_intensity": 0
                        }
                           
                    ]
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
         
               {
                          "title": "Do you find the Bread to be sweet?",
                          "is_nested_question": 0,
                          "is_intensity": 0,
                          "is_nested_option": 0,
                          "is_mandatory": 1,
                          "select_type": 1,
                        
                          "option": [{
                                  "value": "Sweet",
                                   
                                  "is_intensity": 1,
                                  "intensity_type": 2,
                                   "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                  "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                              },
                            {
                                  "value": "Not Sweet",
                                  "is_intensity": 0
                              }
                          ]
                                     },
                            
                         {
                          "title": "Do you find any Saltiness in this Bread?  ",
                          "is_nested_question": 0,
                          "is_intensity": 0,
                          "is_nested_option": 0,
                          "is_mandatory": 1,
                          "select_type": 1,
                        
                          "option": [{
                                  "value": "Salty ",
                                   
                                  "is_intensity": 1,
                                  "intensity_type": 2,
                                   "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                  "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                              },
                            {
                                  "value": "Not Salty ",
                                  "is_intensity": 0
                              }
                          ]
                                     },
                  {
                            "title": "While eating this Bread, do you sense any Sourness?", 
                               "is_nested_question": 0,
                    "is_intensity": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "select_type": 1,
                           
                            "option": [{
                                    "value": "Sour",
                                    "is_intensity": 1,
                            "intensity_type": 2,
                            "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Acidic, Weakly Acidic, Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
        
                                },
                                {
                                    "value": "Not Sour",
                                    "is_intensity": 0
                                }
                            ]
                        },
                    
                            {
                          "title": "Do you find any Bitterness while eating this Bread?",
                          "is_nested_question": 0,
                          "is_intensity": 0,
                          "is_nested_option": 0,
                          "is_mandatory": 1,
                          "select_type": 1,
                        
                          "option": [{
                                  "value": "Bitter ",
                                   
                                  "is_intensity": 1,
                                  "intensity_type": 2,
                                   "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                  "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                              },
                            {
                                  "value": "Not Bitter ",
                                  "is_intensity": 0
                              }
                          ]
                                     },  
                  {
                          "title": "In addition to the above basic tastes, there is one more  basic taste called Umami. Do you sense any Umami in this Bread?",
                          "is_nested_question": 0,
                          "is_intensity": 0,
                          "is_nested_option": 0,
                          "is_mandatory": 1,
                          "select_type": 1,
                           "info": 
                              {
                             
                              "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/4v1jptgeusnn473ociojxh.png"]
                              },
                          "option": [
                              {
                                  "value": "Umami",
                                  "is_intensity": 1,
                                  "intensity_type": 2,
                                  "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                  "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                              },
                              {
                                  "value": "Not Umami ",
                                  "is_intensity": 0
                              }
                          ]
                      },
                              {
                    "title": "We sometimes experience a different kind of dryness in the mouth. For example while eating Gooseberries(Amla), we are not able to move the tongue freely inside the mouth and may feel constricting sensations on the inner side of the lips, gums and cheeks (puckery sensation). This feeling is called Astringent taste.Take a bite and chew it it very slowly. Do you feel any Astringent taste?",
                    "is_nested_question": 0,
                    "is_intensity": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "select_type": 1,
                     "info": 
                        {
                       
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/d5fmlnl0uutqc5kusci3kk.png"]
                        },
                    "option": [
                        {
                            "value": "Astringent-Puckery Sensation",
                          "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/ks7fasy78tbw3oc25bfhk.jpeg",
                            "is_intensity": 1,
                            "intensity_type": 2,
                            "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                          
                            "value": "No Astringent Taste",
                            "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/ccsyd5z64j9g6th36pp6qs.jpeg",
                            "is_intensity": 0
                        }
                    ]
                },
                             {
                    "title": "Some food items (like Cinnamon and Clove) when kept on the tongue, even without chewing generate heat and raise the temperature of the mouth, this is called Pungent Hot-Warming Sensation. Another sensation of burning is caused by eating red chillies, green chillies etc, this is Pungent Chilli-Burning Sensation. We can also feel cooling sensation in the mouth even when we eat food items like menthol/mint, this is called Pungent Cool-Cooling Sensation. While eating this Bread, do you feel any Pungent Taste?",
                    "is_nested_question": 0,
                    "is_intensity": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "select_type": 2,
                     "info": 
                        {
                       
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/8dvkfvfqkz71y6r5y6ivks.jpg"]
                        },
                    "option": [
                       {
                          "value": "Pungent Hot-Warming Sensation",
                          "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/qsdo6nzhdhafptirc4ybl.jpeg",
                          "is_intensity": 1,
                          "intensity_type": 2,
                           "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                          "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                      },
                    {
                          "value": "Pungent Cool-Cooling Sensation ",
                          "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/9zv9lysu5dslufkxx68esc.jpeg",
                          "is_intensity": 1,
                          "intensity_type": 2,
                           "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                          "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                      },
                     {
                          "value": "Pungent Chilli-Burning Sensation",
                          "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/i2hs767kmdtoaagerfzvn.jpeg",
                          "is_intensity": 1,
                          "intensity_type": 2,
                           "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                          "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                      },
                    
                      {
                        
                          "value": "No Pungent Taste ",
                          "image_url" : "https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/jdd79li6rufvsqd8xc06b.jpeg",
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
                                    "color_code": "#AC9000"
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
        
              {
                    "title": "How is the Flavor Experience?",
                    "subtitle": "Flavor is experienced only inside the mouth when the Taste and Aromatics (odor through the mouth) work together.",
                    "select_type": 1,
                    "is_intensity": 0,
                "info":
                       {
                       "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/hb3ndwqdl64mlob0thlfig.jpeg"]
                       },
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
                            "value": "Artificial & Unpleasant",
                           "is_intensity": 0
                        },
                        {
                            "value": "Stale & Unpleasant",
                           "is_intensity": 0
                        }
                    ]
                },
           
                {
                    "title": "Which are the aromatics that you can sense?",
                    "subtitle" : "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any Other\"(Be Specific) and if unable to sense any aromatics at all, then select \"Absent\".",
                    
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
                    "title": "How strong is the overall Flavor (Taste & Aromatics together) of this bread?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Barely Any",
                           "is_intensity": 0
                        },
                        {
                            "value": "Very Less",
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
                            "value": "Little Extra",
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
                    "title": "What is the most prominent flavor in this bread? ",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Grain",
                           "is_intensity": 0
                        },
                        {
                            "value": "White Flour",
                           "is_intensity": 0
                        },
                         {
                            "value": "Yeast/Fermented",
                           "is_intensity": 0
                        },
                        {
                            "value": "Sour",
                           "is_intensity": 0
                        },
                        {
                            "value": "Sweet",
                           "is_intensity": 0
                        },
                       {
                            "value": "Dairy",
                           "is_intensity": 0
                        },
                       {
                            "value": "Nutty",
                           "is_intensity": 0
                        },
                       {
                            "value": "Toasted",
                           "is_intensity": 0
                        },
                        {
                            "value": "Seed",
                           "is_intensity": 0
                        }
                    ]
                },
          {
                    "title": "Flavorwise, which component has succeeded in leaving an imprint on your mind?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Cracker like Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                       
                        {
                            "value": "Earthy Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Toasted Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Fermented Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Sweet Caramel",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Sweet Floral",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                           "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
        
                        },{
                            "value": "Savory Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Buttery Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Bitter Taste",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Metallic Flavor",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                        {
                            "value": "Sweet Starchy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                        {
                            "value": "Tangy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        }
                    ]
                },
           
                {
                    "title": "Please swallow the bite and pause. How is the Aftertaste?",
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
                    "title": "What is the length of the Aftertaste?",
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
                    "title": "After Swallowing, which of these continue to linger in your mouth? ",
                   
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Grain",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                     {
                            "value": "White Flour",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Yeast/Fermented",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Sour",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                             "intensity_value": "Barely Acidic, Weakly Acidic, Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
                            },
                      {
                            "value": "Sweet",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Dairy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Nutty",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Toasted",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                      {
                            "value": "Seeds",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
                        },
                        {
                            "value": "Any Other (Be Specific)",
                             "is_intensity": 1,
                              "option_type": 1,
                             "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Dislike Extremely, Dislike Moderately, Dislike Slightly, Can\'t Say, Like Slightly, Like Moderately, Like Extremely"
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
              {
                    "title": "How much force is needed to chew the product? ",
                    "subtitle": " Please chew the product 3-4 times and pause.",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Barely ",
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
                            "value": "Intense ",
                            "is_intensity": 0
                        },
                      {
                            "value": "Very Intense",
                            "is_intensity": 0
                        },{
                            "value": "Extremely Intense",
                            "is_intensity": 0
                        }
                    ]
                },
              {
                    "title": "Did you experience any Mouth Watering? If yes, then to what extent?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
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
                    "title": "Chew the product 3-4 times, which of these are being released from the Bread? ",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
                    "option": [
                      {
                            "value": "Moisture",
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
                            "value": "Creamy/Starchy Paste",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Oil/Butter",
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
                    "title": "Chew your bite a few more times (a total 8-10 times) and pause. What kind of mass is being formed? ",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Dry Mass - Difficult to Swallow",
                            "is_intensity": 0
                        },
                      {
                            "value": "Pulpy Mass - Easy to Swallow",
                            "is_intensity": 0
                        },
                      {
                            "value": "Pasty Mass - Easy to Swallow",
                            "is_intensity": 0
                        },
                      {
                            "value": "Pasty Mass - Difficult to Swallow",
                            "is_intensity": 0
                        },
                      {
                            "value": "Coarse Mass - Difficult to Swallow",
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
                    "title": "Melt-in-the-mouth is the speed with which the masticated Bread dissolves with the saliva inside your mouth. Take a fresh bite, chew it 3-4 times and pause. How fast does this bite Melt-in-the-Mouth? ",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Melts Very Fast",
                            "is_intensity": 0
                        },
                      {
                            "value": "Melts Fast",
                            "is_intensity": 0
                        },
                      {
                            "value": "Melts Moderately",
                            "is_intensity": 0
                        },
                      {
                            "value": "Melts Reluctantly",
                            "is_intensity": 0
                        },
                      {
                            "value": "Melts Slowly",
                            "is_intensity": 0
                        },
                      {
                            "value": "Melts Very Slowly",
                            "is_intensity": 0
                        },
                      {
                            "value": "Does not Melt",
                            "is_intensity": 0
                        }
                    ]
                },
             {
                    "title": "While chewing, how does the product feel inside the mouth? ",
                "subtitle": "Please select a maximum of 3 options.",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
                    "option": [
                      {
                            "value": "Soft",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                      {
                            "value": "Spongy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                      
                        {
                            "value": "Moist",
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
                            "value": "Mushy",
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
                            "value": "Gummy",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                          {
                            "value": "Wet",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Grainy & Crumbly",
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
                            "value": "Crispy & Crusty",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },  {
                            "value": "Firm",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },  {
                            "value": "Hard",
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
                    "title": "After swallowing, do you feel any Film/Mouth-Coating inside your mouth?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
                    "option": [
                      {
                            "value": "Moist/Wet ",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                        },
                        {
                            "value": "Pasty Starch",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                        },
                        {
                            "value": "Dry/Chalky Powder",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                        },  {
                            "value": "Creamy Film",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                        },  {
                            "value": "Oily ",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                        },
                       {
                            "value": "No Mouth-Coating",
                            "option_type": 2,
                            "is_intensity": 0
                        },
                        {
                            "value": "Any Other (Be Specific)",
                            "option_type": 1,
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                           "intensity_value": "Barely Any, Very Less, Less, Moderate, Little Extra, Extra, Excess"
                            
                        }
                        
                    ]
                },
               {
                    "title": "In addition to Mouth Coating, do you feel anything left inside your mouth?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
                    "option": [
                      {
                            "value": "Loose Particles",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                      {
                            "value": "Sticking on Teeth",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Sticking on Palate",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                        {
                            "value": "Stuck Between Teeth",
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "options": [
                    {
                        "value": "Apple",
                        "color_code": "#F1E6C7"
                    },
                    {
                        "value": "Banana",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    },
                    {
                        "value": "Grapes",
                        "color_code": "#D0DEEF"
                    }
                ],
                "select_type": 7,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
                "title": "Range question - How much you like the chai",
                "options": [
                    {
                        "value": "-2",
                        "label": "Chee"
                    },
                    {
                        "value": "-1",
                        "label": "Yaak"
                    },
                    {
                        "value": "0",
                        "label": "Bakwaas"
                    },
                    {
                        "value" : "1",
                        "label": "A fan"
                    },
                    {
                        "value": "2",
                        "label": "A lover"
                    },
                    {
                        "value": "3",
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
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
                            "value": "Aromatics To Flavor",
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
                    "title": "How would you rate this Bread against the one you consume regularly?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Tastier",
                            "is_intensity": 0
                        },
                        {
                            "value": "Healthier",
                            "is_intensity": 0
                        },
                        {
                            "value": "Nutritious",
                            "is_intensity": 0
                        },
                        {
                            "value": "Purer & Natural",
                            "is_intensity": 0
                        },
                        {
                            "value": "Not Tastier, But Healthier",
                            "is_intensity": 0
                        },
                      {
                            "value": "Not Healthier, But Tastier",
                            "is_intensity": 0
                        },
                        {
                            "value": "Just the same",
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
                    "title": "Would you buy this Bread after this experience?",
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Yes-Absolutely",
                            "is_intensity": 0
                        },
                        {
                            "value": "Yes-Maybe",
                            "is_intensity": 0
                        },
                        {
                            "value": "Not Decided",
                            "is_intensity": 0
                        },
                       
                        {
                            "value": "Not At All",
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
        }
        ';

        $data = [
            'name' => 'RANK_range_questionnaire', 'keywords' => "RANK_range_questionnaire", 'description' => null,
            'question_json' => $questions2, 'header_info' => json_encode($headerInfo2, true), 'track_consistency' => $track_consistency
        ];
        \DB::table('global_questions')->insert($data);
    }
}