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
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dropdown example</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Example 1</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> Example 2</a></li>
                </ul>
            </li>
            
            <li>
                <a href="pages/widgets.html">
                    <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>