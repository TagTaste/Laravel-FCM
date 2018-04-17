<?php


namespace App\Traits;


trait HasPreviewContent
{
    public function getContent($text)
    {
        if(is_null($text)){
            return;
        }
        
        if(isset($text['text'])){
            $text = $text['text'];
        }
        
        $profiles = $this->getTaggedProfiles($text);
        $pattern = [];
        $replacement = [];
        if($profiles == false) {
            return $text;
        }
        foreach ($profiles as $index => $profile)
        {
            $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
            $replacement[] = $profile->name;
        }
        $replacement = array_reverse($replacement);
        return preg_replace($pattern,$replacement,$text);
    }

    public function getContentForHTML($text) {
        if(is_null($text)){
            return;
        }

        if(isset($text['text'])){
            $text = $text['text'];
        }

        $profiles = $this->getTaggedProfiles($text);
        $pattern = [];
        $replacement = [];
        if($profiles == false) {
            return $text;
        }
        foreach ($profiles as $index => $profile)
        {
            $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
//            $replacement[] = "<a href=\"/profile/{$profile->id}\" class=\"tagged-profile\">$profile->name</a>";
            $replacement[] = "<a href=\"#\" class=\"tagged-profile\">$profile->name</a>";
        }
        $replacement = array_reverse($replacement);
        return preg_replace($pattern,$replacement,$text);
    }
}