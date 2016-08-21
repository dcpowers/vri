    <div class="btn-group pull-right" style="margin-right: 5px;">
        <?php 
        echo $this->Form->button(
            '<i class="fa fa-eye fa-fw"></i> View By: <i class="fa fa-fw fa-caret-down"></i>', 
            array(
                'type'=>'button', 
                'escape'=>false, 
                'class'=>'btn btn-primary btn-sm btn-outline dropdown-toggle',
                'data-toggle'=>'dropdown', 
                'aria-haspopup'=>'true', 
                'aria-expanded'=>'false'          
            ) 
        );
        ?>
        
        <ul class="dropdown-menu">
            <li class="<?=$typeClass?>">
                <?php 
                echo $this->Html->link(
                    'Asset Type',
                    array('controller'=>'Assets', 'action'=>'index', $status, 'type'),
                    array('escape'=>false)
                );
                ?>
            </li>
            
            <li class="<?=$manuClass?>">
                <?php 
                echo $this->Html->link(
                    'Manufacturer',
                    array('controller'=>'Assets', 'action'=>'index', $status, 'manufacturer'),
                    array('escape'=>false)
                );
                ?>
            </li>
            
            <li class="<?=$accountClass?>">
                <?php 
                echo $this->Html->link(
                    'Account',
                    array('controller'=>'Assets', 'action'=>'index', $status, 'account'),
                    array('escape'=>false)
                );
                ?>
            </li>
            <li class="<?=$assignedToClass?>">
                <?php 
                echo $this->Html->link(
                    'Assigned To',
                    array('controller'=>'Assets', 'action'=>'index', $status, 'assignedTo'),
                    array('escape'=>false)
                );
                ?>
            </li>
            <li role="separator" class="divider"></li>
            <li>
                <?php 
                echo $this->Html->link(
                    'Clear View By Filter',
                    array('controller'=>'Assets', 'action'=>'index', $status),
                    array('escape'=>false)
                );
                ?>
            </li>
        </ul>
    </div>
    <?php
    echo $this->Form->button(
        '<i class="fa fa-search fa-fw"></i> Search <i class="fa fa-fw fa-caret-down"></i>', 
        array(
            'type'=>'button', 
            'escape'=>false, 
            'class'=>'btn btn-primary btn-sm btn-outline pull-right',
            'data-toggle'=>'collapse', 
            'data-target'=>'#collapseExample', 
            'aria-expanded'=>$var, 
            'aria-controls'=>'collapseExample',
            'style'=>'margin-right: 5px;'
        ) 
    );
    ?>