<?php
$sortArray[8] = $answers[8]['avg'];
$sortArray[9] = $answers[9]['avg'];
$sortArray[10] = $answers[10]['avg'];
$ext = explode(".", Router::url( $this->here, true ));

?>

<style type="text/css">
    .break {page-break-before: always;}    
</style>
<div class="container">
    <div class="col-md-12">
        <?php
        if(!isset($ext[2]) || $ext[2] != 'pdf'){
            ?>
            <div class="pull-right hideLink">
                <?php
                echo $this->Html->image("pdfPrint.gif", array(
                    'fullBase' => true,
                    'alt' => 'iWorkZone',
                    'width'=>'48px',
                    'height'=>'48px',
                    'url' => array('plugin'=> 'report', 'controller' => 'report', 'action' => 'iWRaCIP_individual', $id ,'member'=>false, 'ext'=>'pdf'),
                    
                ));                    
                ?>
            </div>
            <?php
        }
        ?>
        <div class="clearfix"></div>                     
        <div class="widget widget-no-header">
            <div style="color:#428BCA; background-color:#428BCA;height:25px;"></div>
            <h1 class="text-center" style="text-align: center;">This Report was specially prepared for</h1>
            <h1 class="text-center" style="text-align: center;"><?=$userInfo['first_name']?> <?=$userInfo['last_name']?></h1>
            <div style="color:#428BCA; background-color:#428BCA;height:25px;"></div>
            <div class="logo" style="text-align: center">
                <?php
                echo $this->Html->image("iworklogo.png", array(
                    'fullBase' => true,
                    'alt' => 'iWorkZone'
                ));                    
                ?>
            </div>
            <?php
            foreach($report as $item){
                $text = str_replace('{uploadir}', $folder, $item['Report']['text']);
                ?>
                <div class="col-md-12 break">
                    <h1 class="text-center"><?=$item['Report']['name']?></h1>
                    <?=$text?>
                </div>
                <?php
                foreach($item['children'] as $section){
                    $text = str_replace('{uploadir}', $folder, $section['Report']['text']);
                    ?>
                    <div class="col-md-12 break">
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
                                $text = str_replace('{uploadir}', $folder, $statement['Report']['text']);
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