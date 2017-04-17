<?php
    for($i=0;$i<=10;$i++){
        $creditsArray[$i] = $i;
    }
    
    #$settings['scheduleType']['Single'] = 'Assessment';
    #$settings['scheduleType']['Group'] = 'Survey';
    $settings['scheduleType']['MultiplePeople'] = 'Evaluation';
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h2>Add New Test</h2>
</div>

<div class="modal-body">
    <?php 
    echo $this->Form->create('TestGroup', array(
        'url' => array('controller'=>'TestGroups', 'action'=>'add', 'member'=>true), 
        'role'=>'form',
        //'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
    ?>
    <div class="form-group">
        <label class="control-label" for="name">Title/Name:</label>
        <?php 
        echo $this->Form->input('name', array (
            'type'=>'text',
            'placeholder' => 'Name',
            'id'=>'name',
            'class'=>'form-control'
            ));
        ?>
    </div>
    
    <div class="form-group">
        <label class="control-label" for="name">Status:</label>
        <?php 
        echo $this->Form->input('is_active', array (
            'type'=>'select',
            'options'=>$settings['options'],
            'placeholder' => 'Status',
            'id'=>'status',
        ));
        ?>
    </div>
    
    <div class="form-group">
        <label class="control-label" for="name">Test Type:</label>
        <?php 
        echo $this->Form->input('schedule_type', array (
            'type'=>'select',
            'options'=>$settings['scheduleType'],
            'placeholder' => 'Schedule Type',
            'id'=>'schedule_type',
        ));
        ?>
    </div>
    
    <div class="form-group">
        <label class="control-label" for="name">Credits:</label>
        <?php
        unset($creditsArray[0]);
        
        echo $this->Form->input('credits', array (
            'type'=>'select',
            'options'=>$creditsArray,
            'placeholder' => 'credits',
            'id'=>'credits',
        ));
        ?>
    </div>
    
    
            
    <div class="submit">
        <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        <?php echo $this->Html->link( __('Cancel'), array('controller'=>'TestGroups', 'action'=>'index', 'member'=>true ), array('class'=>'btn')  ); ?>
    </div>
    <?php echo $this->Form->end(); ?>
    
</div>
            
<div class="modal-footer">
                            
</div>

<script language="JavaScript">
    jQuery(document).ready( function($) {
        
    });
</script>
