<p>An Accident has been reported by: <?=$reported?></p>
<p>Account: <?=$account?></p>
<p>Employee: <?=$name?></p>
<p>
	<?php
	echo $this->Html->link(
		'View Accident Report',
		array('controller'=>'Accidents', 'action'=>'view', $id, 'full_base' => true,),
		array('escape'=>false)
	);
	?>
</p>