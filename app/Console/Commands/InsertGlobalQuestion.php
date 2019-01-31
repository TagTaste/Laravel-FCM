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


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."]],



            ['header_name'=>"AROMA","header_info"=> ["text" => "<u>At this stage, we are only assessing the aromas (odors through the nose), so please don't drink it yet. Now bring the product closer to your nose and take a deep breath;</u> you may also try taking 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like fermentation, distillation etc.), which the product might have undergone."]],


            ['header_name'=>"TASTE","header_info"=> ["text" => "<i>Slurp noisily and assess the tastes.</i>\n\nAll the tastes except Umami are self-explanatory. <b> Umami </b> taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."]],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Slurp noisily again, keeping your <b>MOUTH CLOSED</b> and <b>EXHALE THROUGH THE NOSE</b>. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."]],




            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the   <b>Texture (Feel)</b> now. FEEL starts when the product comes in contact with the mouth and it may even last after the product has been swallowed. Texture (mouthfeel) is all about the joy we get from what we drink."]],




            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."]]



        ];

        $questions2 = '{ "INSTRUCTIONS": [ { "title": "<u>INSTRUCTION</u>", "subtitle": "<b>Welcome to the Product Review!</b>\n\n<u>If a product involves stirring, shaking etc. (like cold coffee) then the taster must follow the instructions fully, as mentioned on the packaging. To review, follow the questionnaire and select the answers that match with your observations. Please note that you are reviewing the product and NOT the package.</u>\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "APPEARANCE": [ { "title": "<u>for testing underline >> What was the serving temperature of the product?</u>", "subtitle": "You may also touch the product to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room Temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming Hot", "is_intensity": 0 } ] }, { "title": "<i>for testing italic >> How was the visual impression (color and hue) of the product?</i>", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dull", "is_intensity": 0 }, { "value": "Bright", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 } ] }, { "title": "<u>How was the visual texture of the product?</u>", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Silky", "is_intensity": 0 }, { "value": "Frothy", "is_intensity": 0 }, { "value": "Bubbly", "is_intensity": 0 }, { "value": "Sediments", "is_intensity": 0 }, { "value": "Pulpy", "is_intensity": 0 }, { "value": "Syrupy", "is_intensity": 0 }, { "value": "Water Separated", "is_intensity": 0 }, { "value": "Slushy", "is_intensity": 0 }, { "value": "Weak", "is_intensity": 0 }, { "value": "Strong", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 } ] }, { "title": "<b>Overall preference of Appearance</b>", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "Which all aromas did you observe?", "subtitle": "Directly use the search box to select the aromas that you observed or follow the category based aroma list. In case you can\'t find the observed aromas, select <b>Any other</b> and if unable to sense any aromas at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 1, "intensity_value": "15", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_list": "AROMA", "nested_option_title": "AROMAS" }, { "title": "<u>Overall preference of Aroma</u>", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes did you observe?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes did you observe?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent(Dryness)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "Which all aromatics did you observe?", "subtitle": "Directly use the search box to select the aromatics that you observed or follow the category based aromatics list. In case you can\'t find the observed aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable, Weak, Mild, Moderate, Intense, Very Intense, Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "<i>Swallow the product. How was the aftertaste?</i>", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "How was the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [ { "title": "How was the mouthfeel of the product?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Refreshing", "is_intensity": 0 }, { "value": "Watery", "is_intensity": 0 }, { "value": "Juicy", "is_intensity": 0 }, { "value": "Creamy", "is_intensity": 0 }, { "value": "Super Smooth", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Chunky", "is_intensity": 0 }, { "value": "Syrupy", "is_intensity": 0 }, { "value": "Tingly", "is_intensity": 0 }, { "value": "Popping", "is_intensity": 0 }, { "value": "Foamy", "is_intensity": 0 }, { "value": "Milky", "is_intensity": 0 } ] }, { "title": "Did you feel anything left inside the mouth after swallowing the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [ { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "If no, which attribute(s) needs improvement?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics To Flavor", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 } ] }, { "title": "Overall product preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "<b>Comments</b>", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'for testing purpose','keywords'=>"french fries",'description'=>null,
            'question_json'=>$questions2,'header_info'=>json_encode($headerInfo2,true)];
        \DB::table('global_questions')->insert($data);






    }
}
