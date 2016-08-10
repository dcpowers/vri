    <nav>
        <p>Search By First Letter Of First Name</p>
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
                            array( 
                                'controller'=>'Users',
                                'action'=>'index', 
                                $letter,
                                $status,
                                $viewBy
                            ), 
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
            <li>
            <?php
                echo $this->Html->link( 
                    'Clear Search By Letter',
                    array('controller'=>'Users', 'action'=>'index', 'All', $status, $viewBy), 
                    array( 'escape'=>false) 
                );
                ?>
            </li>
        </ul>
    </nav>