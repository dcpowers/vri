<?php 
echo $this->Form->create('JobQuestion', array(
    'url' => array('controller'=>'jobQuestions', 'action'=>'add', 'member'=>true), 
    'role'=>'form',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));

?>
<div class="container">
    <h2 class="title">New Screening Questions</h2>
    <hr class="solidOrange" />

    <div class="form-group">
        <label class="control-label" for="name">Name:</label>
        <?php 
        echo $this->Form->input('name', array (
            'type'=>'text',
            'placeholder' => 'Job Name',
        ));
        ?>
    </div>
    
    <?php
    for($i = 1; $i<=5; $i++){
        $q = (!empty($jobQuestion[0]['JobQuestionDetail'][$i - 1]['question'])) ? $jobQuestion[0]['JobQuestionDetail'][$i - 1]['question'] : null ;
        $o = (!empty($jobQuestion[0]['JobQuestionDetail'][$i - 1]['option'])) ? $jobQuestion[0]['JobQuestionDetail'][$i - 1]['option'] : null ;
        $current_id = (!empty($jobQuestion[0]['JobQuestionDetail'][$i - 1]['id'])) ? $jobQuestion[0]['JobQuestionDetail'][$i - 1]['id'] : null ;
                 
        ?>          
        <div class="form-group">
            <div class="row">
                <div class="col-md-8">
                    <label class="control-label" for="question">Question <?=$i?>:</label>
                    <?php echo $this->Form->hidden('JobQuestionDetail.' .$i. '.id', array('value'=>$current_id));?>
                    <?php echo $this->Form->input('JobQuestionDetail.' .$i. '.question', array (
                        'type'=>'text',
                        'placeholder' => 'Add A Question',
                        'value'=>$q
                    ));?>
                </div>
                 
                <div class="col-md-4">
                    <label class="control-label" for="question">Response <?=$i?>:</label>
                    <?php
                    echo $this->Form->select('JobQuestionDetail.' .$i. '.option', 
                        array($settings['questionOpt']),
                        array(
                            'empty' => 'Select An Option', 
                            'multiple' => false, 
                            'class'=>'form-control',
                            'default'=>$o
                        )
                    );    
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php 
    echo $this->Html->link( 
        '<i class="fa fa-times"></i> Cancel', 
        array('controller'=>'jobQuestions', 'action'=>'index', 'member'=>true ), 
        array('escape'=>false, 'class'=>'btn btn-default btn-sm')  
    ); 
    ?>
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm')); ?>
    <hr/>    
</div>
<?php echo $this->Form->end();?>    

<script language="JavaScript">
    $(document).ready( function() {
        $(".chosen-select").chosen();
    });
</script>    