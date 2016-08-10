    <?php 
    switch($this->request->params['action']){
        case 'view':
            echo $this->Html->link(
                '<i class="fa fa-pencil fa-fw"></i> Edit: '. $account['Account']['name'].'',
                array('controller'=>'Accounts', 'action'=>'edit', $account['Account']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-primary btn-outline' )
            );
            break;
        
        default:
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add New Account', 
                array('controller'=>'Accounts', 'action'=>'add' ),
                array('escape'=>false, 'class'=>'btn btn-primary btn-sm btn-outline') 
            );
            break;
        
    }
    ?>