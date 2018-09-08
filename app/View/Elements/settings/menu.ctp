    <?php
    echo $this->Html->link(
        '<i class="fa fa-plus fa-fw"></i> Add',
        array('controller'=>'Settings', 'action'=>'add', $this->request->params['pass'][0]),
        array( 'escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal' )
    );
    ?>