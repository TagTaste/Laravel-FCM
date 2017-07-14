<?php

namespace App\Shareable;

use App\Shareable\Share;


class Job extends Share
{
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => null,
            'shared' => true
        ];
    }
}
