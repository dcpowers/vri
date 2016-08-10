    <div class="btn-group pull-right">
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
            <li class="<?=$accountClass?>">
                <?php 
                echo $this->Html->link(
                    'Account',
                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'account'),
                    array('escape'=>false)
                );
                ?>
            </li>
            
            <li class="<?=$deptClass?>">
                <?php 
                echo $this->Html->link(
                    'Department',
                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'department'),
                    array('escape'=>false)
                );
                ?>
            </li>
            
            <li class="<?=$roleClass?>">
                <?php 
                echo $this->Html->link(
                    'User Role',
                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'role'),
                    array('escape'=>false)
                );
                ?>
            </li>
            <li role="separator" class="divider"></li>
            <li>
                <?php 
                echo $this->Html->link(
                    'Clear View By Filter',
                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status),
                    array('escape'=>false)
                );
                ?>
            </li>
        </ul>
    </div>
    <?php
    echo $this->Form->button(
        '<i class="fa fa-search fa-fw"></i> Search Filter <i class="fa fa-fw fa-caret-down"></i>', 
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