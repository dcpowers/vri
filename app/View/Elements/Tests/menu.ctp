<?php
    echo $this->Html->link(
        '<i class="fa fa-plus"></i> Add A New Test',
        array('controller'=>'tests', 'action'=>'add', 'admin'=>true),
        array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs') );
    ?>