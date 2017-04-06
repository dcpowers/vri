<?php
echo $this->Form->create('AssignedTest', array(
    'url' => array('controller'=>'TestSchedules', 'action'=>'Single', 'member'=>true, $test_id), 
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control chosen-select',
        'error'=>false
    )
));
echo $this->Form->hidden('test_id', array('value'=>$test_id));
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
            <div class="form-group">
                <label class="col-sm-4 control-label" for="name">Name:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('user_id', array (
                        'options'=>$users,
                        'multiple'=>true,
                        'empty'=>false,
                        'data-placeholder'=>'Select User(s)'
                        //'value'=>$user[0]['User']['first_name']
                    ));?>
                </div>
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
            $(".chosen-select").chosen({ width: "inherit" })
        
        
    });
</script> 