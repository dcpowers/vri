<?php
    //pr($this->Session->read('Auth.User.Location.name'));
    #pr($user);
    #exit;
?>
<div class="container">
    <div class="row">
        <div class="users form">
            <h1>My Settings</h1>
            <div class="col-lg-4 col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="<?php //echo $app['iconCls'] ?>"></i>&nbsp;<span>Account Information</span>
                    </div>
                            
                    <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="pull-right"><?php echo date( 'F d, Y', strtotime( $user['User']['created'] ) ); ?></span>
                                    Created On: 
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= $user['User']['is_active']?></span>
                                    Account Status: 
                                </li>
                                
                                <li class="list-group-item">
                                    <span class="pull-right"><?= $user['User']['first_name']?> <?= $user['User']['last_name'] ?></span>
                                    Name: 
                                </li>
                                
                                <li class="list-group-item">
                                    <span class="pull-right"><?= $user['User']['username']?></span>
                                    E-Mail: 
                                </li>
                                
                                <?php
                                    if(!empty($user['Company']['name'])){
                                        ?>
                                        <li class="list-group-item">
                                            <span class="pull-right"><?= $user['Company']['name']?></span>
                                            Company: 
                                        </li>
                                        <li class="list-group-item">
                                            <span class="pull-right"><?= $user['Location']['name']?></span>
                                            Location: 
                                        </li>
                                        <li class="list-group-item">
                                            <span class="pull-right"><?= $user['Department']['name']?></span>
                                            Department: 
                                        </li>
                                        <li class="list-group-item">
                                            <span class="pull-right"><?= $user['JobTitle']['job_title']?></span>
                                            Job Title: 
                                        </li>
                                        <?php
                                    }
                                ?>
                                
                            </ul>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="<?php //echo $app['iconCls'] ?>"></i>&nbsp;<span>My Information</span>
                    </div>
                            
                    <div class="panel-body">
                        <div class="center" style="height:300px; overflow-y: auto;" >
                            <?php #echo $this->element( $app['name'] . '._portal_dashboard' ); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-4">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <i class="<?php //echo $app['iconCls'] ?>"></i>&nbsp;<span>Company Information</span>
                    </div>
                            
                    <div class="panel-body">
                        <div class="center" style="height:300px; overflow-y: auto;" >
                            <?php #echo $this->element( $app['name'] . '._portal_dashboard' ); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="<?php //echo $app['iconCls'] ?>"></i>&nbsp;<span>My Documents</span>
                    </div>
                            
                    <div class="panel-body">
                        <div class="center" style="height:300px; overflow-y: auto;" >
                            <?php #echo $this->element( $app['name'] . '._portal_dashboard' ); ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

            