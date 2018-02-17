<header class="main-header" style="background-color: #EBEBEB">
    <nav class="navbar navbar-static-top">
        <!--<div class="container">-->
            <div class="navbar-header">
                <!-- Logo -->
                <?php
                echo $this->Html->link(
                    '<span class="logo-lg"><b>One</b>System</span>',
                    array('controller'=>'Dashboard', 'action'=>'index'),
                    array('escape'=>false, 'class'=>'navbar-brand', 'style'=>'margin-left: 30px;')
                );
                ?>

                <?php
                /*
                echo $this->Form->button('<i class="fa fa-bars">hh</i>', array(
                    'type' => 'button',
                    'class'=>'navbar-toggle collapsed',
                    'data-toggle'=>'collapse',
                    'data-target'=>'#navbar-collapse'
                ));
                */
                ?>
            </div>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav"  style="margin-right: 30px;">
                    <!-- Control Sidebar Toggle Button -->
                    <?php
                    if(AuthComponent::user('Role.permission_level') >= 70){
                        ?>
                        <li>
                            <!--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
                            <?php
                            echo $this->Html->link(
                                '<i class="fa fa-cogs"></i>',
                                array('controller'=>'settings', 'action'=>''),
                                array('escape'=>false )
                            );
                            ?>
                        </li>
                        <?php
                    }
                    ?>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <?php
                        $image = (file_exists('img/profiles/'.$current_user['id'].'.png')) ? '/img/profiles/'.$current_user['id'].'.png' : '<i class="glyphicon glyphicon-user"></i>' ;

                        echo $this->Html->link(
                            '<i class="glyphicon glyphicon-user"></i><span class="hidden-xs">'.$current_user['first_name'].' '. $current_user['last_name']. ' </span><span class="fa fa-chevron-down"></span>',
                            '#',
                            array('escape'=>false, 'class'=>'dropdown-toggle', 'data-toggle'=>'dropdown')
                        );
                        ?>

                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <?php
                                $image = (file_exists('img/profiles/'.$current_user['id'].'.png')) ? '/img/profiles/'.$current_user['id'].'.png' : '/img/profiles/noImage.png' ;
                                echo $this->Html->image($image, array('class'=>'img-circle', 'alt'=>$current_user['last_name']));
                                ?>
                                <p>
                                    <?=$current_user['first_name']?> <?=$current_user['last_name']?>
                                    <small>Member since: <?php echo date('F, Y', strtotime($current_user['doh']));?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!--<li class="user-body">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </li>-->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?php
                                    echo $this->Html->link(
                                        'Profile',
                                        array('controller'=>'Users', 'action'=>'profile'),
                                        array('escape'=>false, 'class'=>'btn btn-default btn-flat')
                                    );
                                    ?>
                                </div>

                                <div class="pull-right">
                                    <?php
                                    echo $this->Html->link(
                                        'Sign Out',
                                        array('controller'=>'Users', 'action'=>'logout'),
                                        array('escape'=>false, 'class'=>'btn btn-default btn-flat')
                                    );
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        <!--</div>-->
    </nav>
</header>