<style type="text/css">
.label-as-badge {
    border-radius: 5em;
}
</style>
<div class="container">
    <?php
    #pr($assignedTest);
    echo $this->Form->create('testing', array(
        'type' => 'file',
        'url' => array('controller'=>'tests', 'action'=>'process'), 
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
        <div class="progress-bar progress-bar-primary" data-transitiongoal="<?=$completed?>" style="width: <?=$completed?>%; min-width: 3em;" aria-valuenow="<?=$completed?>"><?=$completed?>%</div>
    </div>
    <?php
    if(!empty($assignedTest['TestRole']['name'])){
        ?>
        <ul class="list-inline">
            <li><h3><b>Who:</b>&nbsp;<small><?=$assignedTest['TestSchedule']['name']?></small></h3></li>
            <li><h3><b>Your Role:</b>&nbsp;<small><?=$assignedTest['TestRole']['name']?></small></h3></li>
        </ul>
        <?php
    }
    ?>        
    <?=showTest($content[0]['children']) ?>
    
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary col-md-offset-6')); ?>
    <?php echo $this->Form->end(); ?> 
</div>

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
                                        <div class="radio">
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