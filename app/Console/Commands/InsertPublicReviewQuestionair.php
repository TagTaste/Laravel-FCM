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



 ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],


['header_name' => "Your Food Shot", 'header_selection_type' => "3"],



 ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the food and beverage pair visually and answer the questions outlined below."],'header_selection_type'=>"1"],



['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat or drink yet.\n\nTake your nose closer to the food & beverage pair and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],


['header_name'=>"TASTE","header_info"=> ["text" => "Please take a bite of the food and drink the beverage as you would normally do while consuming this combination of food and beverage pair. Now assess the tastes.\n\nAll the basic tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more.","video_link" => "https://www.youtube.com/watch?v=H8SVTNZyuW0"],'header_selection_type'=>"1"],


['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat the food & beverage pair normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Instead of exhaling you can simply PINCH YOUR NOSE for minimum 5-6 seconds and release. Identify the odors that come from inside the mouth; these sensed odors are called Aromatics."],'header_selection_type'=>"1"],


['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat.","video_link" => "https://www.youtube.com/watch?v=rIQzJ2Mz7KY"],'header_selection_type'=>"1"],


['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor and Texture of food and beverage pair; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]

];
        $questions2 = '{



	"INSTRUCTIONS": [



		{



			"title": "Instruction",


			"subtitle": "Welcome to the Product Review!\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen / page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product (<b>Food & Beverage pair</b>) and NOT the package. Remember, there are no right or wrong answers. Let\'s start by opening the package.",



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



			"title": "What is the serving temperature of the food?",

			"subtitle": "You may also touch to assess the serving temperature.",


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



			"title": "What is the serving temperature of the beverage?",

			"subtitle": "You may also touch to assess the serving temperature.",


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






					"value": "Steaming hot",



					"is_intensity": 0



				}

			]



		},

		{


			"title": "How is the visual impression of the food & beverage pair?",


			"select_type": 2,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Exciting",



					"is_intensity": 0



				},



				{



					"value": "Balanced",



					"is_intensity": 0






				},



				{



					"value": "Plain & boring",



					"is_intensity": 0



				},



				{



					"value": "Not appealing",



					"is_intensity": 0



				},

				{



					"value": "Bubbly",



					"is_intensity": 0



				},



				{



					"value": "Vibrant",



					"is_intensity": 0



				}


			]



		},

	


		{






			"title": "Overall preference of <b>Appearance</b> (Food & beverage pair)",



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



			"title": "What all aromas have you sensed from food & beverage pair?",



			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\".",



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






			"title": "Overall preference of <b>Aroma</b> (Food & beverage pair)",






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



			"title": "Please take a bite of the food and drink the beverage as you would normally do while consuming this combination of food and beverage pair. Now assess the tastes.\n\nWhich Basic tastes have you sensed (Food & beverage pair)?",



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



					"intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic,Moderately Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"



				},



				{



					"value": "Bitter",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},



				{



					"value": "Umami",



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






			"title": "Astringent and Pungent are tastes as per Ayurveda. Which of these taste(s) have you sensed in this product (Food & beverage pair)?",

      "subtitle": "Please click (i) to develop better understanding of Astringent and Pungent Taste.",


			"select_type": 2,



			"is_intensity": 0,



			"is_mandatory": 1,



			"is_nested_question": 0,






			"is_nested_option": 0,






			"option": [






				{



					"value": "Astringent (Puckery - Raw banana)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},



				{



					"value": "Pungent (Spices / Garlic)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},



				{



					"value": "Pungent Cool Sensation (Mint)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},



				{



					"value": "Pungent Chilli",



					"is_intensity": 1,



					"intensity_type": 2,



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






			"title": "Overall preference of <b>Taste</b> (Food & beverage pair)",






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









	"AROMATICS TO FLAVORS": [

	  {



			"title": "What all aromatics have you sensed in food & beverage pair?",



			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\".",



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


			"title": "Please swallow the food and beverage pair and pause, how is the aftertaste?",


			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






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



			"option": [






				{






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


			"title": "How was the flavor experience?",

      

      "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.",

      

			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






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


			"title": "Assess the flavors of food & beverage pair. What are they like?",

      


			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Contrasting flavors",



					"is_intensity": 0



				},



				{



					"value": "Similar flavors",



					"is_intensity": 0






				}


			]



		},

	  {



			"title": "In terms of flavor, what is the impact of beverage on food?",



			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Enhancing",



					"is_intensity": 0



				},



				{



					"value": "Complimenting (Pairs well)",



					"is_intensity": 0






				},



				{



					"value": "No Change",



					"is_intensity": 0



				},

					{






					"value": "Clashing",



					"is_intensity": 0



				},



				{



					"value": "Diminishing",



					"is_intensity": 0






				},



				{



					"value": "Completely diluted",



					"is_intensity": 0



				}

			]



		},

		

		{




			"title": "Overall preference of <b>Aromatics</b> (Food & beverage pair)",



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






			"title": "Take a bite of the food and beverage pair and pause. Which prominent sound(s) do you hear?",

      "subtitle": "<b>Crispy</b>: One sharp, clean, fast, and high pitched sound. Eg., Chips.\n<b>Crunchy</b>: Multiple low pitched crushing sounds perceived as a series of small events. Eg., Rusks.\n<b>Crackly</b>: One sudden low pitched sound that brittles the product. Eg., Puffed rice.",


			"select_type": 2,



			"is_intensity": 0,



			"is_mandatory": 1,



			"is_nested_question": 0,




			"option": [






				{



					"value": "Crispy",



					"is_intensity": 0


				},



				{



					"value": "Crunchy",



					"is_intensity": 0






				},



				{



					"value": "Crackling",



					"is_intensity": 0






				},



				{



					"value": "No Sound",
                   
                    "option_type": 2,



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






			"title": "While chewing normally with food and beverage pair, what textures do you feel inside your mouth?",

      

			"select_type": 2,



			"is_intensity": 0,



			"is_mandatory": 1,



			"is_nested_question": 0,




			"option": [






				{



					"value": "Bite (Sting)",



					"is_intensity": 0


				},



				{



					"value": "Tingling (Tiny bubbles)",



					"is_intensity": 0






				},



				{



					"value": "Burning (Irritation)",



					"is_intensity": 0






				},



				{



					"value": "Numbing",



					"is_intensity": 0



				},



				{



					"value": "Chewy",



					"is_intensity": 0



				},

				{



					"value": "Dense",



					"is_intensity": 0



				},



				{



					"value": "Mushy",



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



					"value": "Any other",

          

          "option_type": 1,



					"is_intensity": 0



				}






			]



		},

		{






			"title": "Regarding the impact of food & beverage pair inside your mouth, where can you feel it and to what extent?",

      "subtitle": "Impact can come from fizziness (carbonation) of beverage, spiciness and chilliness of food.",


			"select_type": 2,



			"is_intensity": 0,



			"is_mandatory": 1,



			"is_nested_question": 0,






			"is_nested_option": 0,






			"option": [






				{



					"value": "Lips",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},



				{



					"value": "Inner cheeks",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},



				{



					"value": "Nose",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},



				{



					"value": "Palate",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},

{



					"value": "Ears",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},

				{



					"value": "Eyes",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},

				{



					"value": "Back of the throat",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},

				{



					"value": "Forehead",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},

				{



					"value": "Head",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},



				{



					"value": "Nowhere",

         

          "option_type": 2,



					"is_intensity": 0



				}






			]



		},

		{



			"title": "What do you feel while eating this food & beverage pair?",


			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Light",



					"is_intensity": 0



				},



				{



					"value": "Refreshing",



					"is_intensity": 0






				},

				{



					"value": "Lively",



					"is_intensity": 0






				},


				{



					"value": "Intense",



					"is_intensity": 0




				},

				{



					"value": "Creamy",



					"is_intensity": 0



				},

				{



					"value": "Heavy",



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



			"title": "How easy or difficult is this food & beverage pair to swallow?",

		


			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






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



			"title": "Did you feel anything left inside the mouth after swallowing this food & beverage pair?",


			"select_type": 2,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Oily film",



					"is_intensity": 0



				},



				{



					"value": "Loose particles",



					"is_intensity": 0






				},



				{



					"value": "Sticking on tooth or palate",



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



					"value": "No residue",


         "option_type": 2,


					"is_intensity": 0






				}

				

			]



		},



		{






			"title": "Overall preference of <b>Texture</b> (Food & beverage pair)",



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









	"PRODUCT EXPERIENCE": [

	  {



			"title": "Did this food & beverage pair succeed in satisfying your basic senses?",




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



			"title": "Which other beverage would you like to pair along with this food?",

"subtitle":"Please select a maximum of 2 options.",



			"select_type": 2,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [






				{






					"value": "Pepsi",



					"is_intensity": 0



				},



				{



					"value": "Mirinda",



					"is_intensity": 0






				},

				{






					"value": "Mountain Dew",



					"is_intensity": 0



				},

				{






					"value": "Pepsi Black",



					"is_intensity": 0



				},

				{






					"value": "Juice",



					"is_intensity": 0



				},

				{






					"value": "Ice tea",



					"is_intensity": 0



				},

				{






					"value": "Hot tea",



					"is_intensity": 0



				},

				{






					"value": "7 up",



					"is_intensity": 0



				},

				{






					"value": "Diet Pepsi",



					"is_intensity": 0



				},

				{






					"value": "Cold coffee",



					"is_intensity": 0



				},

					{






					"value": "Hot coffee",



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






			"title": "Overall product preference (Food & beverage pair)",



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



}';

        $data = ['name'=>'generic_burger_bev_pair_v1','keywords'=>"generic_burger_bev_pair_v1",'description'=>null,
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
                                'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0
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
                                ->where('id',$path->id)->update(['path'=>$path->value]);
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
                                    ->whereIn('id',$checknestedIds)->update(['path'=>$pathname->path]);
                                \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                    ->where('id',$question->id)->update(['is_nested_option'=>1]);
                            }

                        }
                        $paths = \DB::table('public_review_nested_options')->where('question_id',$x->id)
                            ->where('global_question_id',$globalQuestion->id)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('public_review_nested_options')->where('question_id',$x->id)->where('global_question_id',$globalQuestion->id)
                                ->where('id',$path->id)->update(['path'=>null]);
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
                                    'option_type'=>$option_type
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
                                    'option_type'=>isset($v['option_type']) ? $v['option_type'] : 0
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
                                'option_type'=>$option_type
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