<?php
    echo $this->Form->create('Accidents', array(
        'url' => array('controller'=>'AccidentStatements', 'action'=>'statements'),
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

	echo $this->Form->hidden('AccidentFile.id', array('value'=>$this->request->data['AccidentFile']['id']));

?>
<style type="text/css">
	.chosen-container .chosen-choices .search-field:only-child input {
    width: 100% !important;
}
</style>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __($accidents[0]['AccidentStatement']['name'].':'); ?></h2>
</div>

<div class="modal-body">
	<?php
	foreach($accidents[0]['children'] as $v){
		$option = null;
		$row = null;
		$class = 'form-control';

		$type = $v['AccidentStatement']['type'];
		switch($type){
			case 'select':
				$option = $options;
				$class = 'form-select chzn-select';
				break;

			case 'textarea':
				$row = 3;
				break;
		}
		?>
		<div class="form-group">
	        <label class="control-label" for="<?=$v['AccidentStatement']['id']?>"><?=$v['AccidentStatement']['name']?></label>
	        <?php
	        echo $this->Form->input( $v['AccidentStatement']['id'], array(
	        	'type'=>$type,
				'rows'=>$row,
				'options'=>$option,
				'class'=>$class,
				'empty'=>true
	        ));
	        ?>
		</div>
		<?php
		#pr($v);
		#exit;
	}
	?>
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
			disable_search: true

        });
    });
</script>