<?php
echo $this->Html->link(
	'<i class="fa fa-plus"></i> Add Job Posting',
	array('controller'=>'jobpostings', 'action'=>'add'),
	array('escape'=>false, 'class'=>'btn btn-primary btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
);
?>