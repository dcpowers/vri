    <div class="dashhead-toolbar-item">
        <?php    
        echo $this->Html->link(
            '<i class="fa fa-plus fa-fw"></i> Add Asset', 
            array('plugin'=>false, 'controller'=>'Accounts', 'action'=>'add'),
            array('escape'=>false, 'class'=>'btn btn-primary', 'data-toggle'=>'modal','data-target'=>'#myLgModal'  ) 
        );
        ?>
    </div>