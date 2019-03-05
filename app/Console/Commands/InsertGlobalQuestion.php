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
        $headerInfo2 = $headerInfo2 = [



            ['header_name'=>"INSTRUCTIONS"],



            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the section."]],




            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."]],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat sufficient quantity of the product and assess the taste/s.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more.\n\nAnything that stands out as either too good or too bad, may please be highlighted in the Comment Box."]],





            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Unlike aromas, aromatics are the odors that reach the sensors of the nose from inside the mouth (reverse action).\nReverse Action - As we eat with our mouth closed, food releases odors. These odors are sensed by us as they travel to the back of the throat and then turn up towards the sensors of the nose.\nPlease take a bite again, eat normally, keeping your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth."]],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is chewed, and it may even last after the product is swallowed. Product may make sound (chips), may give us joy (creamy foods) and may even cause pain or disgust (sticky/slimy foods).\n\nAnything that stands out as either too good or too bad, may please be highlighted in the Comment Box."]],





            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavors and Texture; rate the overall experience of the product on all the parameters taken together."]]



        ];

        $questions2 = '{


	"INSTRUCTIONS": [



		{



			"title": "Instruction",



			"subtitle": "Welcome to the Product Review!\n\nTo review, follow the questionnaire and select the answers that match with your observations.\n Please note that you are reviewing the product and NOT the package.\nPlease click (i) on every screen/page for guidance related to questions.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.",



			"select_type": 4



		}



	],







	"APPEARANCE": [



		{



			"title": "What is the serving temperature of the product?",

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



					"value": "Lukewarm",



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



			"title": "Visual Observation",



			"subtitle": "Visually observe the product (without removing the liner from the muffin) and answer the questions.",



			"is_nested_question": 1,



			"question": [



				{



					"title": "How is the visual impression of the product? (Color and sheen)",


					"select_type": 2,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [







						{



							"value": "Dull",



							"is_intensity": 0







						},



						{



							"value": "Bright",



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



							"value": "Dark",



							"is_intensity": 0



						},

						{



							"value": "Artificial",



							"is_intensity": 0



						},



						{



							"value": "Natural",



							"is_intensity": 0



						}



					]



				},



				{



					"title": "How is the distribution of color on the surface of the product?",



					"select_type": 1,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [



						{



							"value": "Even",



							"is_intensity": 0







						},



						{



							"value": "Uneven",



							"is_intensity": 0



						}



					]



				},


				{



					"title": "How is the surface texture of the product?",


					"select_type": 2,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [



						{



							"value": "Moist",



							"is_intensity": 0







						},



						{



							"value": "Dry",



							"is_intensity": 0



						},



						{



							"value": "Sticky",



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



							"value": "Crumbled",



							"is_intensity": 0



						},

						{



							"value": "Blistered",



							"is_intensity": 0



						},


						{



							"value": "Cracked",



							"is_intensity": 0



						}



					]



				},





				{



					"title": "How is the shape of the crust (top of the product)?",



					"select_type": 1,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [



						{



							"value": "Perfect rising",



							"is_intensity": 0



						},



						{



							"value": "Mushroom like",



							"is_intensity": 0







						},

						{



							"value": "Separated crust",



							"is_intensity": 0



						},



						{



							"value": "Flat",



							"is_intensity": 0







						},

						{



							"value": "Collapsed dome",



							"is_intensity": 0



						}


					]



				},

				{



					"title": "How spongy is the product?",

					"subtitle": "Place the index finger in the centre of the product and press down with moderate force.",


					"select_type": 1,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [



						{



							"value": "Hard",



							"is_intensity": 0



						},



						{



							"value": "Dense",



							"is_intensity": 0



						},

						{



							"value": "Fluffy",



							"is_intensity": 0



						},



						{



							"value": "Crumbly",



							"is_intensity": 0







						}

					]



				}


			]


		},


		{



			"title": "Cross Section Observation",



			"subtitle": "Now remove the liner of the product and break the product into two halves. Assess the filling of the product.",



			"is_nested_question": 1,



			"question": [



				{



					"title": "How is the visual impression of the filling? (Color and sheen)",



					"select_type": 2,



					"is_intensity": 0,



					"is_nested_question": 0,



					"is_mandatory": 1,



					"option": [



						{



							"value": "Dull",



							"is_intensity": 0



						},



						{



							"value": "Bright",



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



							"value": "Light",



							"is_intensity": 0



						},


						{



							"value": "Dark",



							"is_intensity": 0



						},

						{



							"value": "Artificial",



							"is_intensity": 0



						},


						{



							"value": "Natural",



							"is_intensity": 0



						}



					]



				},



				{



					"title": "What do you feel about the quantity of the filling?",


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



							"value": "Excess",



							"is_intensity": 0



						}



					]



				}








			]



		},




		{







			"title": "Overall Preference",







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



	],











	"AROMA": [



		{



			"title": "What all aromas have you sensed?",



			"subtitle": "Directly use the search box to select the aromas that you observed or follow the category based aroma list. In case you can\'t find the observed aromas, select \"Any other\" and if unable to sense any aroma at all, then select \"Absent\". If you select \"Any other\", then please mention it in the Comment Box.",



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



			"title": "If you experience any Off (bad)- aroma, then please identify it from the list.",



			"select_type": 2,



			"is_intensity": 1,



			"intensity_type": 2,



			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",



			"is_nested_question": 0,



			"is_mandatory": 0,



			"is_nested_option": 1,



			"nested_option_title": "OFF-AROMA",



			"nested_option_list": "OFFAROMA"



		},



		{







			"title": "Overall Preference",







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



	],







	"TASTE": [



		{



			"title": "Which Basic Taste/s have you sensed?",



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







			"title": "Which Ayurvedic Taste/s have you sensed?",



			"select_type": 2,



			"is_intensity": 0,



			"is_mandatory": 1,







			"is_nested_question": 0,







			"is_nested_option": 0,







			"option": [







				{



					"value": "Astringent (Dryness)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"



				},



				{



					"value": "Pungent (Spices / Garlic)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"







				},



				{



					"value": "Pungent Cool Sensation (Mint)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"







				},



				{



					"value": "Pungent Chilli",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense"



				},



				{



					"value": "No Ayurvedic Taste",



					"is_intensity": 0







				}







			]



		},



		{







			"title": "Overall Preference",







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



	],











	"AROMATICS TO FLAVORS": [



		{



			"title": "What all aromatics have you sensed?",



			"subtitle": "Directly use the search box to select the aromatics that you have observed or follow the category based aromatics list. In case you can\'t find the observed aromatics, select \"Any other\" and if unable to sense any aromatics at all, then select \"Absent\". If you select \"Any other\", then please mention it in the Comment Box.",



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



			"title": "If you experienced any off (bad)- aromatics, then please identify it from the list.",



			"select_type": 2,



			"is_intensity": 1,



			"intensity_type": 2,



			"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense",



			"is_nested_question": 0,



			"is_mandatory": 0,



			"is_nested_option": 1,



			"nested_option_title": "OFF-AROMATICS",



			"nested_option_list": "OFFAROMA"



		},



		{



			"title": "Aftertaste",



			"subtitle": "Please chew and swallow the product.",



			"is_nested_question": 1,



			"question": [



				{



					"title": "How is the aftertaste?",



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



							"value": "None",



							"is_intensity": 0







						},



						{



							"value": "Short",



							"is_intensity": 0



						},



						{



							"value": "Sufficient",



							"is_intensity": 0



						},



						{



							"value": "Long",



							"is_intensity": 0



						}



					]



				}



			]



		},



		{



			"title": "Flavor",



			"subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together. Usually, taste has a lesser contribution and aromatics on the other hand has a greater contribution towards the development of the flavor.",



			"is_nested_question": 1,



			"question": [



				{



					"title": "How is the flavor experience?",

					"subtitle": "If you didn\'t experience any flavor, want any change in the intensity of the flavor or any other flavor factor, then please mention it in the Comment Box.",


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



				}



			]



		},



		{







			"title": "Overall Preference",



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



	],







	"TEXTURE": [




		{



			"title": "How much Force is needed to bite through the product?",



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



					"value": "Moderate",



					"is_intensity": 0



				},



				{



					"value": "Intense",



					"is_intensity": 0



				},

				{



					"value": "Very Intense",



					"is_intensity": 0



				}



			]



		},

		{



			"title": "As you chew, which of these is prominently being released from the product?",



			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [







				{



					"value": "Moisture",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"







				},



				{



					"value": "Grease (Butter etc.)",





					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				},



				{



					"value": "Dry (Saliva absorbed)",



					"is_intensity": 1,



					"intensity_type": 2,



					"intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense"



				}



			]



		},

		{



			"title": "After chewing 3- 4 times, how is the consistency of the product inside the mouth?",



			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [







				{



					"value": "Tight",



					"is_intensity": 0



				},



				{



					"value": "Dense",





					"is_intensity": 0



				},

				{



					"value": "Coarse",





					"is_intensity": 0



				},



				{



					"value": "Lumpy",



					"is_intensity": 0



				},

				{



					"value": "Airy",





					"is_intensity": 0



				},



				{



					"value": "Mushy",



					"is_intensity": 0



				},

				{



					"value": "Pasty",



					"is_intensity": 0



				}



			]



		},

		{



			"title": "Is the product sticking on the teeth or the palate?",



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



			"title": "If needed, chew the product further. Before swallowing the product, what kind of the pulp (mass) is being formed?",



			"select_type": 1,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 1,



			"option": [







				{



					"value": "Scattered particles (No pulp)",



					"is_intensity": 0



				},



				{



					"value": "Loose pulp",





					"is_intensity": 0



				},

				{



					"value": "Tight pulp",





					"is_intensity": 0



				}

			]



		},

		{



			"title": "Is anything left inside the mouth after swallowing the product?",

			"subtitle": "If you select \"Any other\", then please mention it in Comment Box.",



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



					"value": "Sticking on tooth",





					"is_intensity": 0



				},

				{



					"value": "Chalky",



					"is_intensity": 0



				},



				{



					"value": "No residue",





					"is_intensity": 0



				},

				{



					"value": "Any other",





					"is_intensity": 0



				}

			]



		},


		{




			"title": "Overall Preference",



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



	],











	"PRODUCT EXPERIENCE": [



		{



			"title": "Did this product succeed in satisfying your basic senses?",



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



			"title": "Which attributes can be further improved?",



			"select_type": 2,



			"is_intensity": 0,



			"is_nested_question": 0,



			"is_mandatory": 0,



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



					"value": "Aromatics to Flavors",



					"is_intensity": 0



				},



				{



					"value": "Texture",



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

        $data = ['name'=>'Copy of Muffins 4th March- V2','keywords'=>"Cooked Starters and Sides",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
