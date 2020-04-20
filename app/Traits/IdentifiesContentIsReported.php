<?php
namespace App\Traits;

/**
 * Returns the cached key of the model.
 *
 * Class isCached
 * @package App\Traits
 */
use App\ReportContent;

trait IdentifiesContentIsReported
{
    public function isReported($profile_id, $data_type, $data_id, $is_shared = false, $shared_id = null)
    {
        $reportContentExist = ReportContent::where("profile_id", $profile_id)
            ->where("data_type", $data_type)
            ->where("data_id", $data_id)
            ->where("is_shared", $is_shared)
            ->where("shared_id", $shared_id)
            ->exists();
        
        return $reportContentExist;
    }
}