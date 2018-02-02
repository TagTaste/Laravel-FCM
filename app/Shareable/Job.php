<?php

namespace App\Shareable;

use App\Shareable\Share;


class Job extends Share
{
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->job->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->job->title,
            'image' => null,
            'shared' => true
        ];
    }
}
