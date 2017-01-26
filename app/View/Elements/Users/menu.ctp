    <?php
	$this->Html->css('bootstrap-fileupload.min.css', '', array('block' => 'csslib') );
    $this->Html->script('bootstrap-fileupload.js', array('block' => 'scriptsBottom'));
    switch($this->request->params['action']){
        case 'view':

            echo $this->Html->link(
                '<i class="fa fa-pencil fa-fw"></i> Edit: '. $user['User']['first_name'].' '.$user['User']['last_name'] .'',
                array('controller'=>'Users', 'action'=>'edit', $user['User']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-primary btn-sm' )
            );
            break;

        default:
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add New Employee',
                array('controller'=>'Users', 'action'=>'add' ),
                array('escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
            );
            break;

    }
    ?>