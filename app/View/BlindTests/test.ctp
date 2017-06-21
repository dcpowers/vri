<style type="text/css">
    .label-as-badge {
        border-radius: 5em;
    }
    
    .radio, .form-horizontal .checkbox{
        min-height: 0;
        
    }
    .progress {
    overflow: hidden;
    height: 18px;
    margin-bottom: 18px;
    background-color: #f7f7f7;
    background-image: -moz-linear-gradient(top, #f5f5f5, #f9f9f9);
    background-image: -ms-linear-gradient(top, #f5f5f5, #f9f9f9);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f5f5), to(#f9f9f9));
    background-image: -webkit-linear-gradient(top, #f5f5f5, #f9f9f9);
    background-image: -o-linear-gradient(top, #f5f5f5, #f9f9f9);
    background-image: linear-gradient(top, #f5f5f5, #f9f9f9);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5f5f5', endColorstr='#f9f9f9', GradientType=0);
    -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}

.progress .bar {
    width: 0%;
    height: 18px;
    color: #ffffff;
    font-size: 12px;
    text-align: center;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    background-color: #0e90d2;
    background-image: -moz-linear-gradient(top, #149bdf, #0480be);
    background-image: -ms-linear-gradient(top, #149bdf, #0480be);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#149bdf), to(#0480be));
    background-image: -webkit-linear-gradient(top, #149bdf, #0480be);
    background-image: -o-linear-gradient(top, #149bdf, #0480be);
    background-image: linear-gradient(top, #149bdf, #0480be);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#149bdf', endColorstr='#0480be', GradientType=0);
    -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
    -moz-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-transition: width 0.6s ease;
    -moz-transition: width 0.6s ease;
    -ms-transition: width 0.6s ease;
    -o-transition: width 0.6s ease;
    transition: width 0.6s ease;
}

.progress-striped .bar {
    background-color: #62c462;
    background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
    background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    -webkit-background-size: 40px 40px;
    -moz-background-size: 40px 40px;
    -o-background-size: 40px 40px;
    background-size: 40px 40px;
}

.progress.active .bar {
    -webkit-animation: progress-bar-stripes 2s linear infinite;
    -moz-animation: progress-bar-stripes 2s linear infinite;
    animation: progress-bar-stripes 2s linear infinite;
}

.progress-danger .bar {
    background-color: #dd514c;
    background-image: -moz-linear-gradient(top, #ee5f5b, #c43c35);
    background-image: -ms-linear-gradient(top, #ee5f5b, #c43c35);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee5f5b), to(#c43c35));
    background-image: -webkit-linear-gradient(top, #ee5f5b, #c43c35);
    background-image: -o-linear-gradient(top, #ee5f5b, #c43c35);
    background-image: linear-gradient(top, #ee5f5b, #c43c35);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ee5f5b', endColorstr='#c43c35', GradientType=0);
}

.progress-danger.progress-striped .bar {
    background-color: #ee5f5b;
    background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
    background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.progress-success .bar {
    background-color: #5eb95e;
    background-image: -moz-linear-gradient(top, #62c462, #57a957);
    background-image: -ms-linear-gradient(top, #62c462, #57a957);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#57a957));
    background-image: -webkit-linear-gradient(top, #62c462, #57a957);
    background-image: -o-linear-gradient(top, #62c462, #57a957);
    background-image: linear-gradient(top, #62c462, #57a957);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462', endColorstr='#57a957', GradientType=0);
}

.progress-success.progress-striped .bar {
    background-color: #62c462;
    background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
    background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

.progress-info .bar {
    background-color: #4bb1cf;
    background-image: -moz-linear-gradient(top, #5bc0de, #339bb9);
    background-image: -ms-linear-gradient(top, #5bc0de, #339bb9);
    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#339bb9));
    background-image: -webkit-linear-gradient(top, #5bc0de, #339bb9);
    background-image: -o-linear-gradient(top, #5bc0de, #339bb9);
    background-image: linear-gradient(top, #5bc0de, #339bb9);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#339bb9', GradientType=0);
}

.progress-info.progress-striped .bar {
    background-color: #5bc0de;
    background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
    background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
    background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}

@-webkit-keyframes progress-bar-stripes { 
    from { background-position: 0 0 }

    to { background-position: 40px 0 }
}

@-moz-keyframes progress-bar-stripes { 
    from { background-position: 0 0 }

    to { background-position: 40px 0 }
}

@keyframes progress-bar-stripes { 
    from { background-position: 0 0 }

    to { background-position: 40px 0 }
}
</style>
<section class="page-block">

<div class="container">
    <?php
    echo $this->Form->create('BlindTest', array(
        'type' => 'file',
        'url' => array('controller'=>'BlindTests', 'action'=>'process'), 
        'role'=>'form',
        'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    )); 
    echo $this->Form->hidden( 'id', array( 'value' => $id ) );
    
    $new[] = $content[0]['Test']['id'];
    ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2">
                <?php
                $image = (!empty($content[0]['Test']['logo'])) ? $content[0]['Test']['logo'] : 'testing.jpg' ;
                            
                echo $this->Html->image($image, array('class'=>'img-responsive img-thumbnail'));
                ?>
            </div>
            <div class="col-md-10">
                <h1><?=$content[0]['Test']['name']?></h1>
                <hr />
                <p><?=$content[0]['Test']['introduction']?></p>
            </div>
        </div>
    </div>
            
    <div class="progress active">
        <div class="bar progress-bar progress-bar-primary" data-transitiongoal="<?=$completed?>" style="width: <?=$completed?>%; min-width: 3em;" aria-valuenow="<?=$completed?>"><?=$completed?>%</div>
    </div>
            
    <?=showTest($content[0]['children'], $new) ?>
    
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary col-md-offset-6')); ?>
    <?php echo $this->Form->end(); ?> 
</div>
</section>
<?php
    function showTest($array){
        if (count($array)) {
            foreach ($array as $key=>$vals) {
                $text = (!empty($vals['Test']['introduction'])) ? $vals['Test']['introduction'] : null ;
                ?>
                <h2><?=$vals['Test']['name']?></h2>
                <?=$text?>
                <hr class="solid" />
                <?php
                list($next, $value) = each($vals['children']);
                
                switch($value['Test']['category_type']){
                    case '2':
                        showTest($vals['children']);
                        break;
                    
                    case '3':
                        getQuestions($vals['children']);
                        break;
                }
            }
        }
    }
    
    function getQuestions($array){
        if (count($array)) {
            foreach ($array as $key=>$vals) {
                if(empty($vals['children'])){
                    getCommentBox($vals);
                }else{
                    ?>
                    <div class="form-group">
                        <div class="list-group">
                            <div class="list-group-item">
                                <label class="col-sm-6 control-label">
                                    <?=$vals['Test']['name']?> <?=$vals['Test']['introduction']?>
                                </label>
                                
                                <div class="col-md-6">
                                    <?php
                                    foreach($vals['children'] as $answer){
                                        
                                        ?>
                                        <div class="radio col-md-12" style="min-height: 0px;">
                                            <label>
                                                <input type="radio" name="t_ans[<?=$vals['Test']['id']?>]" id="<?=$answer['Test']['id']?>" value="<?=$answer['Test']['id']?>" />
                                                <?=$answer['Test']['name']?>
                                            </label>
                                        </div>
                                        <?php    
                                    }
                                    ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
    
    function getCommentBox($vals=null){
        ?>
        <div class="form-group">
            <div class="list-group">
                <div class="list-group-item">
                    <label class="col-sm-6 control-label">
                        <?=$vals['Test']['name']?> <?=$vals['Test']['introduction']?>
                    </label>
                            
                    <div class="col-md-6">
                        <textarea rows="4" cols="50" name="t_ans[<?=$vals['Test']['id']?>]"></textarea>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
?>