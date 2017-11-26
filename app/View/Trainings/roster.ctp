<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'roster', $training_id, 'ext'=>'pdf'), 
        'role'=>'form',
        'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
    
    echo $this->Form->hidden('training_id', array('value'=>$training_id));
?>

<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Create Roster: '); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Training Date:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'date', array(
                'type'=>'text',
                'required'=>false,
                'label'=>false,
                'value'=>date('m/d/Y', strtotime('now')),
                'class'=>'datepicker form-control'
            ));
            ?>
        </div>
    </div>
                    
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Department(s):</label>
        <div class="col-sm-8">
            <?php 
            echo $this->Form->input('department_id', array (
                'options'=>$depts,
                'type'=>'select',
                'empty'=>true,
                'multiple'=>true,
                'class'=>'form-select chzn-select',
                'data-placeholder'=>'Select Department(s)'
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">User(s):</label>
        <div class="col-sm-8">
            <?php 
            echo $this->Form->input('user_id', array (
                'options'=>$users,
                'type'=>'select',
                'empty'=>true,
                'multiple'=>true,
                'class'=>'form-select chzn-select',
                'data-placeholder'=>'Select User(s)'
            ));
            ?>
        </div>    
    </div>
</div>

<div class="modal-footer">
    <?php 
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Cancel',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    ); 
    ?>
    
    <?php 
    echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    ); 
    ?>
</div>
<?php echo $this->Form->end();?>

<?php
$userRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'userListByDept'));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
        $('.datepicker').datetimepicker({
            'format': 'MM/DD/YYYY',
            'showTodayButton': true,
            'icons': {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                close: "fa fa-trash",
            }
        });
        
        $('#TrainingDepartmentId').change(function() {
            $.ajax({
                type: 'post',
                url: '<?=$userRequest_url?>/' + $(this).val() + '.json',
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    
                },
                success: function(response) {
                    console.log(response);
                    $('#TrainingUserId').html(response);
                    $('#TrainingUserId' ).val(response).trigger( 'chosen:updated' );
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function(){
                    $('#overlay').remove();
                },
            });
        });
    });
</script>