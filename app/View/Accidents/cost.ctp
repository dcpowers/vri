<?php
    echo $this->Form->create('AccidentCost', array(
    'url' => array('controller'=>'Accidents', 'action'=>'cost'),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));

echo $this->Form->hidden('accident_id', array('value'=>$this->request->data['Accident']['id']));
?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Cost Of Accident:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Days on Restrictions:</label>
        <div class="col-sm-8">
            <?php
			for($i=0; $i<=1825; $i++){
				$days[$i] = $i;
			}
            echo $this->Form->input('num_days', array (
                'options'=>$days,
                'class'=>'form-select chzn-select',
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Cost Type:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'accident_cost_lov_id', array(
                'options'=>$costLov,
                'class'=>'form-select chzn-select',
				'empty'=>true
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Vanguard Cost:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'vri_cost', array(
            ));
            ?>
        </div>
    </div>

	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Insurance Cost:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'insurance_cost', array(
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