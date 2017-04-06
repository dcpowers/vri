<?php
    $user = AuthComponent::user();
    
    $v_img      = $train['TrainingFile'][0]['file_name'];
    $v_video    = $train['TrainingFile'][1]['file_name'];
?>
    <video id="hse_video" class="video-js vjs-default-skin" width="640" height="480" data-setup='{ "controls": true, "autoplay": false, "preload": "auto", "poster": "/blog/wp-content/uploads/<?=$v_img?>"  }'>
        <source src="/blog/wp-content/uploads/<?=$v_video?>.mp4" type="video/mp4">
        <source src="/blog/wp-content/uploads/<?=$v_video?>.webm" type="video/webm">
    </video>
    
    <p class='vjs-no-video'><strong>Download Video:</strong> 
        <a href="/blog/wp-content/uploads/<?=$v_video?>.mp4">MP4</a>
    </p>