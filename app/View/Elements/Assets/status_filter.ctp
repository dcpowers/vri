    <?php
    $activeclass = ($status == 1) ? 'active' : null ;
    $inactiveclass = ($status == 2) ? 'active' : null ;
    ?>
    
    <ul class="pagination pagination-sm text-center">
        <li class="<?=$activeclass?>">
            <?php
            echo $this->Html->link( 
                'Active', 
                array('controller'=>'Assets', 'action'=>'index', 1, $viewBy), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
                    
        <li class="<?=$inactiveclass?>">
            <?php
            echo $this->Html->link( 
                'Inactive', 
                array('controller'=>'Assets', 'action'=>'index', 2, $viewBy), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
                        
        <li>
            <?php
            echo $this->Html->link( 
                'Clear Status Filter',
                array('controller'=>'Assets', 'action'=>'index', 'All', $viewBy), 
                array( 'escape'=>false) 
            );
            ?>
        </li>
    </ul>