<?php
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
                    'url' => array('plugin'=> 'report', 'controller' => 'report', 'action' => 'nurse_retention', $id ,'member'=>false, 'ext'=>'pdf'),
                    
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
            <h1 class="text-center" style="text-align: center;"><?=AuthComponent::user('first_name')?> <?=AuthComponent::user('last_name')?></h1>
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
                $text = str_replace('{assigned_id}', $AssignedTest['id'], $text);
                $text = str_replace('{firstname}', $userInfo['first_name'], $text);
                $text = str_replace('{score}', $score, $text);
                ?>
                <div class="col-md-12 break">
                    <h1 class="text-center"><?=$item['Report']['name']?></h1>
                    <?=$text?>
                </div>
                <?php
            }
            ?> 
            <div class="clearfix"></div>
        </div>
    </div>
</div>