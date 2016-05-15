<?php
    $apps = $this->requestAction('Applications/getLeftMenu/'); 
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                $image = file_exists('/img/profiles/'.$current_user['id'].'.png') ? '/img/profiles/'.$current_user['last_name'].'.png' : '/img/profiles/noImage.png' ;
                echo $this->Html->image($image, array('class'=>'img-circle', 'alt'=>$current_user['last_name']));
                ?>
            </div>
            <div class="pull-left info">
                <p><?=$current_user['first_name']?> <?=$current_user['last_name']?></p>
            </div>
        </div>
        
        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>-->
        <!-- /.search form -->
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php
            foreach($apps as $menu_item){
                if(!empty($menu_item['children'])){
                    ?>
                    <li class="treeview">
                        <?php
                        echo $this->Html->link(
                            '<i class="'. $menu_item['Application']['iconCls'] .'"></i> <span>'. $menu_item['Application']['name'] . '</span><i class="fa fa-angle-left pull-right"></i>',
                            '#',
                            array('escape'=>false)
                        );
                        ?>
                        <ul class="treeview-menu">
                            <?php
                            foreach($menu_item['children'] as $child){
                                $subClass = ($this->request->params['controller'] == $child['Application']['controller']) ? 'active' : null ;
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
    </section>
    <!-- /.sidebar -->
</aside>