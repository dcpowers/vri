<?php
echo $this->Html->link(
	'<i class="fa fa-plus fa-fw"></i> Add Accident',
    array('controller'=>'Accidents', 'action'=>'add' ),
    array('escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
);
?>