    <div class="dashhead-toolbar-item item col-md-12">
        <?php
        echo $this->Form->create('Awards', array(
            'url' => array('plugin'=>false, 'controller' => 'Awards', 'action'=>'index'),
            'role'=>'form',
            #'class'=>'form-inline',
            'inputDefaults' => array(
                'label'=>false,
                'div'=>false,
                'class'=>'form-control',
                'error'=>false
            )
        ));

        ?>
		<ul class="list-inline" style="width: 100%;">
			<li class="col-md-6">
		        <div class="form-group">
        			<label class="sr-only">Month:</label>
					<?php
					echo $this->Form->input( 'month', array(
						'options'=>$months,
						'class'=>'chzn-select form-control',
						'value'=>$month,

					));
					?>
		        </div>
            </li>
			<li>
				<div class="form-group">
        			<label class="sr-only">Year:</label>
					<?php
					echo $this->Form->input( 'year', array(
						'options'=>$years,
						'class'=>'chzn-select form-control',
						'value'=>$year
					));
					?>
		        </div>
			</li>
			<li>
			    <?php
                echo $this->Form->button( '<i class="fa fa-search"></i>', array(
                    'type'=>'submit',
                    'class'=>'btn btn-info btn-sm'
                ));
                ?>

			</li>
		</ul>
        <?php echo $this->Form->end(); ?>
    </div>