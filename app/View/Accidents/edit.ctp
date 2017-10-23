<?php
    echo $this->Form->create('Accident', array(
    'url' => array('controller'=>'Accidents', 'action'=>'edit', $this->request->data['Accident']['id']),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));

echo $this->Form->hidden('id', array('value'=>$this->request->data['Accident']['id']));

$areaId = array();
if(!empty($this->request->data['AccidentArea'])){
	foreach($this->request->data['AccidentArea'] as $v){
		$areaId[] = $v['accident_area_lov_id'];
	}
}

?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit Accident Report:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Employee:</label>
        <div class="col-sm-8">
            <?=$this->request->data['User']['first_name']?>
            <?=$this->request->data['User']['last_name']?>

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
            echo $this->Form->input('AccidentArea.accident_area_lov_id', array (
                'options'=>$areas,
				'empty'=>false,
                'class'=>'form-select chzn-select',
				'multiple'=>true,
				'default' => $areaId,
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

	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Reported To Workmans Comp?:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'is_insurance', array(
                'options'=>array(1=>'Yes', 2=>'No'),
				'empty'=>false,
                'class'=>'form-select chzn-select',
				'multiple'=>false,
				'data-placeholder'=>'Reported To Workmans Comp?'
            ));
            ?>
        </div>
    </div>

	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Reported To OSHA?:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input( 'is_osha', array(
                'options'=>array(1=>'Yes', 2=>'No'),
				'empty'=>false,
                'class'=>'form-select chzn-select',
				'multiple'=>false,
				'data-placeholder'=>'Reported To OSHA?'
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