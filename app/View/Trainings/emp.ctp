<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'addEmpStatement'),
        'role'=>'form',
		'type'=>'file',
        #'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

	if(AuthComponent::user('Role.permission_level') >= 60 ){
		$divWidthOne = 8;
	}else{
		$divWidthOne = 12;
	}
?>
<style type="text/css">
	.chosen-container .chosen-choices .search-field:only-child input {
    width: 100% !important;
}
</style>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Employee Statement:'); ?></h2>
</div>

<div class="modal-body">
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Which accident are you reporting on:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Exact location/area where injured*:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">What were you doing just before the incident occured? Describe the activity, as well as the tools, equipment, or material you were using. Be specific. Examples: "climbing a ladder while carrying roofing materials"; "spraying chlorine from hand sprayer"; "daily computer key-entry."*</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">What happened? Tell us how the injury occurred. Examples: "When the ladder slipped on wet floor, I fell 20 feet"; "I was sparyed with chlorine when gasket broke during replacement"; "I developed soreness in my wrist over time." *</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">What was the injury or illness? Tell us the part of the body that was affected and how it was affected; be more specific than "hurt," "pain," or "sore." Examples: "strained back"; "chemical burn, hand"; "carpal tunnel syndrome." *</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Have you had a same or similar injury before:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">if yes, give details:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">What object or substance directly harmed the employee? Examples: "concrete floor"; "chlorine"; "radial arm saw." *:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Was a Safety Device Applicable* :</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Was it used?:</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Was injury caused by failure to use or observe safety rules or regulations: *</label>
        <div class="col-sm-8">
		</div>
	</div>
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">If yes, which rule:
		</label>
        <div class="col-sm-8">
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
            allow_single_deselect: true,

        });
    });
</script>