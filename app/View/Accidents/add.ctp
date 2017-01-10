<?php
    echo $this->Form->create('Accident', array(
    'url' => array('controller'=>'Accidents', 'action'=>'add'),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Add New Accident Report:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Employee:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('user_id', array (
                'options'=>$userList,
                'class'=>'form-select chzn-select',
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Date Of Accident:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'date', array(
                'type'=>'text',
                'value'=>date('m/d/Y', strtotime('now')),
                'class'=>'datepicker form-control'
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Current Wage:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'hourly_rate', array(
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Current Position:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'EPosition', array(
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Area(s) Of Injury:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('AccidentArea.accident_area_lov_id.', array (
                'options'=>$areas,
				'empty'=>false,
                'class'=>'form-select chzn-select',
				'multiple'=>true,
				'data-placeholder'=>'Select Area(s) of Injury On Body'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Brief Description of Accident:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'description', array(
                'type'=>'textarea'
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

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