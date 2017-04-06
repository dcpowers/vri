<div id="myCarousel" class="carousel slide">
    <div class="carousel-inner" style="border: 1px #000 dotted">
        <?php
        $c = 0;
        
        $ar_count = count($train['TrainingFile']);
        
        foreach($train['TrainingFile'] as $img_array)
        {
            $new_class = ($c == 0) ? 'active' : NULL ;
            ?>
            <div class="item <?=$new_class?>">
                <img src="/blog/wp-content/uploads/<?=$train['TrainingFile'][$c]['file_name']?>"  style="width: 650px; height: 518px;" alt="<?=$train['Training']['title']?>">
            </div>
            
            <?php
            $c++;
        }
        ?>
    </div>
    
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
        
    
</div>