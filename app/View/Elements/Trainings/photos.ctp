<style type="text/css">
    
    .carousel .item img{
        margin: 0 auto; /* Align slide image horizontally center */
    }
    
    .bs-example{
        margin: 20px;
    }
</style>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Carousel indicators -->
    <ol class="carousel-indicators">
        <?php
        
        $p = 0;
        
        foreach($trn['TrainingFile'] as $img_array){
            $new_class = ($p == 0) ? 'active' : NULL ;
            ?>
            <li data-target="#myCarousel" data-slide-to="<?=$p?>" class="<?=$new_class?>"></li>
            <?php
            $p++;
        }
        ?>
    </ol>   
    
    <!-- Wrapper for carousel items -->
    <div class="carousel-inner">
        
        <?php
        $c = 0;
        
        $ar_count = count($trn['TrainingFile']);
        
        foreach($trn['TrainingFile'] as $img_array){
            $new_class = ($c == 0) ? 'active' : NULL ;
            ?>
            <div class="item <?=$new_class?>">
                <img src="/files/training/<?=$trn['Training']['id']?>/<?=$trn['TrainingFile'][$c]['file']?>" alt="<?=$trn['TrainingFile'][$c]['human_name']?>" width="900">
            </div>
            
            <?php
            $c++;
        }
        ?>
    </div>
    
    <!-- Carousel controls -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>