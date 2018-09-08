<?php
    #pr($trnCat);
    #exit;
    
?>
<?php
    echo $this->Form->create('Training', array(
    'url' => array('controller'=>'Trainings', 'action'=>'addToAccount',),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
?>
<div class="training index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle"><?=$title?></h6>
            <h3 class="dashhead-title"><i class="fa fa-cogs fa-fw"></i> Settings</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Trainings/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'settings/menu' );?>
        </div>
    </div>
    
    <table class="table table-striped table-condensed table-hover" id="trainingTable">
        <thead>
            <tr>
                <th class="col-sm-3">Name</th>
                <th class="col-sm-3">ABR</th>
                <th class="col-sm-3 text-center">Status</th>
                <th class="col-sm-3 text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php
            #pr($trainings);
            #exit;
            $c=0;
            foreach($records as $key=>$v){
				$status = ($v[$index]['is_active'] == 1 ) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                ?>
                <tr>
                    <td><?=$v[$index]['name']?></td>
                    <td><?=$v[$index]['abr']?></td>
                    <td class="text-center"><?=$status?></td>
                    <td class="text-center">
                    	<ul class="list-inline">
                    		<li>
                    			<?php
			                    echo $this->Html->link(
			                    	'<i class="fa fa-pencil fa-fw"></i>',
			                        array('controller'=>'Settings', 'action'=>'edit', $saveType, $v[$index]['id']),
			                        array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
			                    );
			                	?>
                    		</li>
                    		<li>
                    			<?php
			                    echo $this->Html->link(
			                    	'<i class="fa fa-trash fa-fw"></i>',
			                        array('controller'=>'Settings', 'action'=>'delete', $saveType,$v[$index]['id']),
			                        array('escape'=>false),
			                        'Are You Sure You Want To Delete This Item?'
			                    );
			                	?>
                    		</li>
                    	</ul>	
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>


<?php echo $this->Form->end();?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>