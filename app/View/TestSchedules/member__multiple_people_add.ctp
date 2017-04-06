<?php
echo $this->Form->create('TestSchedule', array(
    'url' => array('controller'=>'TestSchedules', 'action'=>'MultiplePeople_add', 'member'=>true, $test_id), 
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('test_id', array('value'=>$test_id));
echo $this->Form->hidden('test_schedule_id', array('value'=>$test_schedule_id));
?>

<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div>            <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    for($i=0;$i<=4;$i++){
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="name">Role:</label>
                                <?php echo $this->Form->input($i.'.AssignedTest.test_role_id', array (
                                    'options'=>$roles,
                                    'multiple'=>false,
                                    'empty'=>true,
                                    'data-placeholder'=>'Select Role'
                                ));?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-6">
                        
                    <?php
                    for($j=0;$j<=4;$j++){
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="name">User:</label>
                                <?php echo $this->Form->input($j.'.AssignedTest.user_id', array (
                                    'options'=>$users,
                                    'multiple'=>false,
                                    'empty'=>true,
                                    'data-placeholder'=>'Select User'
                                ));?>
                            
                        </div>
                        <?php
                    }
                    ?>
                </div>
            
        </div>
    </div>
</div><!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
            <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end();?>    

<script type="text/javascript">
    jQuery(window).ready( function($) {
    });
</script> 