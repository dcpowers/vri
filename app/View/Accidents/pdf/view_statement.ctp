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
    
</div>