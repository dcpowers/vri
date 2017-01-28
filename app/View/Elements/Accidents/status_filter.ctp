    <?php
    $activeclass = ($status == 1) ? 'active' : null ;
    $inactiveclass = ($status == 2) ? 'active' : null ;
    ?>
    <div class="btn-group pull-right" style="margin-right: 5px;">
		<?php
        echo $this->Form->button(
            '<i class="fa fa-filter fa-fw"></i> Filter By Status: <i class="fa fa-fw fa-caret-down"></i>',
            array(
                'type'=>'button',
                'escape'=>false,
                'class'=>'btn btn-primary btn-sm btn-outline dropdown-toggle',
                'data-toggle'=>'dropdown',
                'aria-haspopup'=>'true',
                'aria-expanded'=>'false'
            )
        );
        ?>
    	<ul class="dropdown-menu">
	        <li class="<?=$activeclass?>">
	            <?php
	            echo $this->Html->link(
	                'Active',
	                array('controller'=>'Accidents', 'action'=>'index', 1),
	                array( 'escape'=>false)
	            );
	            ?>
	        </li>

	        <li class="<?=$inactiveclass?>">
	            <?php
	            echo $this->Html->link(
	                'Inactive',
	                array('controller'=>'Accidents', 'action'=>'index', 2),
	                array( 'escape'=>false)
	            );
	            ?>
	        </li>

	        <li>
	            <?php
	            echo $this->Html->link(
	                'Clear Status Filter',
	                array('controller'=>'Accidents', 'action'=>'index', 'All'),
	                array( 'escape'=>false)
	            );
	            ?>
	        </li>
	    </ul>
	</div>