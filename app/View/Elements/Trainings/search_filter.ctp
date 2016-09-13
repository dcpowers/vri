    <div class="btn-group pull-right" style="margin-right: 5px;">
        <?php
        $trncat[null] = 'View All';
        echo $this->Form->button(
            '<i class="fa fa-eye fa-fw"></i> View: '. $trncat[$cat] .' <i class="fa fa-fw fa-caret-down"></i>', 
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
            <?php
            foreach($trncat as $key=>$item){
                $class = ($key == $cat) ? 'active' : null ;
                ?>
                <li class="<?=$class?>">
                    <?php 
                    echo $this->Html->link(
                        $item,
                        array('controller'=>'Trainings', 'action'=>'library', $key),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>