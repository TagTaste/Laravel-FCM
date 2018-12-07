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
        $headerInfo2 = [
   ['header_name'=>"INSTRUCTIONS"],

   ['header_name'=>"APPEARANCE","header_info"=>"Visually observe the sample, e.g., color, appearance, texture, etc and assess the below outlined questions. 
Any attribute that stands out as either too good or too bad, may please be highlighted in the comments box at the end of each section."],

   ['header_name'=>"AROMA","header_info"=>"We experience Aroma when volatile compounds travel from brewed liquor through the air to our nose. There are too many known aromas in coffee; fresh coffee has a stronger aroma than the stale coffee, the degree of roasting impacts aroma and blending creates a wide range of aromas. To experience aroma, take a deep breath in and if you don't get any aroma then take short, quick and strong sniffs like how a dog sniffs.
"],

   ['header_name'=>"TASTE","header_info"=>"Take a sip or multiple sips and assess the taste/s. Anything too good or too bad, please highlight in the comment box at the end of the section. If you find the product to be bland, please mention in the comment box."],

   ['header_name'=>"AROMATICS TO FLAVORS","header_info"=>"Aromatics is experiencing odour/s inside the mouth, as you slurp. Take a sip again, keeping your mouth closed and exhale through the nose. Identify the odours using the aroma options. Anything too good or too bad, please highlight in the comment box at the end of the section."],

   ['header_name'=>"MOUTHFEEL","header_info"=>"Drink the coffee and assess the mouthfeel elements of the coffee. Mouthfeel is oral texture which affects our enjoyment.
 "],

   ['header_name'=>"OVERALL PRODUCT EXPERIENCE","header_info"=>"RATE the overall experience of the product on the preference scale."]
];



        $questions2 = '{ "INSTRUCTIONS": [{ "title": "INSTRUCTION", "subtitle": "Please follow the questionnaire and click answers that match with your observation/s. Remember, there are no right or wrong answers. In case you observe something that is not covered in the questionnaire, you are most welcome to share your additional inputs in the comments box. \n Anything that stands out as either too good or too bad, may please be highlighted in the comments box.", "select_type": 4 }], "APPEARANCE": [{ "title": "Identify the Color. If selected \"any other\" option mention it in the comment box", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Hazelnut,Caramel,Tan,Brown,Dark Tan,Dark brown,Rust,Black,Any other" }, { "title": "Is it Clear or Cloudy?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Clear,Cloudy" }, { "title": "Any off-appearance attribute (If yes, please highlight in the comment box)", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Yes,No" }, { "title": "Overall preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0 } ], "AROMA": [{ "title": "Aroma observed", "subtitle": "(We have a list of more than 500 aromas, grouped under 11 heads. If you select \"any other\" option please write the identified aroma in the comment box. Use the search box to access the aroma list.) ", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_list": "AROMA" }, { "title": "If you experienced any Off (bad)- aroma, please indicate the intensity.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming", "is_nested_question": 0, "is_mandatory": 0, "is_nested_option": 1, "nested_option_list": "OFFAROMA" }, { "title": "Overall preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0 } ], "TASTE": [{ "title": "Basic Taste", "is_nested_question": 1, "is_mandatory": 1, "question": [{ "title": "Sweet", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense" }, { "title": "Salt", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Barely Detectable,Identifiable but not very intense,Slightly Intense,Moderately Intense,Intense,Very Intense,Extremely Intense" }, { "title": "Sour", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic" }, { "title": "Bitter", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Barely Detectable,Identifiable but not very intense,Slightly Intense,Moderately Intense,Intense,Very Intense,Extremely Intense" }, { "title": "Umami", "subtitle": "When the taste causes continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth and has a long lasting aftertaste.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense" } ] }, { "title": "Ayurveda Taste", "select_type": 2, "is_mandatory": 0, "is_intensity": 1, "intensity_type": 2, "intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense", "is_nested_question": 0, "option": "Astringent (Dryness),Pungent (Spices/Garlic),Pungent Cool Sensation (Mint),Pungent- Chilli" }, { "title": "Sip again and assess the acidity of the coffee.", "subtitle": "Coffee without acids is \"flat\" and with acids can be \"bright with a pop\" or undesirably \"sour\".", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Flat,Bright,Winey,Lime (Sour),Fermented" }, { "title": "Intensity of natural sweetness", "subtitle": "Applicable only in the absence of added sugar.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense" }, { "title": "Overall preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0 } ], "AROMATICS TO FLAVORS": [{ "title": "Aromatics observed (you can refer again to aroma section)", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_list": "AROMA" }, { "title": "If you experienced any Off (bad)- aromatics, please indicate the intensity .", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "None,Very Mild,Mild,Distinct - mild,Distinct,Distinct - strong,Strong,Overwhelming", "is_nested_question": 0, "is_mandatory": 0, "is_nested_option": 1, "nested_option_list": "OFFAROMA" }, { "title": "Aftertaste", "subtitle": "Ingest the beverage and assess the sensation on your tongue.", "is_nested_question": 1, "is_mandatory": 1, "question": [{ "title": "How was the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Pleasant,Unpleasant,Can\'t say" }, { "title": "Length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Short,Sufficient,Long" } ] }, { "title": "Flavor", "subtitle": "As a rule of thumb, Flavor is a combination of Taste (25%) and Aromatics (75%) . Congratulations! You just discovered the Flavor of the product that you are tasting.", "is_nested_question": 1, "is_mandatory": 1, "question": [{ "title": "What is the Flavor like?", "subtitle": " If selected \"Any other\" option mention it in the comment box.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Chocolatey,Bitter,Bright,Mellow,Sharp,Dry,Any other" }, { "title": "Did your observe any taint/s (Off Flavor)", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "None,Fermented,Leathery (Rubber),Grassy,Aged,Earthy,Green,Musty,New crop,Stale,Woody,Strawy" } ] }, { "title": "Overall preference", "subtitle": "Share your overall preference for the Flavor (Concentrate on Aromatics and Taste together)", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0 } ], "MOUTHFEEL": [{ "title": "How would you describe body and smoothness of the coffee? If selected \"any other\" mention in the comment box", "subtitle": "Body - refers to the heaviness of texture of the brewed coffee. \n Smoothness - The result of levels of fat suspended in the coffee.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Water,Skimmed milk,Syrup,Whole milk,Creamy,Any other" }, { "title": "Overall preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0 } ], "OVERALL PRODUCT EXPERIENCE": [{ "title": "Are these 4 elements balanced: Acidity, Aftertaste, Flavor and Body? ", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": "Yes,No" }, { "title": "If no, then which of the attribute/s is weak in the coffee?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": "Acidity,Aftertaste,Flavor,Body" }, { "title": "If no, then which of the attribute/s is strong in the coffee?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": "Acidity,Aftertaste,Flavor,Body" }, { "title": "Overall Product Experience", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [{ "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Strongly", "color_code": "#D0021B" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Strongly", "color_code": "#577B33" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }'
        ;
        
        $data = ['name'=>'Cold Brewed Coffee','keywords'=>"Cold Brewed Coffee",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
