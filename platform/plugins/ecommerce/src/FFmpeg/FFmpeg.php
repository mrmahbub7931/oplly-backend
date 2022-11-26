<?php

namespace Canopy\Ecommerce\FFmpeg;

class FFmpeg {

    function __construct($video,$watermark,$name,$size,$opacity)
    {
        $this->video = $video;
        $this->watermark = $watermark;
        $this->name = $name;
        $this->size = $size;
        $this->opacity = $opacity;
    }

    public function runcmd(){
        
        shell_exec('ffmpeg -i '.$this->video.' -vf "movie='.$this->watermark.' [watermark]; [watermark]colorchannelmixer=aa='.$this->opacity.',scale=-2:'.$this->size.' [watermark2];[in][watermark2] overlay=W-w-60:H-h-40 [out]" '.base_path() . '/public/storage/orders/'.$this->name.'.mp4');
        // shell_exec('ffmpeg -i '.$this->video.' -i '.$this->watermark.' -filter_complex \"[1]colorchannelmixer=aa='.$this->opacity.',scale=-2:'.$this->size.':-1[wm];[0][wm]overlay=W-w-80:H-h-60"'.base_path() . '/public/storage/orders/'.$this->name.'.mp4');
    }
}