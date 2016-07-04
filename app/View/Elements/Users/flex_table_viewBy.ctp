<div class="flextable-item">
    <ul class="pagination pagination-sm">
                <?php
                foreach($letters as $key=>$letter){
                    if(in_array($letter, $activeLetters) || $key == 0){
                        $class = ($currentLetter == $letter) ? 'active' : null ;
                        
                        ?>
                        <li class="<?=$class?>">
                            <?php
                            echo $this->Html->link(
                                $letter, 
                                array('controller'=>'Users','action'=>'index', $letter, $status, $viewBy),
                                array( 'escape'=>false) 
                            );
                            ?>
                        </li>
                        <?php
                    }else{
                        ?>
                        <li class="disabled ">
                            <span>
                                <span aria-hidden="true"><?=$letter?></span>
                            </span>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        
            <ul class="pagination pagination-sm">
                <?php
                $activeclass = ($status == 1) ? 'active' : null ;
                $inactiveclass = ($status == 2) ? 'active' : null ;
                ?>
                <li class="<?=$activeclass?>">
                    <?php
                    echo $this->Html->link( 
                        'Active', 
                        array('controller'=>'Users', 'action'=>'index', $currentLetter, 1, $viewBy), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
                
                <li class="<?=$inactiveclass?>">
                    <?php
                    echo $this->Html->link( 
                        'Inactive', 
                        array('controller'=>'Users', 'action'=>'index', $currentLetter, 2, $viewBy), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
                    
                <li>
                    <?php
                    echo $this->Html->link( 
                        'Clear Filters',
                        array('controller'=>'Users', 'action'=>'index'), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
            </ul>
        </div>