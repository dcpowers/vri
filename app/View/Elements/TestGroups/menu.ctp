<ul class="list-inline pull-right">
	        	<li>
		            <?php
		            echo $this->Html->link(
		                '<i class="fa fa-arrow-left fa-fw fa-lg text-success"></i><span class="text text-success">Back To Testing</span>',
		                array('controller'=>'Tests', 'action'=>'index'),
		                array('escape'=>false, 'class'=>'btn btn-default btn-xs pull-right')
		            );
		            ?>
		        </li>
		        <li>
		            <?php
		            echo $this->Html->link(
		                '<i class="fa fa-plus fa-lg fa-fw text-success"></i><span class="text text-success">Add A New Test</span>',
		                array('controller'=>'TestGroups', 'action'=>'add'),
		                array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-default btn-xs pull-right') );
		            ?>
		        </li>
	    	</ul>