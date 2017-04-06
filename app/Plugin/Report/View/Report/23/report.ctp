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
                'url' => array('plugin'=> 'report', 'controller' => 'report', 'action' => 'pro_screen_employee', $id ,'member'=>false, 'ext'=>'pdf'),
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
                $text = str_replace('{assigned_id}', $AssignedTest['id'], $text);
                $text = str_replace('{firstname}', $userInfo['first_name'], $text);
                ?>
                <div class="col-md-12 break">
                    <h1 class="text-center"><?=$item['Report']['name']?></h1>
                    <?=$text?>
                </div>
                <?php
                foreach($item['children'] as $section){
                    $text = str_replace('{uploadir}', $folder, $section['Report']['text']);
                    $text = str_replace('{assigned_id}', $AssignedTest['id'], $text);
                    $text = str_replace('{firstname}', $userInfo['first_name'], $text);
                    ?>
                    <div class="col-md-12 break">
                        <h2 class="title"><?=$section['Report']['name']?></h2>
                        <?=$text?>
                    </div>
                    <?php
                    foreach($section['children'] as $sectionKey=>$statement){
                        foreach($averages as $key=>$answer){
                            if($answer['section'] == $statement['Report']['parent_id']){
                                $avg = round($answer['avg'], 2);
                                $min= round($statement['Report']['min_score'], 2);
                                $max= round($statement['Report']['max_score'], 2);
                                
                                if($min <= $avg && $max >= $avg && $statement['Report']['parent_id'] == $answer['section']){
                                    $text = str_replace('{uploadir}', $folder, $statement['Report']['text']);
                                    $text = str_replace('{key}', $key, $text);
                                    $text = str_replace('{firstname}', $userInfo['first_name'], $text);
                                    $text = str_replace('{3128}', $answers[23][246][3128]['answerText'], $text); 
                                    $text = str_replace('{3129}', $answers[23][246][3129]['answerText'], $text);
                                    $text = str_replace('{3130}', $answers[23][246][3130]['answerText'], $text);
                                    $text = str_replace('{3131}', $answers[23][246][3131]['answerText'], $text);
                                    $text = str_replace('{3132}', $answers[23][246][3132]['answerText'], $text);
                                    $text = str_replace('{3133}', $answers[23][246][3133]['answerText'], $text);
                                    $text = str_replace('{3136}', $answers[23][246][3136]['answerText'], $text);
                                    $text = str_replace('{3135}', $answers[23][246][3135]['answerText'], $text);
                                    $text = str_replace('{3148}', $answers[23][246][3148]['answerText'], $text);
                                    $text = str_replace('{3137}', $answers[23][246][3137]['answerText'], $text);
                                    $text = str_replace('{3138}', $answers[23][246][3138]['answerText'], $text);
                                    $text = str_replace('{3140}', $answers[23][246][3140]['answerText'], $text);
                                    $text = str_replace('{3146}', $answers[23][246][3146]['answerText'], $text);
                                    $text = str_replace('{3141}', $answers[23][246][3141]['answerText'], $text);
                                    $text = str_replace('{3142}', $answers[23][246][3142]['answerText'], $text);
                                    $text = str_replace('{3143}', $answers[23][246][3143]['answerText'], $text);
                                    $text = str_replace('{3149}', $answers[23][246][3149]['answerText'], $text);
                                    $text = str_replace('{3147}', $answers[23][246][3147]['answerText'], $text);
                                    $text = str_replace('{3155}', $answers[23][246][3155]['answerText'], $text);
                                    $text = str_replace('{3159}', $answers[23][246][3159]['answerText'], $text);
                                    $text = str_replace('{3160}', $answers[23][246][3160]['answerText'], $text);
                                    $text = str_replace('{3134}', $answers[23][246][3134]['answerText'], $text);
                                    $text = str_replace('{3158}', $answers[23][246][3158]['answerText'], $text);
                                    $text = str_replace('{3144}', $answers[23][246][3144]['answerText'], $text);
                                    $text = str_replace('{3145}', $answers[23][246][3145]['answerText'], $text);
                                    $text = str_replace('{3153}', $answers[23][246][3153]['answerText'], $text);
                                    $text = str_replace('{3154}', $answers[23][246][3154]['answerText'], $text);
                                    $text = str_replace('{3150}', $answers[23][246][3150]['answerText'], $text);
                                    $text = str_replace('{3151}', $answers[23][246][3151]['answerText'], $text);
                                    $text = str_replace('{3152}', $answers[23][246][3152]['answerText'], $text);
                                    $text = str_replace('{3156}', $answers[23][246][3156]['answerText'], $text);
                                    $text = str_replace('{3157}', $answers[23][246][3157]['answerText'], $text);
                                    
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
            }
            ?> 
            <div class="clearfix"></div>
        </div>
        
        <?php
        foreach($extreme as $key=>$item){
            ?>
            <div class="col-md-12">
                <h2 class="title"><?=$key?></h2>
                <dl class="lead">
                    <?php
                    foreach($item as $record){
                        ?>
                        <dt><?=$record['question']?></dt>
                        <dd><?=$record['answer']?></dd>
                        <?php
                    }
                    ?>
                </dl>
            </div>
            <?php
        }
        ?>
        <div class="clearfix"></div>
        
    </div>

