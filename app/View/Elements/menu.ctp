<?php
    $apps = $this->requestAction('Applications/getLeftMenu/'); 
?>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="min-width: 100%; padding: 0px; margin: 0px; height: 50px;">
        <nav class="navbar navbar-inverse navbar-static-top skin-border">
            <div class="container">
                
                    <ul class="nav navbar-nav">
                        <?php
                        #pr($apps);
                        #exit;
                        foreach($apps as $menu_item){
                            if(!empty($menu_item['children'])){
                                ?>
                                <li class="dropdown">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="'. $menu_item['Application']['iconCls'] .'"></i> <span>'. $menu_item['Application']['name'] . '</span><i class="fa fa-angle-down pull-right"></i>',
                                        '#',
                                        array('escape'=>false, 'class'=>'dropdown-toggle', 'data-toggle'=>'dropdown')
                                    );
                                    ?>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
                                        foreach($menu_item['children'] as $child){
                                            $subClass = ($this->request->params['action'] == $child['Application']['action']) ? 'active' : null ;
                                            ?>
                                            <li class="<?=$subClass?>">
                                                <?php
                                                echo $this->Html->link(
                                                    '<i class="'. $child['Application']['iconCls'] .'"></i> <span>'. $child['Application']['name'] . '</span>',
                                                    array('controller'=>$child['Application']['controller'], 'action'=>$child['Application']['action']),
                                                    array('escape'=>false)
                                                );
                                                ?>
                                            </li>
                                            <?php      
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }else{
                                $class = ($this->request->params['controller'] == $menu_item['Application']['controller']) ? 'active' : null ;
                                ?>
                                <li class="<?=$class?>">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="'. $menu_item['Application']['iconCls'] .'"></i> <span>'. $menu_item['Application']['name'] . '</span>',
                                        array('controller'=>$menu_item['Application']['controller'], 'action'=>$menu_item['Application']['action']),
                                        array('escape'=>false)
                                    );
                                    ?>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                
            
            </div>
            
        </nav>
        
    </div>
    