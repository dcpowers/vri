<?php
echo $this->Form->create('TestSchedule', array(
    'url' => array('controller'=>'TestSchedules', 'action'=>'Group', 'member'=>true, $test_id), 
    'role'=>'form',
    //'class'=>'form-horizontal',
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
                <label class="control-label" for="name">Name:</label>
                <?php echo $this->Form->input('TestSchedule.name', array (
                    'type'=>'text',
                    'data-placeholder'=>'Create A Name',
                ));?>
                <small><b>Example Name:</b> Employee Engagement Fall 2015</small>
            </div>
            <div class="row">
                <div class="col-md-12">
                <?php
                echo $this->Form->input('TestSchedule.groups_id', array(
                    'type'=>'select',
                    'options'=>$groups,
                    'multiple' => true, 
                    'name'=>'groups_id'
                ));
                ?>
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
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        var demo1 = $('select[name="groups_id[]"]').bootstrapDualListbox({
            nonSelectedListLabel: 'Add Locations/Departments',
            selectedListLabel: 'Active List',
        });
        
        var container1 = demo1.bootstrapDualListbox('getContainer');
        container1.find('.btn').addClass('btn-xs btn-default');
    });
</script> 