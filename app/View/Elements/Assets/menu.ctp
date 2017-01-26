    <?php
    switch($this->request->params['action']){
        case 'view':
            echo $this->Html->link(
                '<i class="fa fa-trash fa-fw"></i> Delete: '. $asset['Asset']['asset'],
                array('controller'=>'Assets', 'action'=>'delete', $asset['Asset']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-danger btn-sm' ),
                'Are You Sure You Want To Delete This Asset?'
            );
            break;

        default:
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add Asset',
                array('plugin'=>false, 'controller'=>'Assets', 'action'=>'add'),
                array('escape'=>false, 'class'=>'btn btn-success btn-sm', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
            );
            break;

    }
    ?>