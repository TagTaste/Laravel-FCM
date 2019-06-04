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



            ['header_name' => "Your Food Shot",'header_selection_type' => "3"],




            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Please observe appearance at 3 different stages:\n1. Dry tisanes placed on white plate.\n2. While brewing, observe the contents in the kettle.\n3. Observe the tisane liquor after straining. Please serve the tisane liquor in a plain white cup or a glass.\nExamine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],




            ['header_name'=>"AROMA","header_info"=> ["text" => "From this stage onwards, we are assessing only the product (tisane liquor) served in the transparent glass / cup.\n\nAt this stage, we are only assessing the aromas (odors through the nose coming from the tisane liquor), so please don't drink it yet. Now bring the tisane liquor closer to your nose and take a deep breath; you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the tisane liquor can be traced to the ingredients and the processes (like sun drying, machine drying, brewing etc.) which the product might have undergone."],'header_selection_type'=>"1"],






            ['header_name'=>"TASTE","header_info"=> ["text" => "Slurp noisily and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],





            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Aromatics are the odors coming from the product (tisane liquor) inside the mouth. As you slurp noisily a series of odors will reveal themselves (one by one or even together). These aromatics will be sensed and recorded by the taster as Head notes, Body notes and Tail notes. Please follow the subtitle (use of aroma list etc.) and the question specific instructions carefully.\n\nSlurp noisily again, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odor(s) that come from inside the mouth."],'header_selection_type'=>"1"],





            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel - Sense of touch on the tongue) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘feel’ may even last after the product has been swallowed. Texture (mouthfeel) is all about the joy we get from what we drink."],'header_selection_type'=>"1"],



            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]


        ];

        $questions2 = '{




	"INSTRUCTIONS": [




		{





			"title": "Instruction",





			"subtitle": "<b>Welcome to the Product Review!</b>\n\nIf a product involves stirring, shaking etc (like cold coffee) then the taster must follow the instructions fully, as mentioned on the package.\n\nTo review, follow the questionnaire and select the answers that match your observations. Please click (i) on every screen/page for guidance related to questions.\n\nAny attribute that stands out as either too good or too bad, may please be highlighted in the <b>comment box</b> at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",




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






			"title": "Please place dry tisanes on a white plate and observe its appearance. What is the dominant visual impression of the dry tisanes?",




			"select_type": 1,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Floral",






					"is_intensity": 0






				},






				{






					"value": "Leafy",






					"is_intensity": 0









				},






				{






					"value": "Bark",






					"is_intensity": 0






				},




				{









					"value": "Roots",






					"is_intensity": 0






				},






				{






					"value": "Fruits / Berries",






					"is_intensity": 0









				},






				{






					"value": "All mixed",






					"is_intensity": 0






				}






			]






		},
		{






			"title": "How do you describe these dry tisanes?",




			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Nature fresh",






					"is_intensity": 0






				},






				{






					"value": "Delicate",






					"is_intensity": 0









				},






				{






					"value": "Exotic",






					"is_intensity": 0






				},




				{









					"value": "Vibrant",






					"is_intensity": 0






				},






				{






					"value": "Sun kissed",






					"is_intensity": 0









				},






				{






					"value": "Boring",






					"is_intensity": 0






				},




				{






					"value": "Dull",






					"is_intensity": 0





				},
				{






					"value": "Dehydrated",






					"is_intensity": 0





				},
				{






					"value": "Burnt",






					"is_intensity": 0





				}






			]






		},
		{






			"title": "Rub the dry tisanes gently between your palms. What is the prominent feeling on your plams?",





			"select_type": 1,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Dry",






					"is_intensity": 0






				},






				{






					"value": "Moist",






					"is_intensity": 0









				},






				{






					"value": "Brittle (Crushing)",






					"is_intensity": 0






				},




				{









					"value": "Powdery",






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






					"value": "Hard",






					"is_intensity": 0





				}






			]






		},


		{






			"title": "Place the dry tisanes at the bottom of a transparent glass kettle and brew as per the instructions mentioned on the package. Observe the brewing process in the kettle. How does it appear?",




			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Breathtaking",






					"is_intensity": 0






				},






				{






					"value": "Mesmerising",






					"is_intensity": 0









				},






				{






					"value": "Dramatic",






					"is_intensity": 0






				},




				{









					"value": "Mysterious",






					"is_intensity": 0






				},






				{






					"value": "Refreshing",






					"is_intensity": 0









				},






				{






					"value": "Artfully blooming",






					"is_intensity": 0






				},




				{






					"value": "Dreamy",






					"is_intensity": 0





				},
				{






					"value": "Chic & elegant",






					"is_intensity": 0





				},
				{






					"value": "Rustic",






					"is_intensity": 0





				},
				{






					"value": "Washed out",






					"is_intensity": 0





				},
				{






					"value": "Forgettable",






					"is_intensity": 0





				},
				{






					"value": "Timid",






					"is_intensity": 0





				}






			]






		},

		{






			"title": "What is the serving temperature of the product?",




			"subtitle": "Stir and strain the tisane liquor and pour it in a white glass or a cup. You may also take a sip to assess the serving temperature.",





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






			"title": "How is the visual impression (color & hue) of the tisane liquor?",





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






					"value": "Jewel tone",






					"is_intensity": 0






				},






				{






					"value": "Pastel",






					"is_intensity": 0






				},



				{









					"value": "Oily",






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






				}






			]






		},






		{






			"title": "Please look through the tisane liquor. How does it appear to you?",




			"select_type": 1,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Clear (Transparent)",






					"is_intensity": 0






				},






				{






					"value": "Cloudy (Translucent)",






					"is_intensity": 0









				},






				{






					"value": "Opaque",






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


			"title": "Asssess the aromas coming from the tisane liquor. What all aromas have you sensed?",




			"subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.",






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






			"title": "Which Basic Tastes have you sensed?",






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






					"value": "Umami",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},






				{






					"value": "No Basic Taste",






					"is_intensity": 0






				}








			]






		},






		{





			"title": "Which Ayurvedic Tastes have you sensed?",






			"select_type": 2,






			"is_intensity": 0,






			"is_mandatory": 1,






			"is_nested_question": 0,









			"is_nested_option": 0,









			"option": [









				{






					"value": "Astringent (Dryness - Raw Banana)",






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












	"AROMATICS TO FLAVORS": [

		{

			"title": "Head notes are the first impression of aromatics we perceive immediately, but they do not last long. Which prominent head notes have you sensed?",
			"subtitle": "Please select a maximum of 2 options (aromatics).",


			"is_nested_question": 0,






			"is_intensity": 0,






			"is_nested_option": 0,






			"is_mandatory": 1,






			"select_type": 2,






			"option": [









				{









					"value": "Earthy",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},






				{






					"value": "Wood",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},






				{






					"value": "Vegetables",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"






				},






				{






					"value": "Grass",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},






				{






					"value": "Herbs",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},

				{






					"value": "Spices",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Tropical fruits",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Tree fruits",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Citrus",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Berries",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Nuts",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Floral",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Animal",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Marine",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Caramel",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Sweet",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Smoke",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Ash",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Mineral / Chemical",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Any other",






					"is_intensity": 1,






					"intensity_type": 2,






					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"









				},
				{






					"value": "Absent",






					"is_intensity": 0



				}



			]






		},


		{






			"title": "Body notes are powerful and stable aromatics that give the overall impression of the product (tisane liquor). Which prominent body notes have you sensed?",






			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.\nPlease select a maximum of 2 options (aromatics).",






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






			"title": "Tail notes are aromatics that linger in the mouth after swallowing the product (tisane liquor). Which tail notes have you sensed?",






			"subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.",






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






			"title": "Please ingest the product and pause. How is the aftertaste?",






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






			"title": "How is the flavor experience?",




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






			"title": "How would you express the flavor of this product (tisane liquor)?",





			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Opulent",






					"is_intensity": 0






				},






				{






					"value": "Sturdy",






					"is_intensity": 0









				},






				{






					"value": "Vigorous",






					"is_intensity": 0






				},




				{






					"value": "Candid",






					"is_intensity": 0









				},






				{






					"value": "Lively",






					"is_intensity": 0






				},




				{






					"value": "Young",






					"is_intensity": 0






				},
				{






					"value": "Summery",






					"is_intensity": 0






				},
				{






					"value": "Wintery",






					"is_intensity": 0






				},
				{






					"value": "Petite",






					"is_intensity": 0






				},
				{






					"value": "Subtle",






					"is_intensity": 0






				},
				{






					"value": "Brisk",






					"is_intensity": 0






				},
				{






					"value": "Raw",






					"is_intensity": 0






				},
				{






					"value": "Frivolous",






					"is_intensity": 0






				},
				{






					"value": "Old",






					"is_intensity": 0






				},
				{






					"value": "Complex",






					"is_intensity": 0






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






			"title": "How is the mouthfeel of the product?",



			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Velvety",






					"is_intensity": 0







				},






				{






					"value": "Mellow",






					"is_intensity": 0










				},






				{






					"value": "Silky",






					"is_intensity": 0








				},
				{






					"value": "Strong",






					"is_intensity": 0








				},
				{






					"value": "Pointed",






					"is_intensity": 0








				},
				{






					"value": "Light",






					"is_intensity": 0








				},
				{






					"value": "Rejuvenating",






					"is_intensity": 0








				},
				{






					"value": "Tingly",






					"is_intensity": 0








				},
				{






					"value": "Clean",






					"is_intensity": 0








				},
				{






					"value": "Flowy",






					"is_intensity": 0








				},
				{






					"value": "Heavy",






					"is_intensity": 0








				},
				{






					"value": "Biting",






					"is_intensity": 0








				},
				{






					"value": "Rough",






					"is_intensity": 0








				}


			]


		},




		{




			"title": "How is the body and smoothness of the product (tisane liquor)?",

			"subtitle": "Body refers to the thickness of texture of the product.\nSmoothness is the result of levels of fat suspended in the product.",


			"select_type": 1,




			"is_nested_question": 0,




			"is_nested_option": 0,




			"is_mandatory": 1,




			"is_intensity": 0,




			"option": [





				{




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






			"title": "After ingesting the product, do you feel anything left inside the mouth?",




			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [









				{









					"value": "Greasy film",






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






					"value": "Stuck between tooth",






					"is_intensity": 0






				},




				{









					"value": "Chalky",






					"is_intensity": 0







				},






				{






					"value": "Any other",






					"is_intensity": 0










				},






				{






					"value": "No residue",






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




	"PRODUCT EXPERIENCE": [




		{






			"title": "Which immediate benefits did you experience from the product (tisane liquor)?",





			"select_type": 2,






			"is_intensity": 0,






			"is_nested_question": 0,






			"is_mandatory": 1,






			"option": [





				{









					"value": "Destressing",






					"is_intensity": 0







				},






				{






					"value": "Stimulating",






					"is_intensity": 0










				},




				{









					"value": "Energy booster",






					"is_intensity": 0







				},



				{









					"value": "Bloating reduction",






					"is_intensity": 0







				},
				{









					"value": "Mood balancing",






					"is_intensity": 0







				},
				{









					"value": "Sleep inducing",






					"is_intensity": 0







				},
				{









					"value": "Health benefits (Pain relief etc.)",






					"is_intensity": 0







				},
				{









					"value": "Decongesting",






					"is_intensity": 0







				},
				{









					"value": "Any other",






					"is_intensity": 0







				},
				{









					"value": "None",






					"is_intensity": 0







				}




			]






		},




		{






			"title": "Do you feel that the product (tisane liquor) will be able to deliver the long term benefits (immunity & metabolism booster, anti- oxidant etc.) that it claims as mentioned on the package?",





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









					"value": "Can\'t say",






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






					"value": "Aromatics to flavors",






					"is_intensity": 0






				},






				{






					"value": "Texture",






					"is_intensity": 0









				},




				{






					"value": "Everything is fine",






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






}';

        $data = ['name'=>'Generic_tisanes_v1','keywords'=>"Generic_tisanes_v1",'description'=>null,
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
                            $option[] = [
                                'id' => $i,
                                'value' => $v
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
                                'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null
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
                        $option[] = [
                            'id' => $i,
                            'value' => $v
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
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,
                                    'is_active'=>1, 'global_question_id'=>$globalQuestion->id,'header_id'=>$headerId,'description'=>$description,'is_intensity'=>$nested->is_intensity];
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
                                    'description'=>$description,'is_intensity'=>$nested->is_intensity];
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
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v
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
                                    'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null
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
                            $option[] = [
                                'id' => $i,
                                'value' => $v
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