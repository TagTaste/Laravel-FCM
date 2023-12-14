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
           
           
                   ['header_name' => "APPEARANCE", "header_info" => ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],
           
           
                   ['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are only assessing the aromas (odors through the nose), so please do not take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes that the product might have undergone.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/8hg706hch9i9p934spmjgv.jpg"]],'header_selection_type'=>"1"],
           
           
                   ['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/jfz477z6fz4ihadu7qj2g.jpg"]],'header_selection_type'=>"1"],
           
           ['header_name' => "Aromatics To Flavor","header_info" => ["text" => "Aromatics is the odor/s of food coming from inside the mouth.Take a bite and chew it normally.","images" => ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/hjuiriimrxfqz0qj9tvth.jpeg"]],'header_selection_type'=>"1"],
                  
                   ['header_name' => "TEXTURE","header_info" => ["text" =>"Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],
           
               ['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]

];
        $questions2 = '
		
{
	"INSTRUCTIONS": [
				{
					"title": "Instruction",
					"subtitle": "Welcome to the Product Review!\nTo review, follow the  questionnaire and select the answers that match your observations. Please click (i) for clarity on certain questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of every section.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start.",
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
				{
					"title": "What is the serving temperature of the product?",
				  "subtitle": "You may also touch the product to assess the serving temperature.",
					"select_type": 1,
					"is_intensity": 0,
					"is_nested_question": 0,
					"is_mandatory": 1,
					"option": [
						{
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
		
		
					"title": "What is the color of the product?",
		
					
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Snow white",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "White",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Off white",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Cream",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
						{
		
		
		
							"value": "Pale beige",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Beige",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
						{
		
		
		
							"value": "Yellowish",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
						{
		
		
		
							"value": "Greyish",
		
		
		
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
		
		
		
					"title": "How is the visual impression of the product?",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
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
		
		
		
		
		
		
							"value": "Artificial",
		
		
		
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
		
		
		
					"title": "What is your view about the garnishing (Warq) on the product?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Less still appealing",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Less & unappealing",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Balanced",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
		
		
		
							"value": "Excess still appealing",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Excess & unappealing",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "No Garnish",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						}
		
					]
		
		
		
				},
		
				{
		
		
		
					"title": "In terms of shape, does the product resemble authentic kaju katli (diamond shape)?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Yes",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "No",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Does not matter",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						}
		
					]
		
		
		
				},
		
				{
		
		
		
					"title": "How is the thickness/thinness of the product?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Very thin",
		
		
		
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
		
		
		
							"value": "Very thick",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						}
		
					]
		
		
		
				},
				{
		
		
		
					"title": "What is the visual Texture of the Product?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Smooth",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Gritty",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Shiny Sugar Crystals",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Crumbly",
		
		
		
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
		
		
		
					"title": "Touch the product and examine your hand. What do you observe?",
		
		
					"is_nested_question": 0,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_option": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"select_type": 2,
		
		
		
					"option": [
		
		
		
		{
		
		
		
		
		
		
							"value": "Greasy (Like ghee)",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
						{
		
		
		
		
		
		
							"value": "Oily",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		{
		
		
		
							"value": "Sticky",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
						{
		
		
		
							"value":"Dry",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
		
						{
		
		
		
							"value": "None",
		
		
				   "option_type": 2,
		
				   
		
							"is_intensity": 0
		
		
		
						}
		
		
		
					]
		
		
		
				},
		
				{
		
		
		
					"title": "Gently hold the product in your hand. How does the product behave?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Soft",
		
		
		
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
		
		
		
							"value": "Soft & mushy",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Falling apart",
		
		
		
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
		
		
		
		
		
		
					"title": "Overall preference of Appearance",
		
		
		
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
				{
		
		
		
					"title": "What is your perception about the Bouquet of Aromas coming from this product?",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
					"info": {
						"images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/1yky9wnf8ifikibou1s69.jpeg"]
					},
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Roasted Cashew",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Boiled Cashew",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Nutty",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Khoya",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Bhunna Khoya",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Desi Ghee",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Scalded Milk",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Lactose Sweetness",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Sugar Syrup",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Caramalized Sugar",
		
		
		
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
					"title": "Which of these sensations or feelings are more pronounced than the others in this product? It is possible that many sensations/feelings may or may not come over you.",
					"is_nested_question": 0,
					"is_intensity": 0,
					"is_nested_option": 0,
					"is_mandatory": 1,
					"select_type": 1,
				  
					"option": [
					  {
							"value": "Mouth-Watering ",
							 
							"is_intensity": 1,
							"intensity_type": 2,
							 "intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
						},
						 {
							"value": "Inviting ",
							 
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
							"value": "No Aroma, so No Feeling",
							"option_type": 2,
							"is_intensity": 0
						},
						 {
							"value": "Has Aroma but No Pronounced Feeling",
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
		
		
		
					"title": "Which are the Aromas that you can sense?",
		
		
		
					"subtitle": " Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any Other\" (Be Specific) and if unable to sense any aroma at all, then select \"Absent\".",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 1,
		
		
		
					"intensity_type": 2,
		
		
		
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_option": 1,
		
		
		
					"nested_option_title": "AROMAS",
		
		
		
					"nested_option_list": "AROMA"
		
		
		
				},
		
		
		
				{
		
		
		
		
		
		
					"title": "Overall preference of Aroma",
		
		
		
		
		
		
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
		
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
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Salt",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Sour",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Bitter",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
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
					"title": "In addition to the above basic tastes, there is one more  basic taste called Umami. Do you sense any Umami?",
					"is_nested_question": 0,
					"is_intensity": 0,
					"is_nested_option": 0,
					"is_mandatory": 1,
					"select_type": 2,
					"info": {
						"images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/djpf4stlsdqi8a3a66qkb.jpg"]
					},
				  
					"option": [{
							  "value": "Umami",
								"is_intensity": 1,
							"intensity_type": 2,
							"intensity_color": "#FCEFCC,#FAE7B2,#F9DF99,#F7D77F,#F4C74C,#F2BC26,#EDAE00",
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
						},
					   {
							"value": "Not Umami",
							"option_type": 2,
							"is_intensity": 0
						}
					]
		  },
		
			  {
					"title": "We sometimes experience a different kind of dryness in the mouth. For example while eating Gooseberries(Amla), we are not able to move the tongue freely inside the mouth and may feel constricting sensations on the inner side of the lips, gums and cheeks (puckery sensation). This feeling is called Astringent taste. Take a bite and chew it it very slowly. Do you feel any Astringent taste?",
					"is_nested_question": 0,
					"is_intensity": 0,
					"is_nested_option": 0,
					"is_mandatory": 1,
					"select_type": 1,
					 "info": 
						{
					   
						"images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/wfro4isy038fwgv0p33gj4.jpg"]
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
					"title": "Some food items (like Cinnamon and Clove) when kept on the tongue, even without chewing generate heat and raise the temperature of the mouth, this is called <b>Pungent Hot-Warming Sensation</b>. Another sensation of burning is caused by eating red chillies, green chillies etc, this is <b>Pungent Chilli-Burning Sensation.</b> We can also feel cooling sensation in the mouth even when we eat food items like menthol/mint, this is called <b>Pungent Cool-Cooling Sensation.</b>While eating this product, do you feel any Pungent Taste?",
					"is_nested_question": 0,
					"is_intensity": 0,
					"is_nested_option": 0,
					"is_mandatory": 1,
					"select_type": 1,
					 "info": 
						{
					   
						"images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/o99hre8ea60ocih2crtgm.jpg"]
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
		
		
		
					"title": "Where do you feel the impact of the sweetnes?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Lips",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Tongue",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Palate",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Inner Cheeks",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
						{
		
		
		
							"value": "Teeth",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
						{
		
		
		
							"value": "Back of Throat",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						}
					]
		
		
		
				},
		
				{
		
		
		
		
		
		
					"title": "Overall preference of Taste",
		
		
		
		
		
		
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
		
			   {
					"title": "How is the flavor experience?",   
					"subtitle": "Flavor is experienced only inside the mouth when the Taste and Aromatics (odor through the mouth) work together.",
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
							"value": "Artificial & Unpleasant",
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
		
		
		
					"title": "Which are the aromatics that you can sense?",
		
		
		
					"subtitle": "Directly use the search box to select the aromatics that you have observed or follow the category based aromatics list. In case you can\'t find the observed aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",
					"info": {
						"images": ["https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/dashboard/images/zath138b7uhfsccnxd2e4.png"]
					},
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 1,
		
		
		
					"intensity_type": 2,
		
		
		
					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_option": 1,
		
		
		
					"nested_option_title": "AROMATICS",
		
		
		
					"nested_option_list": "AROMA"
		
		
		
				},
		{
		
		
		
		
		
		
					"title": "Flavorwise, which components are leaving an imprint on your mind?",
		
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_question": 0,
		
		
		
		
		
		
					"is_nested_option": 0,
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
							"value": "Roasted Cashew",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Boiled Cashew",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Nutty",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
						"value": "Khoya",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						  {
		
		
		
						"value": "Bhunna Khoya",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						  {
		
		
		
						"value": "Desi Ghee",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						{
		
		
		
							"value": "Scalded Milk",
			
			
			
								"is_intensity": 1,
			
			
			
								"intensity_type": 2,
			
			
			
								"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
			
			
			
			
							},
						   {
		
		
		
						"value": "Lactose Sweetness",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						   {
		
		
		
						"value": "Sugar Syrup",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						   {
		
		
		
						"value": "Caramalized Sugar",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
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
		
		
		
					"title": "Please swallow the bite and pause, how is the aftertaste and what is the length of the aftertaste? ",
		
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Pleasant & Long",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Pleasant & Sufficient",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
					  {
		
		
		
							"value": "Pleasant & Short",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
					 
					  {
		
		
		
							"value": "Unpleasant & Long",
		
		
		
							"is_intensity": 0
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Unpleasant & Short",
		
		
		
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
		
		
		
		
		
		
					"title": "After swallowing, which of these flavors continue to linger in your mouth?",
		
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_question": 0,
		
		
		
		
		
		
					"is_nested_option": 0,
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
							"value": "Ghee",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},

						  {
		
		
		
							"value": "Cashew",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Sugar",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Milk",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Khoya",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Metallic Flavor",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Cardboard",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Rancid",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						},
						  {
		
		
		
							"value": "Sulphur",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Any, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"
		
		
		
						}
		
					]
		
		
		
				},
				{
		
		
		
		
					"title": "Overall preference of Aromatics",
		
		
		
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
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
		
		
				{
		
		
		
					"title": "How much <b>force</b> is needed to chew the product?",
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
							"value": "Barely Any",
		
		
		
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
		
		
		
							"value": "Intense",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
						{
		
		
		
							"value": "Very intense",
		
		
		
							"is_intensity": 0
		
		
		
						},
		
						{
		
		
		
							"value": "Extremely intense",
		
		
		
							"is_intensity": 0
		
		
		
						}
		
					]
		
		
		
				},
		
		{
		
		
		
		
		
		
					"title": "As you chew, which of these are being released from the product?",
		
		"subtitle": "Please chew the product 3- 4 times and pause.",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_question": 0,
		
		
		
		
		
		
					"is_nested_option": 0,
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
							"value": "Cashew Cream",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Grease",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Moisture",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
						"value": "Dry",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						  {
		
		
		
						"value": "Oil",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
						  {
		
		
		
						"value": "Syrup",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
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
		
		
		
		
		
		
					"title": "While chewing, how does the product feel inside the mouth?",
		
				
		
					"subtitle": "Please select a maximum of 4 option.",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_question": 0,
		
		
		
		
		
		
					"is_nested_option": 0,
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
							"value": "Smooth",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Soft",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Chewy",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Lumpy",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
						"value": "Sticky",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Mushy",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Grainy",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Gritty",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Rubbery",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Firm",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Hard",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
						},
		
						{
		
		
		
						"value": "Any Other (Be Specific)",
		
		
				 "option_type": 1,
		
				 
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
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
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
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
		
		
		
		
		
		
					"title": "Do you feel any mouth coating inside your mouth and to what extent?",
		
		
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
		
		
		
		
		
		
					"title": "After swallowing, do you feel any Mouth Coating inside your mouth and to what extent?",
		
		
					"select_type": 2,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"is_nested_question": 0,
		
		
		
		
		
		
					"is_nested_option": 0,
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
							"value": "Creamy Cashew",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Desi Ghee",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Milky",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Dry & Chalky Coating",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
						{
		
		
		
							"value": "Sticky & Starchy Feeling",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
						{
		
		
		
							"value": "Nutty Paste",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
					  {
		
		
		
							"value": "Sugary Syrup",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
					  {
		
		
		
							"value": "Oil/Grease",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
			{
		
		
		
						"value": "No Mouth Coating",
		
		
				 "option_type": 2,
		
		
							"is_intensity": 0
		
		
						},
						{
		
		
		
						"value": "Any Other (Be Specific)",
		
		
				  "option_type": 1,
		
				  
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
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
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Sticking on Teeth",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Sticking on Palate",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
		
		
		
						},
		
		
		
						{
		
		
		
							"value": "Stuck Between Teeth",
		
		
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
							"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"
		
		
		
						},
		
					
		
						
		
						{
		
		
		
						"value": "Any Other (Be Specific)",
		
		
				  "option_type": 1,
		
				  
		
							"is_intensity": 1,
		
		
		
							"intensity_type": 2,
		
		
		
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
		
		
		
		
		
		
					"title": "Overall preference of Texture",
		
		
		
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
	"PRODUCT EXPERIENCE": [{
                "title": "Rank question - Fav fruit",
                "max_rank": 3,
                "option": [
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
                "option": [
                    {
                        "value": -2,
                        "label": "Chee"
                    },
                    {
                        "value": -1,
                        "label": "Yaak"
                    },
                    {
                        "value": 0,
                        "label": "Bakwaas"
                    },
                    {
                        "value" : 1,
                        "label": "A fan"
                    },
                    {
                        "value": 2,
                        "label": "A lover"
                    },
                    {
                        "value": 3,
                        "label": "Life"
                    }
                ],
                "select_type": 8,
                "is_intensity": 0,
                "is_mandatory": 1,
                "is_nested_question": 0
            },
            {
		
		
		
					"title": "Does this product succeed in satisfying your basic senses?",
		
		
		
		
					"select_type": 1,
		
		
		
					"is_intensity": 0,
		
		
		
					"is_nested_question": 0,
		
		
		
					"is_mandatory": 1,
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
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
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
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
		
		
		
		
		
		
					"option": [
		
		
		
		
		
		
						{
		
		
		
		
		
		
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
		
		
		
			]
 }
                ';

 	
        $data = ['name'=>'updated_rank_range_questionnaire','keywords'=>"rank_range_questionnaire",'description'=>null,

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
                $maxRank = isset($item['max_rank']) ? $item['max_rank'] : null;

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
                    'questions'=>json_encode($item,true),'parent_question_id'=>null,'max_rank'=>$maxRank,
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
                            $extra = \Db::table('global_nested_option')->where('is_active',1)->where('type','like',$nestedOption->nested_option_list)->whereNull("deleted_at")->get();
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $option_type = isset($nested->option_type) ? $nested->option_type : 0;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description,'is_intensity'=>$nested->is_intensity, 'option_type'=>$option_type, 'pos'=>$nested->pos];
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