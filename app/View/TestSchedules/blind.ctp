<?php
echo $this->Form->create('TestSchedule', array(
    'url' => array('controller'=>'TestSchedules', 'action'=>'Blind', $test_id),
    'role'=>'form',
    #'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
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
                <label class="control-label" for="name">Name:</label>
                <?php
                echo $this->Form->input('name', array (
                    'type'=>'text',
                    'data-placeholder'=>'Create A Name',
                ));
                ?>
                <small>Example Name: Annual Review ( Month/Year )</small>
            </div>

            <div class="form-group">
                <label class="control-label" for="name">Ask For Department:</label>
                <?php
                echo $this->Form->input('ask_group', array (
                    'options'=>array(0=>'No', 1=>'Yes'),
                    'type'=>'select',
                    'data-placeholder'=>'Create A Name',
                ));
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