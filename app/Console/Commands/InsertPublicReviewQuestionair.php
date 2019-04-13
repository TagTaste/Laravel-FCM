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



            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],






            ['header_name'=>"TASTE","header_info"=> ["text" => "Take a bite and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."],'header_selection_type'=>"1"],




            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat noramlly with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],




            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is eaten; and it may even last after the product is swallowed. Product may make sound (add on chips/nuts), may give us joy (creamy foods), and may even cause pain or disgust (sticky/slimy foods)."],'header_selection_type'=>"1"],


            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavor, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]




        ];

        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What was the serving temperature of the product?", "subtitle": "You may also touch the product to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product along with the toppings?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Glazed", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 } ] }, { "title": "What is your view about the toppings on the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Less still appealing", "is_intensity": 0 }, { "value": "Less & unappealing", "is_intensity": 0 }, { "value": "Balanced", "is_intensity": 0 }, { "value": "Excess still appealing", "is_intensity": 0 }, { "value": "Excess & unappealing", "is_intensity": 0 }, { "value": "Doesn\'t matter", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "Take a spoonful of product (if needed, cut through it). What do you feel about the product?", "subtitle": "Assess all the components of the product. Please select a maximum of 4 prominent options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Airy", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Soft", "is_intensity": 0 }, { "value": "Crumbly", "is_intensity": 0 }, { "value": "Crispy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Molten filling", "is_intensity": 0 }, { "value": "Creamy filling", "is_intensity": 0 }, { "value": "Gel like filling", "is_intensity": 0 } ] }, { "title": "How many layers can you identify in this product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Two", "is_intensity": 0 }, { "value": "More than two", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "What do you observe about the build of the layers in this product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Holding together", "is_intensity": 0 }, { "value": "Falling apart", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "In terms of quantity, what is your assessment about filling inside the product?", "select_type": 1, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "option": [ { "value": "Barely any", "is_intensity": 0 }, { "value": "Less", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Little extra", "is_intensity": 0 }, { "value": "Excess", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness - Raw Banana)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How was the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take sufficient quantity of the product (include all the components of the served product). Bite 2-3 times and pause. What kind of sound do you hear?", "subtitle": "Crispy- one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events,e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product,e.g., Puffed rice.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "As you chew, which of these are being released from the product?", "subtitle": "Please chew the product 3- 4 times and pause.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Moisture", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Butter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Oil", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Cream", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dry", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "While chewing, how does the product feel inside the mouth?", "subtitle": "Please select a maximum of 4 prominent options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Airy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Soft", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Crispy", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Pasty", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Rough", "is_intensity": 0 }, { "value": "Lumpy", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "How fast does the filling of the product melt-in-the-mouth?", "subtitle": "Compress half a teaspoon of the filling between the tongue and palate. Please don\'t swallow the product yet.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Melts quickly", "is_intensity": 0 }, { "value": "Melts moderately", "is_intensity": 0 }, { "value": "Melts slowly", "is_intensity": 0 }, { "value": "Doesn\'t melt", "is_intensity": 0 }, { "value": "Less or no filling", "is_intensity": 0 } ] }, { "title": "What kind of mass is being formed?", "subtitle": "Take a spoonful of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Tight mass", "is_intensity": 0 }, { "value": "Pulpy mass", "is_intensity": 0 }, { "value": "Barely any mass", "is_intensity": 0 }, { "value": "No mass", "is_intensity": 0 } ] }, { "title": "Is this product difficult to swallow?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left inside the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Greasy film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides (like ice cream, chocolate sauce etc.) served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says it serves 2, does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall Product Preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_cake_v1','keywords'=>"generic_cake_v1",'description'=>null,
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








        $headerInfo2 = [

            ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is eaten; and it may even last after the product is swallowed. Product may make sound (add on chips/nuts), may give us joy (creamy foods), and may even cause pain or disgust (sticky/slimy foods)."],'header_selection_type'=>"1"],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]



        ];


        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\n Please note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What is the serving temperature of the product?", "subtitle": "You may also touch the product to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product along with the toppings?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Glazed", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 } ] }, { "title": "What is your view about the toppings on the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Less still appealing", "is_intensity": 0 }, { "value": "Less & unappealing", "is_intensity": 0 }, { "value": "Balanced", "is_intensity": 0 }, { "value": "Excess still appealing", "is_intensity": 0 }, { "value": "Excess & unappealing", "is_intensity": 0 }, { "value": "Doesn\'t matter", "is_intensity": 0 }, { "value": "No toppings", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "Take a spoonful of product (if needed, cut through it). What do you feel about the product?", "subtitle": "Assess all the components of the product. Please select a maximum of 4 prominent options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Soft", "is_intensity": 0 }, { "value": "Airy", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Creamy", "is_intensity": 0 }, { "value": "Crusty", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Crispy", "is_intensity": 0 }, { "value": "Elastic", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Crumbly", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "How many layers can you identify in this product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Two", "is_intensity": 0 }, { "value": "More than two", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "What do you observe about the build of the layers in this product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Holding together", "is_intensity": 0 }, { "value": "Falling apart", "is_intensity": 0 } ] }, { "title": "How does the filling inside the product appear to you?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "option": [ { "value": "Molten", "is_intensity": 0 }, { "value": "Creamy", "is_intensity": 0 }, { "value": "Gel like", "is_intensity": 0 }, { "value": "Chunky", "is_intensity": 0 }, { "value": "Crumbly", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "In terms of quantity, what is your assessment about filling inside the product?", "select_type": 1, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "option": [ { "value": "Less", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Extra", "is_intensity": 0 }, { "value": "Absent", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness - Raw Banana)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How was the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take sufficient quantity of the product (include all the components of the served product). Bite 2-3 times and pause. What kind of sound do you hear?", "subtitle": "Crispy - one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events, e.g., Rusks.\nCrackly - bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., Puffed rice. ", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "As you chew, which of these are being released from the product?", "subtitle": "Please chew the product 3- 4 times and pause.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Moisture", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Butter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Oil", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Cream", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sugar syrup", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dry", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "While chewing, how does the product feel inside the mouth?", "subtitle": "Please select a maximum of 3 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Soft", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Airy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Rough", "is_intensity": 0 }, { "value": "Lumpy", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Pasty", "is_intensity": 0 } ] }, { "title": "How fast does the product melt-in-the-mouth?", "subtitle": "Eat normally and pause. Please don\'t swallow the product yet.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Melts quickly", "is_intensity": 0 }, { "value": "Melts moderately", "is_intensity": 0 }, { "value": "Melts slowly", "is_intensity": 0 }, { "value": "Doesn\'t melt", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "What kind of mass is being formed?", "subtitle": "Take a spoonful of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Tight mass", "is_intensity": 0 }, { "value": "Pulpy mass", "is_intensity": 0 }, { "value": "Barely any mass", "is_intensity": 0 }, { "value": "No mass", "is_intensity": 0 } ] }, { "title": "Is this product difficult to swallow?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left inside the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Greasy film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides ( like ice cream, cream etc.) served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says \"it serves 2\", does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall Product Preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_dessert_v1','keywords'=>"generic_dessert_v1",'description'=>null,
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











        $headerInfo2 = [


            ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is eaten; and it may even last after the product is swallowed. Product may make sound (add on chips/nuts), may give us joy (creamy foods), and may even cause pain or disgust (sticky / slimy foods)."],'header_selection_type'=>"1"],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]



        ];


        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What was the serving temperature of the product?", "subtitle": "You may also lick the product to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "How is the product served?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Edible cone", "is_intensity": 0 }, { "value": "Stick", "is_intensity": 0 }, { "value": "Container", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product along with the toppings?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Glazed", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 } ] }, { "title": "What is your view about the toppings on the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Less still appealing", "is_intensity": 0 }, { "value": "Less & unappealing", "is_intensity": 0 }, { "value": "Balanced", "is_intensity": 0 }, { "value": "Excess still appealing", "is_intensity": 0 }, { "value": "Excess & unappealing", "is_intensity": 0 }, { "value": "No toppings", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "Take a spoonful of product (if needed, cut through it). What do you feel about the product?", "subtitle": "Assess all the components of the product. Please select a maximum of 4 prominent options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Creamy", "is_intensity": 0 }, { "value": "Dripping", "is_intensity": 0 }, { "value": "Top Icicle Layer", "is_intensity": 0 }, { "value": "Icicles", "is_intensity": 0 }, { "value": "Silky", "is_intensity": 0 }, { "value": "Airy", "is_intensity": 0 }, { "value": "Soft", "is_intensity": 0 }, { "value": "Crumbly", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Crispy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness - Raw Banana)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How is the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take sufficient quantity of the product (include all the components of the served product). Bite 2-3 times and pause. What kind of sound do you hear?", "subtitle": "Crispy - one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events, e.g., Rusks.\nCrackly - bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., Puffed rice. ", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "How fast did the product melt-in-the-mouth?", "subtitle": "Compress half a teaspoon of the product between the tongue and the palate. Please don\'t swallow the product yet.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Melts quickly", "is_intensity": 0 }, { "value": "Melts moderately", "is_intensity": 0 }, { "value": "Melts slowly", "is_intensity": 0 }, { "value": "Doesn\'t melt", "is_intensity": 0 } ] }, { "title": "What is the texture of the product on your tongue?", "subtitle": "Please take a few licks. Select a maximum of 3 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Soft", "is_intensity": 0 }, { "value": "Creamy", "is_intensity": 0 }, { "value": "Airy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Crunchy", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Rough", "is_intensity": 0 }, { "value": "Lumpy", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Silky", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "As you eat, which of these is being felt inside the mouth? ", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Milk solids", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dairy fat", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Other fat", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Icicles (Ice crystals)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Seed/ Nuts awareness", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Syrupy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "To what extent is your mouth coated with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Barely Any", "is_intensity": 0 }, { "value": "Less", "is_intensity": 0 }, { "value": "Moderate", "is_intensity": 0 }, { "value": "Little extra", "is_intensity": 0 }, { "value": "Excess", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left inside the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Greasy film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides (like chocolate sauce, fruits etc.) served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says it serves 2, does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall Product Preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_ice_cream_v1','keywords'=>"generic_ice_cream_v1",'description'=>null,
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










        $headerInfo2 = [


            ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more."],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. FEEL starts when the product is put inside the mouth; FEEL changes when the product is chewed; and it may even last after the product is swallowed. Product may make sound (chips), may give us joy (creamy foods), and may even cause pain or disgust (sticky/slimy foods)."],'header_selection_type'=>"1"],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics To Flavors, and Texture; rate the overall experience of the product on all parameters taken together."],'header_selection_type'=>"2"]



        ];


        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What is the serving temperature of the product?", "subtitle": "You may also touch the product to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 } ] }, { "title": "Is the product garnished?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "How do you relate to the consistency of the gravy in the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Water", "is_intensity": 0 }, { "value": "Whole Milk", "is_intensity": 0 }, { "value": "Pulpy Juice", "is_intensity": 0 }, { "value": "Cream", "is_intensity": 0 }, { "value": "Honey", "is_intensity": 0 }, { "value": "Peanut Butter", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How is the visual texture of the product?", "subtitle": "Please select a maximum of 4 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Spicy", "is_intensity": 0 }, { "value": "Soft", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Lumpy", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Crispy", "is_intensity": 0 }, { "value": "Crusty", "is_intensity": 0 }, { "value": "Chunky", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Dry", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "How oily is the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Oil Free", "is_intensity": 0 }, { "value": "Barely Detectable", "is_intensity": 0 }, { "value": "Weak", "is_intensity": 0 }, { "value": "Mild", "is_intensity": 0 }, { "value": "Moderate", "is_intensity": 0 }, { "value": "Intense", "is_intensity": 0 }, { "value": "Very Intense", "is_intensity": 0 }, { "value": "Extremely Intense", "is_intensity": 0 } ] }, { "title": "Is the quantity of main ingredient in relation to whole product sufficient?", "subtitle": "Assess how much chicken / paneer is there with respect to gravy. Similarly, in dry dishes like Aloo-Gobhi, assess the ratio of Aloo & Gobhi.", "select_type": 1, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 }, { "value": "Doesn\'t matter", "is_intensity": 0 } ] }, { "title": "What is the cooked appeal of the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Rightly cooked", "is_intensity": 0 }, { "value": "Under cooked", "is_intensity": 0 }, { "value": "Over cooked", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness - Raw Banana)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How is the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take sufficient quantity of the product (include all the components of the served product). Bite 2-3 times and pause. What kind of sound do you hear?", "subtitle": "Crispy- one sound event which is sharp, clean, fast and high pitched, e.g., Chips.\nCrunchy (Crushing sound) - multiple low pitched sounds perceived as a series of small events, e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., Puffed rice.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "How much force is needed to chew the product?", "subtitle": "Take all the components of the product.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Barely any force", "is_intensity": 0 }, { "value": "Normal force", "is_intensity": 0 }, { "value": "Extra force", "is_intensity": 0 } ] }, { "title": "As you chew, which of these are being released from the product?", "subtitle": "Please chew for 3-4 times and pause.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Moisture", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Oil", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dry", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "While chewing, which prominent textures can you feel in your mouth?", "subtitle": "Select a maximum of 4 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Juicy", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Silky", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Dry", "is_intensity": 0 }, { "value": "Fibrous", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Springy", "is_intensity": 0 }, { "value": "Gummy", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Grainy", "is_intensity": 0 }, { "value": "Gritty (Hard to chew)", "is_intensity": 0 }, { "value": "Chunky", "is_intensity": 0 }, { "value": "Leathery", "is_intensity": 0 } ] }, { "title": "What kind of mass is being formed?", "subtitle": "Take a spoonful of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Tight mass", "is_intensity": 0 }, { "value": "Pulpy mass", "is_intensity": 0 }, { "value": "Barely any mass", "is_intensity": 0 }, { "value": "No mass", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left inside the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Oily film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says it serves 2, does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall product preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_main_course_v1','keywords'=>"generic_main_course_v1",'description'=>null,
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








        $headerInfo2 = [


            ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Take a bite and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more." ],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavors and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]



        ];


        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What is the serving temperature of the product?", "subtitle": "You may also touch to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Light", "is_intensity": 0 }, { "value": "Dark", "is_intensity": 0 }, { "value": "Natural", "is_intensity": 0 }, { "value": "Artificial", "is_intensity": 0 } ] }, { "title": "What type of bread is used in this product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Sliced Sandwich Bread", "is_intensity": 0 }, { "value": "Artisanal Bread", "is_intensity": 0 } ] }, { "title": "How is the surface appearance of the sandwich?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Fried", "is_intensity": 0 }, { "value": "Toasted", "is_intensity": 0 }, { "value": "Plain", "is_intensity": 0 }, { "value": "Prominent grill marks", "is_intensity": 0 }, { "value": "Light grill marks", "is_intensity": 0 } ] }, { "title": "How many layers can you identify in this product (include all the components)?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Two", "is_intensity": 0 }, { "value": "More than two", "is_intensity": 0 }, { "value": "Not visible", "is_intensity": 0 } ] }, { "title": "What do you observe about the build of this product?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Holding together", "is_intensity": 0 }, { "value": "Fillings slipping out", "is_intensity": 0 }, { "value": "Cheese dripping", "is_intensity": 0 }, { "value": "Soggy bread", "is_intensity": 0 }, { "value": "Crumbly bread", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 } ] }, { "title": "In terms of quantity, what is the proportion of the filling to the whole product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Two - third", "is_intensity": 0 }, { "value": "Half", "is_intensity": 0 }, { "value": "One - third", "is_intensity": 0 }, { "value": "One - fourth", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How is the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Which components of the product are contributing towards making the flavor experience better?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bread", "is_intensity": 0 }, { "value": "Condiments (spreads)", "is_intensity": 0 }, { "value": "Filling", "is_intensity": 0 }, { "value": "Cheese", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take a single bite (with all the components of the product) and pause. Which prominent sound do you hear?", "subtitle": "Crispy - One sharp, clean, fast, and high pitched sound, e.g., Chips.\nCrunchy - Multiple low pitched crushing sounds perceived as a series of small events, e.g., Rusks.\nCrackly - One sudden low pitched sound that brittles the product, e.g., Puffed rice.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "How much force is needed to bite through the entire product (top to bottom)?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Less force", "is_intensity": 0 }, { "value": "Normal force", "is_intensity": 0 }, { "value": "Extra force", "is_intensity": 0 } ] }, { "title": "While chewing, which prominent textures can you feel in your mouth?", "subtitle": "Please chew for 3 - 4 times and pause. Select a maximum of 4 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Crusty bread", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Gritty", "is_intensity": 0 }, { "value": "Fibrous", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Juicy", "is_intensity": 0 }, { "value": "Tender", "is_intensity": 0 }, { "value": "Sticky", "is_intensity": 0 }, { "value": "Springy", "is_intensity": 0 }, { "value": "Gummy", "is_intensity": 0 }, { "value": "Dense", "is_intensity": 0 }, { "value": "Dry", "is_intensity": 0 }, { "value": "Grainy", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Pasty", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Leathery", "is_intensity": 0 } ] }, { "title": "How does the filling feel inside your mouth?", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Oily", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Juicy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Tender", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Loosely packed", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dense", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dry", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "What kind of mass is being formed?", "subtitle": "Take a bite of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Tight mass", "is_intensity": 0 }, { "value": "Pulpy mass", "is_intensity": 0 }, { "value": "Barely any mass", "is_intensity": 0 }, { "value": "No mass", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left inside the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Oily film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "No Sides Served", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says it serves 2, does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall Product Preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_sandwich_v1','keywords'=>"generic_sandwich_v1",'description'=>null,
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







        $headerInfo2 = [


            ['header_name'=>"INSTRUCTIONS",'header_selection_type'=>"0"],

            ['header_name' => "Your Food Shot", 'header_selection_type' => "3"],


            ['header_name'=>"APPEARANCE","header_info"=> ["text" => "Examine the product visually and answer the questions outlined below."],'header_selection_type'=>"1"],


            ['header_name'=>"AROMA","header_info"=> ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't eat yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aroma/s arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc) which the product might have undergone."],'header_selection_type'=>"1"],




            ['header_name'=>"TASTE","header_info"=> ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste; and some people crave for more." ],'header_selection_type'=>"1"],



            ['header_name'=>"AROMATICS TO FLAVORS","header_info"=> ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."],'header_selection_type'=>"1"],



            ['header_name'=>"TEXTURE","header_info"=> ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."],'header_selection_type'=>"1"],

            ['header_name'=>"PRODUCT EXPERIENCE","header_info"=> ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavors and Texture; rate the overall experience of the product on all the parameters taken together."],'header_selection_type'=>"2"]


        ];

        $questions2 = '{ "INSTRUCTIONS": [ { "title": "Instruction", "subtitle": "<b>Welcome to the Product Review!</b>\n\nTo review, follow the questionnaire and select the answers that match your observations.\n\nPlease click (i) on every screen / page for guidance related to questions.\nAny attribute that stands out as either too good or too bad, may please be highlighted in the comment box at the end of the questionnaire.\n\nPlease note that you are reviewing the product and NOT the package.\n\nRemember, there are no right or wrong answers. Let\'s start by opening the package.", "select_type": 4 } ], "Your Food Shot": [ { "title": "Take a selfie with the product", "subtitle": "Reviews look more authentic when you post them with a photograph.", "select_type": 6 } ], "APPEARANCE": [ { "title": "What is the serving temperature of the product?", "subtitle": "You may also touch to assess the serving temperature.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Frozen", "is_intensity": 0 }, { "value": "Chilled", "is_intensity": 0 }, { "value": "Cold", "is_intensity": 0 }, { "value": "Room temperature", "is_intensity": 0 }, { "value": "Warm", "is_intensity": 0 }, { "value": "Hot", "is_intensity": 0 }, { "value": "Steaming hot", "is_intensity": 0 } ] }, { "title": "What is the color of the crust?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Golden", "is_intensity": 0 }, { "value": "Yellow", "is_intensity": 0 }, { "value": "Copper", "is_intensity": 0 }, { "value": "Bronze", "is_intensity": 0 }, { "value": "Light brown", "is_intensity": 0 }, { "value": "Brown", "is_intensity": 0 }, { "value": "Whitish", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 } ] }, { "title": "How is the visual impression (color and sheen) of the product?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Bright", "is_intensity": 0 }, { "value": "Dull", "is_intensity": 0 }, { "value": "Shiny", "is_intensity": 0 }, { "value": "Dehydrated", "is_intensity": 0 }, { "value": "Oily", "is_intensity": 0 }, { "value": "Limp", "is_intensity": 0 }, { "value": "Firm", "is_intensity": 0 }, { "value": "Smooth", "is_intensity": 0 }, { "value": "Rough", "is_intensity": 0 }, { "value": "Spots", "is_intensity": 0 } ] }, { "title": "How is the color of majority of the product pieces?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Even color", "is_intensity": 0 }, { "value": "Uneven color", "is_intensity": 0 } ] }, { "title": "How is the shape of the majority of the product pieces?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Uniform shape", "is_intensity": 0 }, { "value": "Non - Uniform shape", "is_intensity": 0 } ] }, { "title": "How is the size of majority of the product pieces?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Different sizes", "is_intensity": 0 }, { "value": "Same size", "is_intensity": 0 } ] }, { "title": "In terms of quantity, what is your assessment about filling inside the product?", "subtitle": "To answer this question, please cut through the product.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Less", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Extra", "is_intensity": 0 }, { "value": "Absent", "is_intensity": 0 }, { "value": "Not applicable", "is_intensity": 0 } ] }, { "title": "Overall preference of Appearance", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMA": [ { "title": "What all aromas have you sensed?", "subtitle": "Directly use the search box to select the aromas that you have identified or follow the category based aroma list. In case you can\'t find the identified aromas, select <b>Any other</b> and if unable to sense any aroma at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMAS", "nested_option_list": "AROMA" }, { "title": "Overall preference of Aroma", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TASTE": [ { "title": "Which Basic tastes have you sensed?", "is_nested_question": 0, "is_intensity": 0, "is_nested_option": 0, "is_mandatory": 1, "select_type": 2, "option": [ { "value": "Sweet", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Salt", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Sour", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Acidic,Weakly Acidic,Mildly Acidic, Moderately Acidic, Intensely Acidic, Very Intensely Acidic, Extremely Acidic" }, { "value": "Bitter", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Umami", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Basic Taste", "is_intensity": 0 } ] }, { "title": "Which Ayurvedic tastes have you sensed?", "select_type": 2, "is_intensity": 0, "is_mandatory": 1, "is_nested_question": 0, "is_nested_option": 0, "option": [ { "value": "Astringent (Dryness - Raw Banana)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent (Spices / Garlic)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Cool Sensation (Mint)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Pungent Chilli", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Ayurvedic Taste", "is_intensity": 0 } ] }, { "title": "Overall preference of Taste", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "AROMATICS TO FLAVORS": [ { "title": "What all aromatics have you sensed?", "subtitle": "Directly use the search box to select the aromatics that you have identified or follow the category based aromatics list. In case you can\'t find the identified aromatics, select <b>Any other</b> and if unable to sense any aromatics at all, then select <b>Absent</b>.", "select_type": 2, "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense", "is_nested_question": 0, "is_mandatory": 1, "is_nested_option": 1, "nested_option_title": "AROMATICS", "nested_option_list": "AROMA" }, { "title": "Please swallow the product and pause. How is the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Pleasant", "is_intensity": 0 }, { "value": "Unpleasant", "is_intensity": 0 }, { "value": "Can\'t Say", "is_intensity": 0 } ] }, { "title": "What is the length of the aftertaste?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Long", "is_intensity": 0 }, { "value": "Sufficient", "is_intensity": 0 }, { "value": "Short", "is_intensity": 0 }, { "value": "None", "is_intensity": 0 } ] }, { "title": "How is the flavor experience?", "subtitle": "Flavor is experienced only inside the mouth when the taste and aromatics (odor through the mouth) work together.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Natural & pleasant", "is_intensity": 0 }, { "value": "Natural but unpleasant", "is_intensity": 0 }, { "value": "Artificial but pleasant", "is_intensity": 0 }, { "value": "Artificial & unpleasant", "is_intensity": 0 }, { "value": "Bland", "is_intensity": 0 } ] }, { "title": "Which components are contributing more towards enhancing the flavor experience?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Outer layer / crust", "is_intensity": 0 }, { "value": "Inner cooked part / Filling", "is_intensity": 0 }, { "value": "Condiments", "is_intensity": 0 }, { "value": "Seasoning / Garnishing", "is_intensity": 0 }, { "value": "All of them", "is_intensity": 0 }, { "value": "None of them", "is_intensity": 0 } ] }, { "title": "Overall preference of Aromatics", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "TEXTURE": [{ "title": "Take sufficient quantity of the product (include all the components of the served product). Bite 2-3 times and pause. What kind of sound do you hear?", "subtitle": "Crispy - One sharp, clean, fast, and high pitched sound, e.g., Chips.\nCrunchy - Multiple low pitched crushing sounds perceived as a series of small events, e.g., Rusks.\nCrackly - One sudden low pitched sound that brittles the product, e.g., Puffed rice.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Crispy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crunchy", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Crackly", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "No Sound", "is_intensity": 0 } ] }, { "title": "As you chew, which of these are being released from the product?", "subtitle": "Please chew the product 3-4 times and pause.", "select_type": 2, "is_nested_question": 0, "is_nested_option": 0, "is_mandatory": 1, "is_intensity": 0, "option": [ { "value": "Oily", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Juicy (moisture release)", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" }, { "value": "Dry", "is_intensity": 1, "intensity_type": 2, "intensity_value": "Barely Detectable,Weak,Mild,Moderate,Intense,Very Intense,Extremely Intense" } ] }, { "title": "While chewing, which textures can you feel inside your mouth?", "subtitle":"Please select a maximum of 4 options.", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Soft", "is_intensity": 0 }, { "value": "Pasty", "is_intensity": 0 }, { "value": "Mushy", "is_intensity": 0 }, { "value": "Fluffy", "is_intensity": 0 }, { "value": "Chewy", "is_intensity": 0 }, { "value": "Springy", "is_intensity": 0 }, { "value": "Fibrous", "is_intensity": 0 }, { "value": "Stringy", "is_intensity": 0 }, { "value": "Spongy", "is_intensity": 0 }, { "value": "Rubbery", "is_intensity": 0 }, { "value": "Coarse", "is_intensity": 0 }, { "value": "Hard", "is_intensity": 0 } ] }, { "title": "What kind of mass is being formed?", "subtitle": "Take a spoonful of the product comprising all the ingredients, chew it for minimum 8-10 times and pause.", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Tight mass", "is_intensity": 0 }, { "value": "Pulpy mass", "is_intensity": 0 }, { "value": "Barely any mass", "is_intensity": 0 }, { "value": "No mass", "is_intensity": 0 } ] }, { "title": "After swallowing the product, do you feel anything left in the mouth?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Oily film", "is_intensity": 0 }, { "value": "Loose particles", "is_intensity": 0 }, { "value": "Sticking on tooth", "is_intensity": 0 }, { "value": "Stuck between tooth", "is_intensity": 0 }, { "value": "Chalky", "is_intensity": 0 }, { "value": "Any other", "is_intensity": 0 }, { "value": "No residue", "is_intensity": 0 } ] }, { "title": "Overall preference of Texture", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] } ], "PRODUCT EXPERIENCE": [{ "title": "What do you feel about the sides (like sauce, chutney, salad etc.) served along with the product?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Exceeds Expectation", "is_intensity": 0 }, { "value": "Meets Expectation", "is_intensity": 0 }, { "value": "Below Expectation", "is_intensity": 0 }, { "value": "Not Applicable", "is_intensity": 0 } ] }, { "title": "How would you describe the \"serve size\" of this product?", "subtitle": "Suppose the menu says it serves 2, does it really serve 2?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Generous", "is_intensity": 0 }, { "value": "Modest", "is_intensity": 0 }, { "value": "Limited", "is_intensity": 0 } ] }, { "title": "Did this product succeed in satisfying your basic senses?", "select_type": 1, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Yes", "is_intensity": 0 }, { "value": "No", "is_intensity": 0 } ] }, { "title": "Which attributes can be improved further?", "select_type": 2, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 0, "option": [ { "value": "Appearance", "is_intensity": 0 }, { "value": "Aroma", "is_intensity": 0 }, { "value": "Taste", "is_intensity": 0 }, { "value": "Aromatics to Flavors", "is_intensity": 0 }, { "value": "Texture", "is_intensity": 0 }, { "value": "Everything is fine", "is_intensity": 0 } ] }, { "title": "Overall Product Preference", "select_type": 5, "is_intensity": 0, "is_nested_question": 0, "is_mandatory": 1, "option": [ { "value": "Dislike Extremely", "color_code": "#8C0008" }, { "value": "Dislike Moderately", "color_code": "#C92E41" }, { "value": "Dislike Slightly", "color_code": "#C92E41" }, { "value": "Can\'t Say", "color_code": "#E27616" }, { "value": "Like Slightly", "color_code": "#AC9000" }, { "value": "Like Moderately", "color_code": "#7E9B42" }, { "value": "Like Extremely", "color_code": "#305D03" } ] }, { "title": "Comments", "placeholder": "Share feedback in your own words…", "select_type": 3, "is_intensity": 0, "is_mandatory": 0, "is_nested_question": 0 } ] }';

        $data = ['name'=>'generic_snacks_v1','keywords'=>"generic_snacks_v1",'description'=>null,
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
