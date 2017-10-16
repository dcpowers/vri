<?php
echo $this->Html->link(
	'<i class="fa fa-plus"></i> Add Job Title',
	array('controller'=>'jobs', 'action'=>'add'),
	array('escape'=>false, 'class'=>'btn btn-primary btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myModal')
);
?>