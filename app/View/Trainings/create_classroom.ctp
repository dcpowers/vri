<?php
    echo $this->Form->create('TrainingClassroom', array(
        'url' => array('controller'=>'Trainings', 'action'=>'createClassroom', $training_id),
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
    echo $this->Form->hidden('account_id', array('value'=>$account_id));
    echo $this->Form->hidden('name', array('value'=>$name));
?>

<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Create Classroom: '); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Date Of Class:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'date', array(
                'type'=>'text',
                'required'=>false,
                'label'=>false,
                'value'=>date('m/d/Y', strtotime('today')),
                'class'=>'datepicker form-control'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Instructor:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('instructor_id', array (
                'options'=>$users,
                'type'=>'select',
                'empty'=>true,
                'multiple'=>false,
                'class'=>'form-select chzn-select',
                'data-placeholder'=>'Select Instructor(s)'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Roster:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('TrainingClassroomDetail.user_id', array (
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
    });
</script>