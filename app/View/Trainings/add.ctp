<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'add'),
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
?>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Add New Training'); ?></h2>
</div>

<div class="modal-body">
	<div class="form-group">
            	<label for="name" class="control-label">Name:</label>
                <?php
				echo $this->Form->input('name', array (
					'type'=>'text'
				));
				?>
    </div>

	<div class="form-group">
    	<label for="name" class="control-label">Description:</label>
        <?php
		echo $this->Form->input('description', array (
			'type'=>'textarea'
		));
		?>
    </div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="name" class="control-label">Status:</label><br />
				<label class="radio-inline">
					<?php
					echo $this->Form->radio('is_active',
						array (1=>'Active', 0=>'Inactive'),
						array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
					);
					?>
				</label>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="name" class="control-label">Make Avaiable To Others:</label><br />
				<label class="radio-inline">
					<?php
					echo $this->Form->radio('is_public',
						array (1=>'Make Public', 0=>'Make Private'),
						array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
					);
					?>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
    	<label for="name" class="control-label">Files:</label>
        <?php
		echo $this->Form->input('files.', array(
			'type' => 'file',
			'multiple'=>true
		));
		?>
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

        $(".btn-edit").click(function(){
            $('.accountInputs').prop('disabled', false);
            $('.accountInputs').prop('disabled', false).trigger("chosen:updated");

            $(".btn-edit-set").hide();
            $(".btn-cancel-set").show();

        });

        $(".btn-cancel").click(function(){
            $('.accountInputs').prop('disabled', true);
            $('.accountInputs').prop('disabled', true).trigger("chosen:updated");

            $(".btn-edit-set").show();
            $(".btn-cancel-set").hide();
        });

        $('#UserFirstName').editable({
            type: 'text',
            name: 'name'
        });
    });
</script>