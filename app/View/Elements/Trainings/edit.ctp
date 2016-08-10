<?php
            echo $this->Html->link(
                '<i class="fa fa-pencil fa-fw"></i> Edit',
                array('controller'=>'Trainings', 'action'=>'edit', $training['Training']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-primary btn-outline' )
            );
            ?>