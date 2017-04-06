<?php
$ext = explode(".", Router::url( $this->here, true ));
?>

<style type="text/css">
    .break {page-break-before: always;}
</style>

<div class="container">
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
                'url' => array('plugin'=> 'report', 'controller' => 'report', 'action' => 'evaluationSummary', $id ,'member'=>false, 'ext'=>'pdf'),
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
            <h1 class="text-center" style="text-align: center;"><?=$userInfo['User']['fullname']?></h1>
            <div style="color:#428BCA; background-color:#428BCA;height:25px;"></div>
            <div class="logo" style="text-align: center">
                <?php
                echo $this->Html->image("iworklogo.png", array(
                    'fullBase' => true,
                    'alt' => 'iWorkZone'
                ));                    
                ?>
            </div>
            
            <div class="col-md-12">
                <h1 class="text-center"><?=$test['Test']['name']?></h1>
                <?=$test['Test']['report_introduction']?>
            </div>
            
            <div class="col-md-12 break">
                <h1 class="text-center" style="color:#336699; font: bold Helvetica, serif;">Competency Summary</h1>
                <?php
                echo $this->Html->image(
                    '../'.$summaryGraph,
                    array('class'=>'center-block')
                );
                ?>
            </div>
            
            <div class="col-md-12 break">
                <h1 class="text-center" style="color:#336699; font: bold Helvetica, serif;">Competency Overview</h1>
                
                <?php
                $report = Hash::expand($report);
                foreach($report as $item){
                    ?>
                    <div class="col-md-12">
                        <h3 class="text-left" style="color:#336699; font: Helvetica, serif;"><?=$item['category']['catName']?></h3>
                        <?=$item['category']['catReport']?>
                    </div>
                    <?php
                    echo $this->Html->image(
                        '../'.$item['category']['catRoleGraph'],
                        array('class'=>'center-block')
                    );
                }
                ?>
            </div>
            <div class="col-md-12 break">
                <h1 class="text-center" style="color:#336699; font: bold Helvetica, serif;">Competency Details</h1>
                
                <?php
                foreach($report as $item){
                    foreach($item['category']['questions'] as $details){
                        #pr($details);
                        ?>
                        <div class="col-md-12">
                            <h3 class="text-left" style="color:#336699; font: Helvetica, serif;"><?=$details['name']?></h3>
                        </div>
                        <?php
                        if(isset($details['qRoleGraph'])){
                            echo $this->Html->image(
                                '../'.$details['qRoleGraph'],
                                array('class'=>'center-block')
                            );
                        }
                        
                        if(isset($details['comment'])){
                            ?>
                            <dl class="dl-horizontal">
                                <?php
                                foreach($details['comment'] as $role=>$comment){
                                    ?>
                                    <dt><?=$role?></dt>
                                    <dd><?=$comment?></dd>
                                    <?php
                                }
                                ?>
                            </dl>
                            <?php
                            
                        }
                    }
                }
                ?> 
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        
    </div>

