<?php
    echo $this->Form->create('TrainingUploads', array(
    'url' => array('controller'=>'Trainings', 'action'=>'files', $this->request->data['TrainingDetail']['training_id']),
    'role'=>'form',
	'type'=>'file',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));

echo $this->Form->hidden('training_id', array('value'=>$this->request->data['TrainingDetail']['training_id']));
echo $this->Form->hidden('account_id', array('value'=>$this->request->data['TrainingDetail']['account_id']));

?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Add Files To Training:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
    	<label for="name" class="col-sm-4 control-label">File:</label>
        <div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.0.files', array(
				'type' => 'file',
				'multiple'=>false
			));
			?>
		</div>
	</div>

	<div class="form-group">
    	<label for="name" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.0.description', array(
            	'type'=>'text'
			));
			?>
		</div>
	</div>

	<div class="form-group">
    	<label for="name" class="col-sm-4 control-label">File:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.1.files', array(
				'type' => 'file',
				'multiple'=>false
			));
			?>
		</div>
	</div>

	<div class="form-group">
    	<label for="name" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.1.description', array(
            	'type'=>'text'
			));
			?>
		</div>
	</div>

	<div class="form-group">
    	<label for="name" class="col-sm-4 control-label">File:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.2.files', array(
				'type' => 'file',
				'multiple'=>false
			));
			?>
		</div>
	</div>

	<div class="form-group">
    	<label for="name" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
			<?php
			echo $this->Form->input('TrainingFile.2.description', array(
            	'type'=>'text'
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
       
    });
</script>