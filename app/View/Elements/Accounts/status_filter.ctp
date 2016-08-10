    <?php
    $activeclass = ($status == 1) ? 'active' : null ;
    $inactiveclass = ($status == 2) ? 'active' : null ;
    ?>
    
    <ul class="pagination pagination-sm text-center">
        <li class="<?=$activeclass?>">
            <?php
            echo $this->Html->link( 
                'Active', 
                array('controller'=>'Accounts', 'action'=>'index', 1), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
                    
        <li class="<?=$inactiveclass?>">
            <?php
            echo $this->Html->link( 
                'Inactive', 
                array('controller'=>'Accounts', 'action'=>'index', 2), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
                        
        <li>
            <?php
            echo $this->Html->link( 
                'Clear Status Filter',
                array('controller'=>'Accounts', 'action'=>'index', 'All'), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
    </ul>