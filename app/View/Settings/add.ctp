<?php
    echo $this->Form->create($index, array(
        'url' => array('controller'=>'Settings', 'action'=>'add', $saveType),
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
?>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2>Add: <?=$title?></h2>
</div>

<div class="modal-body">
	<div class="form-group">
    	<label class="col-sm-4 control-label" for="name">Name:</label>
        <div class="col-sm-8">
        	<?php
            echo $this->Form->input('name', array (
                'type'=>'text',
            ));
            ?>
        </div>
    </div>	
    
    <div class="form-group">
    	<label class="col-sm-4 control-label" for="name">ABR:</label>
        <div class="col-sm-8">
        	<?php
            echo $this->Form->input('abr', array (
                'type'=>'text',
            ));
            ?>
        </div>
    </div>	
    
    <div class="form-group">
    	<label class="col-sm-4 control-label" for="name">Status:</label>
        <div class="col-sm-8">
        	<?php
            echo $this->Form->input('is_active', array (
            	'options'=>array(1=>'Active', 0=>'Inactive'),
                'type'=>'select',
                'class'=>'form-select chzn-select',
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
    });
</script>