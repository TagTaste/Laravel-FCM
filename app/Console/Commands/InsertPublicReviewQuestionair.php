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
		['header_name' => "INSTRUCTIONS"],
		['header_name' => "APPEARANCE", "header_info" => ["text" => "Examine the product visually and answer the questions outlined below."]],
		['header_name' => "AROMA","header_info" => ["text" => "At this stage, we are assessing only aromas (odors) through the nose, so please don't take a bite yet. Now bring the product closer to your nose and take a deep breath; you may also take 3-4 short, quick and strong sniffs. Aromas arising from the product can be traced to the ingredients and the processes (like baking, cooking, fermentation etc.) which the product might have undergone."]],
		['header_name' => "TASTE","header_info" => ["text" => "Eat normally and assess the tastes.\n\nAll the tastes except Umami are self-explanatory. Umami taste is felt when you get a continuous secretion of saliva; taste is felt on the entire tongue, throat, roof, back of the mouth; has a long lasting aftertaste and some people crave for more."]],
		['header_name' => "AROMATICS","header_info" => ["text" => "Eat normally with your MOUTH CLOSED and EXHALE THROUGH THE NOSE. Identify the odors that come from inside the mouth; these observed odors are called Aromatics."]],
		['header_name' => "TEXTURE","header_info" => ["text" => "Let's experience the Texture (Feel) now. ‘Feel’ starts when the product comes in contact with the mouth and the ‘Feel’ may even last after the product has been swallowed. Texture (Feel) is all about the joy we get from what we eat."]],
		['header_name' => "PRODUCT EXPERIENCE","header_info" => ["text" => "Consider all the attributes - Appearance, Aroma, Taste, Aromatics to flavors and Texture; rate the overall experience of the product on all the parameters taken together."]]
	];

        $questions2 = '{
"INSTRUCTIONS": [
 {
"title": "INSTRUCTION",
"subtitle": "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.",
"select_type": 4
}
],
"APPEARANCE": [{
"title": "Color and evenness of crust",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Light brown,Pale brown,Golden brown,Dark brown"
},
{
"title": "Color of the mass",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Lemon,Golden tone,Light brown,Baked brown"
},
{
"title": "Shape",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Even,Uneven"
},
{
"title": "Overall preference",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 0
}
],
"AROMA": [{
"title": "Aroma observed",
"select_type": 2,
"is_intensity": 1,
"intensity_type": 2,
"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
"is_nested_question": 0,
"is_mandatory": 1,
"is_nested_option": 1,
"nested_option_list": "AROMA"
},
{
"title": "Ayurveda Taste - numeric intensity",
"select_type": 2,
"is_mandatory": 1,
"is_intensity": 1,
"intensity_type": 1,
"intensity_value": 15,
"is_nested_question": 0,
"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
},
{
"title": "Overall preference",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 0
}
],
"TASTE": [{
"title": "Basic Taste",
"is_nested_question": 1,
"is_mandatory": 1,
"question": [{
"title": "Sweet",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
},
{
"title": "Taste(textual intensity)",
"select_type": 2,
"is_mandatory": 1,
"is_intensity": 1,
"intensity_type": 2,
"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
"is_nested_question": 0,
"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
},
{
"title": "Taste - numeric intensity",
"select_type": 2,
"is_mandatory": 1,
"is_intensity": 1,
"intensity_type": 1,
"intensity_value": 15,
"is_nested_question": 0,
"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
},
{
"title": "Salt",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
},
{
"title": "Sour",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Neutral,Barely Acidic,Mildly Acidic,Moderately Acidic,Strongly Acidic,Intensely Acidic,Very Intensely Acidic,Extremely Acidic"
},
{
"title": "Bitter",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
},
{
"title": "Umami",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense"
}
]
},
{
"title": "Ayurveda Taste",
"select_type": 2,
"is_mandatory": 1,
"is_intensity": 1,
"intensity_type": 2,
"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
"is_nested_question": 0,
"option": "Astringent (Dryness),Pungent- Masala (Warm Spices),Pungent- Cool Sensation (Cool Spices),Pungent- Chilli"
},
{
"title": "If you were to make your own chocolate what will be the combination of bitter and sweet taste",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "30% Bitter and 70% Sweet,70% Bitter and 30% Sweet,100% Bitter,15% Bitter,10% Bitter and 90% Sweet (Milk Chocolate)"
},
{
"title": "Overall preference",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 0
}
],
"AROMATICS": [{
"title": "Feel of baked flour",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Yes,No"
},
{
"title": "Aromatics observed",
"select_type": 2,
"is_intensity": 1,
"intensity_type": 2,
"intensity_value": "None,Very Mild,Mild,Distinct Mild,Distinct,Distinct Strong,Strong,Overwhelming",
"is_nested_question": 0,
"is_mandatory": 1,
"is_nested_option": 1,
"nested_option_list": "AROMA"
},
{
"title": "Overall preference",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 0
}
],
"TEXTURE": [{
"title": "Rougness of mass ",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
},
{
"title": "Sound",
"is_nested_question": 0,
"subtitle": "Crispy- one sound event- sharp, clean, fast and high pitched,e.g., Potato chips.\nCrunchy - multiple low pitched sounds perceived as a series of small events (Grinding),e.g., Rusks.\nCrackly- bite only once without grinding, it is one sudden low pitched sound event that brittles the product, e.g., cracker biscuits; sugar crystals are crackly too",
"is_mandatory": 1,
"select_type": 2,
"is_intensity": 1,
"intensity_type": 2,
"intensity_value": "None,Barely detectable,Identifiable but not very intense,Slightly intense,Moderately intense,Intense,Very intense,Extremely intense",
"option": "Crispy,Crunchy,Crackly"
},
{
"title": "FIRST CHEW",
"is_nested_question": 1,
"is_mandatory": 1,
"question": [{
"title": "Uniformity of bite",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
},
{
"title": "Denseness of mass",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
},
{
"title": "Crunchiness",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
}
]
},
{
"title": "CHEWDOWN EXPERIENCE",
"is_nested_question": 1,
"is_mandatory": 1,
"question": [{
"title": "Moisture absorption",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
},
{
"title": "Cohesiveness of mass",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Low,Medium,High"
}
]
},
{
"title": "RESIDUAL",
"is_nested_question": 1,
"is_mandatory": 1,
"question": [{
"title": "Loose particles",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Yes,No"
},
{
"title": "Mouthcoating / tooth stickiness",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Yes,No"
},
{
"title": "Dunking of rusk in tea/ milk/ coffee (4 seconds)",
"select_type": 1,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": "Drops,Lumpy,Holds good"
}
]
},
{
"title": "Overall preference",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 0
}
],
"PRODUCT EXPERIENCE": [{
"title": "Full product experience",
"select_type": 5,
"is_intensity": 0,
"is_nested_question": 0,
"is_mandatory": 1,
"option": [{
"value": "Don\'t like",
"color_code": "#8C0008"
},
{
"value": "Can\'t say",
"color_code": "#C92E41"
},
{
"value": "Somewhat like",
"color_code": "#AC9000"
},
{
"value": "Clearly like",
"color_code": "#577B33"
},
{
"value": "Love it",
"color_code": "#305D03"
}
]
},
{
"title": "Comments",
"select_type": 3,
"is_intensity": 0,
"is_mandatory": 0,
"is_nested_question": 0
}
]
}';

        $data = ['name'=>'generic_sliced_bread_v1','keywords'=>"generic_sliced_bread_v1",'description'=>null,
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