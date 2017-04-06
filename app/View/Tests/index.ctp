<?php pr($userAssign); ?>
<?php pr($test); exit; ?>


<div class="container">
    <div class="row">
        <h1 class="title">
            <?=$test[0]['Test']['name']?>
        </h1>
        
        <?php
        foreach($test[0]['children'] as $category){
            echo $category['Test']['name'];
            
            foreach($category['children'] as $subCategory){
                if(empty($subCategory['children'])) { $is_question = 1; }else{ $is_question = 0; }
            }
        }            
        ?>
    </div>
</div>
