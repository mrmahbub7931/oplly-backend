<?php

namespace Canopy\Ecommerce\FFmpeg;

class FFmpeg {

    function __construct($video,$watermark,$name,$size)
    {
        $this->video = $video;
        $this->watermark = $watermark;
        $this->name = $name;
        $this->size = $size;
    }

    public function runcmd(){
        
        shell_exec('ffmpeg -i '.$this->video.' -vf "movie='.$this->watermark.' [watermark]; [watermark]scale=-2:'.$this->size.' [watermark2];[in][watermark2] overlay=W-w-5:H-h-5 [out]" '.config('filesystems.disks.s3.url') . '/orders/'.$this->name.'.mp4');
    }
}