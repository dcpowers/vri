<?php
echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobs', 'action'=>'add'),
    'role'=>'form',
    //'class'=>'form-horizontal',
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
    <h2><?php echo __('Add Job Title');?></h2>
</div>

<div class="modal-body">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="name">Title:</label>
                <?php
                echo $this->Form->input('name', array (
                    'type'=>'text',
                    'placeholder' => 'Job Name'
                ));
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="soc">SOC Code:</label>
                <?php
                echo $this->Form->input('soc_code', array (
                    'type'=>'text',
                    'placeholder' => 'SOC Code'
                ));?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label" for="description">Description:</label>
        <?php
        echo $this->Form->input('description', array (
            'type'=>'textarea',
            'id'=>'description',

        ));
        ?>
    </div>

	<div class="form-group">
        <label class="control-label" for="description">Account:</label>
        <?php
        echo $this->Form->input('account_id', array (
            'type'=>'select',
			'options'=>$accounts,
			'empty'=>false,
            'class'=>'form-select chzn-select',
			'multiple'=>false,
			'data-placeholder'=>'Select An Account'
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
	});
</script>