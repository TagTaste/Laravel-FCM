<?php

namespace App\Shareable;

use App\Payment\PaymentDetails;
use App\PaymentHelper;
use App\PeopleLike;
use Illuminate\Support\Facades\Redis;
use App\Traits\HashtagFactory;
use App\Company;

class Quiz extends Share
{
    use HashtagFactory;
    protected $table = 'quiz_shares';

    protected $fillable = ['profile_id', 'quiz_id', 'payload_id', 'privacy_id', 'content'];

    protected $visible = ['id', 'profile_id', 'created_at', 'content'];

    protected $with = ['quiz'];

    protected $appends = ["totalApplicants"];

    public function quiz()
    {
        return $this->belongsTo(\App\Quiz::class, 'quiz_id');
    }

    public function getMetaFor($profileId)
    {
        $meta = [];
        $key = "meta:quizShare:likes:" . $this->id;

        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'quizShare', request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();

        $quiz = \App\Quiz::where('id', $this->quiz_id)->whereNull('deleted_at')->first();
        if ($quiz) {
            $meta['originalPostMeta'] = $quiz->getMetaFor($profileId); //Because off android this response is changes 
            //from original_post_meta to originalPostMeta 
        }
        $payment = PaymentDetails::where("model_type", "quiz")->where("model_id", $this->quiz_id)->where("is_active", 1)->first();

        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        $k = Redis::get("quiz:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:quizShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $quiz = \App\Quiz::where('id', $this->quiz_id)->whereNull('deleted_at')->first();
        if ($quiz) {
            $meta['originalPostMeta'] = $quiz->getMetaFor($profileId); //Because off android this response is changes 
            //from original_post_meta to originalPostMeta 
        }
        $payment = PaymentDetails::where("model_type", "quiz")->where("model_id", $this->quiz_id)->where("is_active", 1)->first();

        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        $k = Redis::get("quiz:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {
        return $this->getMetaForV2($profileId);
    }

    public function getMetaForPublic()
    {
        $meta = [];
        $key = "meta:quizShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->surveys_id,
            // 'id' => 1,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : null,
            'image' => null,
            'shared' => true
        ];
    }

    /**
     * @param int $profileId
     * @return array
     */


    public function getTotalApplicantsAttribute()
    {
        $sur = \DB::table("quizes")->where("id", $this->surveys_id)->first();
        if (!empty($sur)) {
            $c = false;
            if (isset($sur->company_id) && !empty($sur->company_id)) {
                $userId = request()->user()->id;
                $company = Company::find($sur->company_id);
                $userBelongsToCompany = $company->checkCompanyUser($userId);
                if ($userBelongsToCompany) {
                    $c = true;
                }
            }


            if ($c || (request()->user()->profile->id == $sur->profile_id)) {
                return \DB::table('quiz_applicants')->where('quiz_id', $this->surveys_id)->whereNull('deleted_at')->get()->count();
            }
        }

        return 0;
    }
}
