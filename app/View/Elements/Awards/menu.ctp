<?php
echo $this->Html->link(
	'<i class="fa fa-plus fa-fw"></i> Verify Month Award',
    array('controller'=>'Awards', 'action'=>'verify' ),
    array('escape'=>false, 'class'=>'btn btn-success btn-sm')
);
?>