<?php
echo $this->Html->link(
	'<i class="fa fa-plus fa-fw"></i> Print',
    array('controller'=>'Awards', 'action'=>'index', 'ext'=>'pdf', $year.'-'.$month.'-'.$acct_id  ),
    array('escape'=>false, 'class'=>'btn btn-success btn-sm')
);
?>