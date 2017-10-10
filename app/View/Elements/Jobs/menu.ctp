<?php
        echo $this->Html->link(
            '<i class="fa fa-plus"></i> Add Job Opening',
            array('controller'=>'JobPostings', 'member'=>true, 'action'=>'add'),
            array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
        );
        ?>