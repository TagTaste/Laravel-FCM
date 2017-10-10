<?php

namespace App;

class Preview
{
    private $tags;
    
    public function __construct($url)
    {
        $source = $this->downloadHTMl($url);
    
        $html = new \DOMDocument();
        @$html->loadHTML($source);
    
        $this->tags = $html->getElementsByTagName('meta');
    }
    public static function get($url)
    {
        $key = "preview:" . sha1($url);
        if(!\Redis::exists($key)){
            $self = new self($url);
            $tags = $self->parseFacebookTags();
            \Redis::set($key,json_encode($tags));
        }
    
        return json_decode(\Redis::get($key));
       
    }
    
    private function downloadHTMl(&$url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Get the response and close the channel.
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    
    protected function parseFacebookTags()
    {
        $meta = [];
       
        foreach($this->tags as $tag) {
            if(str_contains($tag->getAttribute('property'),'og')){
                $property = $tag->getAttribute('property');
                $property = substr($property,3);
                $value = $tag->getAttribute('content');
                $meta[$property] = $value;
            }
        }
        return $meta;
    }
}
