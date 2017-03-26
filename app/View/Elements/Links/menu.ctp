<?php
	echo $this->Html->link(
    	'<i class="fa fa-plus fa-fw"></i> Create New Link',
        array('plugin'=>false, 'controller'=>'Links', 'action'=>'add'),
        array('escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myModal')
    );
?>