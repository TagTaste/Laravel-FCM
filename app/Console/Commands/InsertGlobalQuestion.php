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
            ['header_name' => "Instructions", 'header_selection_type' => "0"],
 ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


 ['header_name' => "Appearance", "header_info" => ["text" => "Examine the Kheer visually and answer the questions outlined below. <b>Please DO NOT EAT IN THIS ENTIRE SECTION.</b>"], 'header_selection_type' => "1"],

 ['header_name' => "Aroma", "header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so <b>Please DO NOT EAT IN THIS ENTIRE SECTION.</b> Now bring the Kheer closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from Kheer can be traced to the ingredients and the processes involved.", "images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/x807wre61ymfuuqu4puq8.jpeg"]], 'header_selection_type' => "1"],


 ['header_name' => "Taste", "header_info" => ["text" => "Eat normally and assess the tastes.", "images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/z89kt9vjdrap1ja60v303a.jpg"]], 'header_selection_type' => "1"],

 ['header_name' => "Aromatics To Flavor", "header_info" => ["text" => "Aromatics is the odor/s of food/beverage coming from inside the mouth.", "images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/pnxsc38tbdhkl0p1gccgg.jpeg"]], 'header_selection_type' => "1"],

 ['header_name' => "Texture", "header_info" => ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is eaten; and it may even last after the product is swallowed. Product may make sound (add on chips/nuts), may give us joy (creamy foods), and may even cause pain or disgust (sticky/slimy foods)."], 'header_selection_type' => "1"],

 ['header_name' => "Product Experience", "header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the Overall Experience of the product on all parameters taken together."], 'header_selection_type' => "2"]




        ];

        $questions2 = '
        {
            "Instructions": [{
                "title": "Instruction",
                "subtitle": "Welcome to the  Kheer Review!  To review, follow the questionnaire and select the answers that match your observations. Please click (i) for clarity in certain questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box </b>at the end of the each section.\n\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",
                
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
                    "title": "What is the serving temperature of the Kheer?",
                    "subtitle": "You may also touch the product to assess the serving temperature.",

                    "select_type": 1,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    
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
                    "title": "How does this Kheer appeal to you?",
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
            "title": "How is the visual impression of this Kheer?",
                   "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,

            "option": [
                {
                    "value": "Bright",
                          "is_intensity": 0
                },
               {
                    "value": "Shiny & Glossy",
                          "is_intensity": 0
                },
              {
                    "value": "Smooth & Creamy",
                         "is_intensity": 0
                },
               {
                    "value": "Dull/Washed Out",
                         "is_intensity": 0
                },
               {
                    "value": "Fresh",
            "is_intensity": 0
               },
                 {
                    "value": "Natural",
                         "is_intensity": 0
                },
               {
                    "value": "Artificial",
                         "is_intensity": 0
                },
              {
                    "value": "Stale",
             "is_intensity": 0
                },
               {
                    "value": "Lumpy",
                      "is_intensity": 0
                },
               {
                    "value": "Stringy & Slimy",
                      "is_intensity": 0
                },
               {
                    "value": "Grainy",
                      "is_intensity": 0
                },
               {
                    "value": "Dry Leathery Skin on Surface",
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
            "title": "What is the color of this Kheer?",
          "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,


          
            "option": [{
                    "value": "Bright White",
                    "is_intensity": 0

                },
              {
                    "value": "Warm White",
                        "is_intensity": 0
                },
              {
                    "value": "Ivory",
                     
                                        "is_intensity": 0

              },

              {
                    "value": "Yellowish White",
                     
                                       "is_intensity": 0

                },
              {
                    "value": "Blotchy Color (Spotty)",
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
            "title": "Using a spoon, stir the Kheer in the bowl just once, pause and examine the Kheer. Which of the following options best describes this Kheer?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
          
            "option": [{
                    "value": "Smooth & Silky",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                },
{
                    "value": "Thin & Loose",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                },
              {
                    "value": "Thick & Glossy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                },
              {
                    "value": "Lumpy, Loose & Wobbly",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                },
               {
                    "value": "Thick & Caked",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
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
            "title": "Assess the stickiness of the Kheer by taking a few drops of the Kheer on your thumb and touching it with your index finger?",
                           "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Sighty Sticky",
                    "is_intensity": 0
                },
              {
                    "value": "Moderately Sticky",
                    "is_intensity": 0
                },
              {
                    "value": "Strongly Sticky",
                    "is_intensity": 0
                },
              {
                    "value": "Very Strongly",
                    "is_intensity": 0
                },
              {
                    "value": "Extremely Sticky",
                    "is_intensity": 0
                }
            ]
              },

                  {
            "title": "Take a Spoonful of the Kheer and tilt it over the same bowl. How does the Kheer behave?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Does not leave spoon after tilting",
                    "is_intensity": 0
                },
              {
                    "value": "Flows but reluctantly",
                    "is_intensity": 0
                },
              {
                    "value": "Flows smoothly but slowly",
                    "is_intensity": 0
                },
              {
                    "value": "Flows with moderate speed",
                    "is_intensity": 0
                },
              {
                    "value": "Flows freely",
                    "is_intensity": 0
                },
               {
                    "value": "Flows very freely",
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
            "title": "Which of the following feeling is more pronounced than the others? ",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
          
            "option": [{
                    "value": "Mouth Watering ",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Sweet Soothing",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Irritating ",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Unclean Sensation inside your Nose",
                     
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
                    "value": "Not Appetizing",
                     
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
                    "value": "Has Aroma but No Pronounced Feeling ",
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
            "title": "In what ways can you define the Bouquet of Aromas of this Kheer?",   
             "is_nested_question": 0,
            "is_intensity": 0,
            "is_mandatory": 1,
            "select_type": 2,

                 "info": 
                        {
                       
                        "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/izi7kj4kx4rf1srxtsm3fg.jpeg"]
                        },
        
        
            "option": [{
                    "value": "Sweet Dairy (Like Milk)",
                    "is_intensity": 0
                },
              {
                    "value": "Nutty Rice",
                    "is_intensity": 0
                },
              {
                    "value": "Scalded Milk ",
                    "is_intensity": 0
                },
              {
                    "value": "Caramelised",
                    "is_intensity": 0
                },
              {
                    "value": "Sweet Floral",
                    "is_intensity": 0
                },
              {
                    "value": "Sweet Spice (licorice)",
                    "is_intensity": 0
                },
              {
                    "value": "Bitter-Sweet ",
                    "is_intensity": 0
                },
               {
                    "value": "Medicinal",
                    "is_intensity": 0
                },
              {
                    "value": "Vegetal/Minty",
                    "is_intensity": 0
                },
              {
                    "value": "Sulphury & Putrid  ",
                    "is_intensity": 0
                },
                {
                    "value": "No Clear Perception",
                    "option_type": 2,
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
                    "title": "What are the Aromas that you can sense?",
                    "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t replace the identified aromas, select \"Any Other\" (Be Specific) and if unable to sense any aroma at all, then select \"Absent\".",
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
            "title": "Is there any aroma that is not associated with Kheer?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "No Off Aroma",
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
                    "intensity_value": "Barely Acidic, Weakly Acidic, Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
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
            "title": "We sometimes experience, a different kind of dryness in the mouth, for example while eating Gooseberries (Amla). This feeling is called Astringent taste. Do you feel any Astringent taste while or after eating this Kheer?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/ofhuncsvmhi09edmrzc4op4.jpeg"]
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
            "title": "Some food items (like Cinnamon and Clove) when kept on the tongue, even without chewing generate heat and raise the temperature of the mouth, this is called Pungent Hot-Warming Sensation. Another sensation of burning is caused by eating red chillies, green chillies etc, this is Pungent Chilli-Burning Sensation. We can also feel cooling sensation in the mouth even when we eat food items like menthol/mint, this is called Pungent Cool-Cooling Sensation.  Eat the Kheer as you normally do, can you sense any Pungent taste?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
             "info": 
                {
               
                "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/umwuoh506ycqeeo9djypuj.jpeg"]
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
                    "is_intensity": 0
                }
            ]
        },   
{
            "title": "As milk is reduced it concentrates, the sweetness of the natural sugars or Lactose and the cooking of rice in milk also adds to the sweetness of Kheer.  How best would you describe the Sweet taste of this Kheer?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Natural Sweetness",
                    "is_intensity": 0
                },
              {
                    "value": "Earthy Sweetness",
                    "is_intensity": 0
                },
              {
                    "value": "Sweetness with a Hint of Sweet Spices",
                    "is_intensity": 0
                },
              {
                    "value": "Sweetness with a Hint of Herbs",
                    "is_intensity": 0
                },
              {
                    "value": "Sweetness with a Hit",
                    "is_intensity": 0
                },
               {
                    "value": "Artificial Sweetness",
                    "is_intensity": 0
                },
               {
                    "value": "Bitter Sweet",
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
            "title": "While eating normally, which sensations do you feel inside your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
          
            "option": [{
                    "value": "Soothing",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Tingling ",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Refreshing",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Stinging",
                     
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
                    "value": "Unclean/Metallic",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                 {
                    "value": "No Sensation Felt ",
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
                    "title": "Which are the aromatics that you can sense?",
                    "subtitle" : "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t replace the identified aromatics, select \"Any Other\" (Be Specific) and if unable to sense any aromatics at all, then select \"Absent\".",
                    "info":
                       {
                       "images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/qknuiezjhibcpujpt7pp1e.png"]
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
            "title": "How is the Flavor Experience?",   
            "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.",
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
                    "value": "Bland & Flat",
                    "is_intensity": 0
                },
                {
                    "value": "Vegetal & Earthy",
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
            "title": "Flavorwise, how would you describe this Kheer?",   
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Well Rounded",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"
                },
              {
                    "value": "Simply Sweet ",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
              {
                    "value": "Bitter Sweet Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong" 
                               },
              {
                    "value": "Sweet Smell",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
              {
                    "value": "Scorched Milk",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
              {
                    "value": "Sweet Spice-Fennel/Licorice/Saunf",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"
                                },
              {
                    "value": "Oxidized/Metallic",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
                {
                    "value": "Vegetal Flavor",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
                {
                    "value": "Medicinal Flavor",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
                {
                    "value": "Sulphury & Putrid Flavor",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"  
                              },
                              {
                                "value": "Any Other (Be Specific)",
                                 "option_type": 1,
                                 "is_intensity": 1,
                                "intensity_type": 2,
                                 "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                                "intensity_value": "Very Weak, Weak, Mild, Moderate, Strong, Very Strong, Extremely Strong"
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
                    "value": "Can\'t Say",
                    "is_intensity": 0
                }
            ]
           },
            {
            "title": "What is the length of the aftertaste?",   
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
            "option": [{
                    "value": "Long & Persistent",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Sufficient",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
           
             
              {
                    "value": "Short",
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
            
                {
                    "value": "None",
                    "is_intensity": 0
                }
            ]
           },
             {
            "title": "After Swallowing, which of these continue to linger in your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [{
                    "value": "Sweet Kheer ",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                },
              {
                    "value": "Bitter Sweet Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Milky Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Scorched Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Sweet Spice-Fennel/Licorice/Saunf Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Herby/Minty Kheer",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Just Bitter",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
              {
                    "value": "Medicinal ",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                                },
             
                    {
                        "value": "Any Other (Be Specific)",
                         "option_type": 1,
                         "is_intensity": 1,
                        "intensity_type": 2,
                         "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                        "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                    }

        
            ]
             },
           {
                    "title": "Overall preference of Aromatics",
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
          "Texture": [
            {
                    "title": "Please eat Kheer normally. Do you experience any Mouth Watering? If yes, then to what extent?",
                    
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Yes",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                        {
                            "value": "No",
                           "is_intensity": 0
                        }
                      
                    ]
                },
                        {
                    "title": "Place a spoon of Kheer on your tongue, press the tongue against your palate and bring it back down. How creamy is the feeling inside your mouth?",
                    
                    "select_type": 1,
                    "is_intensity": 0,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                    "is_mandatory": 1,
                    "option": [{
                            "value": "Feels like Custard",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Feels Like Butter",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Feels Like Thick Cream",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Feels Like Cream",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                      {
                            "value": "Feels Like Whole Milk",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                         {
                            "value": "Feels Like Skim Milk",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
                        },
                        {
                            "value": "Feels Very Thin",
                            "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
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
                    "title": "Eat a spoonful of Kheer normally. Which of these are being released from the Kheer?",
                    "select_type": 2,
                    "is_intensity": 0,
                    "is_mandatory": 1,
                    "is_nested_question": 0,
                    "is_nested_option": 0,
                     
                    "option": [
                      {
                            "value": "Syrup",
                           "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        },
                      {
                            "value": "Cream",
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
                            "value": "Milk",
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
            "title": "Eat a spoonful of Kheer normally. Assess the stickiness of your lips?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,        
            "option": [{
                    "value": "Very Less",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Sufficient",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Ideal",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Extra",
                    "is_intensity": 0

                },
              {
                    "value": "Too Much",
                     
                    "is_intensity": 0

                }
            ]
        },
                         {
            "title": "Eat a spoonful of Kheer normally. Assess the viscosity, i.e., how fast does it spread inside your mouth?",
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1, 
          
            "option": [{
                    "value": "Spreads but reluctantly",
                     
                    "is_intensity": 0
                   
                },
              {
                    "value": "Spreads smoothly but slowly",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Spreads with moderate speed",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Spreads freely",
                     
                    "is_intensity": 0

                },
              {
                    "value": "Spreads Very Freely",
                     
                    "is_intensity": 0

                }
            ]
        },

{
            "title": "Slipperiness in Kheer can be because of the Fat in the Milk, Starch from the Rice or the Sugar. How does this Kheer feel on your palate?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Extremely Slippery",
                    "is_intensity": 0
                },
{
                    "value": "Very Slippery",
                    "is_intensity": 0
                },

              {
                    "value": "Moderately Slips",
                    "is_intensity": 0
                },

              {
                    "value": "Barely Slippery",
                    "is_intensity": 0
                },

              {
                    "value": "Not Slippery at all",
                    "is_intensity": 0
                }
            ]
},

            
            
              
               {
            "title": "As you continue to eat the Kheer normally, how does it feel inside your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
            "option": [
                {
                    "value": "Pasty",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "Gel Like",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "Pulpy & Firm",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "Liquidy & Weak",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "Slimy",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "Pulpy & Sandy",
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
                    "title": "How fast does the Kheer Melt-in-the-Mouth?", 
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
                            "value": "Does Not Melt",
                            "is_intensity": 0
                        }
        
                    ]
                    },
                 
        {
            "title": "Chew your bite a few more times and pause. What kind of mass is being formed?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
          
            "option": [{
                    "value": "Tight Mass-Difficult to Swallow",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
              {
                    "value": "Grainy Mass",
                     
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
            "title": "After Swallowing, did you feel any Film/Mouth-Coating inside your mouth? ",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 2,
          
            "option": [
                {
                    "value": "Yes-Buttery",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Yes-Creamy ",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Yes-Moist/Wet Coating",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Yes-Thin Milk",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
                {
                    "value": "Yes Dry-Chalky",
                    "is_intensity": 1,
                    "intensity_type": 2,
                    "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                },
               {
                    "value": "No Film",
                    "option_type": 2,
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
            "title": "After Swallowing, what is the feeling inside your mouth?",
            "is_nested_question": 0,
            "is_intensity": 0,
            "is_nested_option": 0,
            "is_mandatory": 1,
            "select_type": 1,
          
            "option": [{
                    "value": "Light ",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely, Weakly, Mildly, Moderately, Intensely, Very Intensely, Extremely Intensely"
                },
              {
                    "value": "Heavy",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely, Weakly, Mildly, Moderately, Intensely, Very Intensely, Extremely Intensely"
                },
              {
                    "value": "Refreshing ",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely, Weakly, Mildly, Moderately, Intensely, Very Intensely, Extremely Intensely"
                },
                {
                    "value": "Minty",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely, Weakly, Mildly, Moderately, Intensely, Very Intensely, Extremely Intensely"
                },
                {
                    "value": "Sweet Spice",
                     
                    "is_intensity": 1,
                    "intensity_type": 2,
                     "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                    "intensity_value": "Barely, Weakly, Mildly, Moderately, Intensely, Very Intensely, Extremely Intensely"
                },
              {
                            "value": "Any Other (Be Specific)",
                             "option_type": 1,
                             "is_intensity": 1,
                            "intensity_type": 2,
                             "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
                            "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
                        }


            ]},
            
                      
       
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
         "Product Experience": [      



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
            "title": "In one word, how do you replace the taste of this Kheer?",   
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
                    "value": "It hurts",
                    "is_intensity": 0
                },
                {
                    "value": "Bland/No Impact",
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
            "title": "Is there anything in this Kheer that is Unforgettable?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [  {
                            "value": "Yes-Positive (Please Specify)",
                             "option_type": 1,
                            "is_intensity": 0
                        },

             {
                            "value": "Yes-Negative (Please Specify)",
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
            "title": "How will you describe the sweet taste of this Kheer?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "Home Style",
                    "is_intensity": 0
                },
                 {
                            "value": "Different Taste (Please Specify)",
                             "option_type": 1,
                            "is_intensity": 0
                        }
            
            ]
           },
                        {
            "title": "Is there anything in this Kheer that makes it different from the Kheer that you normally cook in your home?",   
            "select_type": 1,
            "is_intensity": 0,
            "is_nested_question": 0,
            "is_mandatory": 1,
            "option": [{
                    "value": "No, Just the Same",
                    "is_intensity": 0
                },
                 {
                            "value": "Different Taste (Please Specify)",
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
                    "title": "Comments (Mandatory)",
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
            'name' => 'WholeEarth_Kheer_July22_api_test', 'keywords' => "WholeEarth_Kheer_July22", 'description' => null,
            'question_json' => $questions2, 'header_info' => json_encode($headerInfo2, true), 'track_consistency' => $track_consistency
        ];
        \DB::table('global_questions')->insert($data);
    }
}