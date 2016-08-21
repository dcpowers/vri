    <?php
    if(AuthComponent::user('Role.permission_level') == 70){
        ?>
        <div class="btn-group pull-right">
            <?php 
            echo $this->Form->button(
                '<i class="fa fa-cogs fa-fw"></i>', 
                array(
                    'type'=>'button', 
                    'escape'=>false, 
                    'class'=>'btn btn-warning btn-sm btn-outline dropdown-toggle',
                    'data-toggle'=>'dropdown', 
                    'aria-haspopup'=>'true', 
                    'aria-expanded'=>'false'          
                ) 
            );
            ?>
            
            <ul class="dropdown-menu">
                <li>
                    <?php 
                    echo $this->Html->link(
                        'Manufacturer',
                        array('controller'=>'Assets', 'action'=>'manage', 'manufacturer'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li>
                    <?php 
                    echo $this->Html->link(
                        'Vendors',
                        array('controller'=>'Assets', 'action'=>'manage', 'vendor'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
            </ul>
        </div>
        <?php
    }
    ?>