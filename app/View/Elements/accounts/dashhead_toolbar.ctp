    <div class="dashhead-toolbar-item">
        <?php    
        echo $this->Html->link(
            '<i class="fa fa-pencil fa-fw"></i> Edit Account', 
            array('plugin'=>false, 'controller'=>'Accounts', 'action'=>'edit', $account['Account']['id'] ),
            array('escape'=>false, 'class'=>'btn btn-primary' ) 
        );
        ?>
    </div>