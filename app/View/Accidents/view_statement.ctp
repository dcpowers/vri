<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __($info['name'].': '.$info['user']); ?></h2>
</div>

<div class="modal-body">
	<?php
	foreach($data as $v){
		?>
		<dl>
			<dt><?=$v['statement']?></dt>
        	<dd><?=$v['answer']?></dd>
		</dl>
		<?php
	}
	?>
</div>
<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

    echo $this->Html->link(
        '<i class="fa fa-print fa-fw"></i> Print',
        array('controller'=>'accidents', 'action'=>'viewStatement', $id, 'ext'=>'pdf'),
        array('escape'=>false, 'class'=>'btn btn-primary pull-left')
    );
    ?>
</div>