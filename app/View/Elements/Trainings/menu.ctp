    <?php
    switch($this->request->params['action']){
        case 'view':
            echo $this->Html->link(
                '<i class="fa fa-pencil fa-fw"></i> Edit: '. $asset['Asset']['asset'],
                array('controller'=>'Assets', 'action'=>'edit', $asset['Asset']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-primary btn-sm' )
            );
            ?>
            <?php
            echo $this->Html->link(
                '<i class="fa fa-trash fa-fw"></i> Delete: '. $asset['Asset']['asset'],
                array('controller'=>'Assets', 'action'=>'delete', $asset['Asset']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-danger btn-sm' ),
                'Are You Sure You Want To Delete This Asset?'
            );
            break;

        case 'library':
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Upload New Training',
                array('controller'=>'Trainings', 'action'=>'add'),
                array( 'escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myModal' )
            );
            break;

        default:
            if(AuthComponent::user('Role.permission_level') >= 30){
                echo $this->Html->link(
                    '<i class="fa fa-list-alt fa-fw"></i> Training Library',
                    array('controller'=>'Trainings', 'action'=>'library'),
                    array( 'escape'=>false, 'class'=>'btn btn-primary btn-sm btn-outline' )
                );
            }
            break;

    }
    ?>