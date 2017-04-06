<?php
#pr($report);
?>
<div class="container">
    <div class="col-md-12">
        <div class="widget widget-no-header">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    <span class="sr-only">100% Complete</span>
                </div>
            </div>
            <h1 class="text-center">This Report was specially prepared for</h1>
            <h1 class="text-center"><?=AuthComponent::user('first_name')?> <?=AuthComponent::user('last_name')?></h1>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    <span class="sr-only">100% Complete</span>
                </div>
            </div>
            <?php
            foreach($report as $item){
                $text = str_replace('{uploadir}', AuthComponent::user('DetailUser.uploadDir'), $item['Report']['text']);
                ?>
                <div class="col-md-12">
                    <h1 class="text-center"><?=$item['Report']['name']?></h1>
                    <?=$text?>
                </div>
                <?php
                foreach($item['children'] as $section){
                    $text = str_replace('{uploadir}', AuthComponent::user('DetailUser.uploadDir'), $section['Report']['text']);
                    ?>
                    <div class="col-md-12">
                        <h2 class="text-center"><?=$section['Report']['name']?></h2>
                        <?=$text?>
                    </div>
                    <?php 
                    foreach($section['children'] as $sectionKey=>$statement){
                        foreach($answers as $key=>$answer){
                            if(!isset($answer['section_id'])) { echo $key; }
                            #pr($answer);
                            #exit;
                            if($statement['Report']['min_score'] == $answer['min_score'] && $statement['Report']['max_score'] == $answer['max_score'] && $statement['Report']['parent_id'] == $answer['section_id']){
                                $text = str_replace('{uploadir}', AuthComponent::user('DetailUser.uploadDir'), $statement['Report']['text']);
                                if(!empty($answer['avg'])){
                                    $text = str_replace('[value]', $answer['avg'], $text);
                                }
                                //echo $statement['Report']['graph_action'];
                                ?>
                                <div class="col-md-12">
                                    <?=$text?>
                                </div>
                                <?php
                                unset($answers[$key]);   
                            }
                        }
                    }
                }
            }
            ?> 
            <div class="clearfix"></div>
        </div>
    </div>
</div>