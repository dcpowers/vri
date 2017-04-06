<?php
    $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
    $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
    $editable_group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
    
?>
<style type="text/css">
    .sub-content {
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        border-radius: 0px 0px 5px 5px;
        padding: 10px;
    }

    .nav-tabs {
        margin-bottom: 0;
    }

    .vertical-align {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row;
    }
</style>
<div class="container">
    <h2 class="title"><i class="fa fa-clipboard"></i> Testing</h2>
    <hr class="solidOrange" />
    
    <div class="pull-right">
        <?php
        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            ?>
            <div class="row">
                <ul class="list-inline">
                    <li class="pull-right">
                        <?php
                        echo $this->Html->link(
                            '<i class="fa fa-info-circle fa-lg text-success"></i><span class="text text-success">Custom Testing</span>',
                            array('controller'=>'TestGroups', 'action'=>'index', 'member'=>true,),
                            array('escape'=>false, 'class'=>'btn btn-default btn-xs pull-right')
                        );
                        ?>
                    </li>
                    <li class="pull-right"><b>Available Credits: </b> <?php echo $credits; ?></li>
                </ul>
            </div>
            <?php
        }
        ?>
        
    </div>
    
    <!-- Nav tabs -->
    <ul class="nav nav-pills" role="tablist">
        <li role="presentation" class="active" ><a href="#assessment" aria-controls="assessment" role="tab" data-toggle="tab">Assessments</a></li>
        <li role="presentation"><a href="#survey" aria-controls="survey" role="tab" data-toggle="tab">Surveys</a></li>
        <li role="presentation"><a href="#evaluation" aria-controls="evaluation" role="tab" data-toggle="tab">Evaluations</a></li>
    </ul>
    
    <!-- Tab panes -->
    <div class="tab-content" style="margin-top: 30px;">
        <div role="tabpanel" class="tab-pane active fade in" id="assessment">
            <?php
            foreach($assessments as $test){
                ?>
                <div class="row">
                    <div class="col-md-2 ">
                        <?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;
                                    
                        echo $this->Html->link( 
                            $this->Html->image($image, array('class'=>'img-responsive img-thumbnail')),
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_single', $test['Test']['id']),
                            array('escape' => false) 
                        );
                        ?>
                        
                        <?php 
                        if($test['Test']['group_id'] == $editable_group_id[0]){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'member'=>true, 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm') 
                                ); 
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                            
                    <div class="col-md-10">
                        <?php 
                        echo $this->Html->link( 
                            '<h2 class="no-margin">'. $test['Test']['name'].'</h2>',
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_single', $test['Test']['id']),
                            array('escape' => false) ); 
                        ?>
                        
                        <div class="pull-right">
                            <p>
                                <strong>Credits: </strong>
                                <small><?php echo $test['Test']['credits']; ?></small>
                            </p>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php 
                                echo $this->Html->link( 
                                    '<i class="fa fa-eye"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'member'=>true, 'action'=>'view_single', $test['Test']['id']),
                                    array('escape' => false) ); 
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-calendar"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'member'=>true, 'action'=>$test['Test']['schedule_type'], $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal') 
                                );
                                ?>    
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="survey">
            <?php
            foreach($surveys as $test){
                ?>
                <div class="row">
                    <div class="col-md-2">
                        <?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;
                                    
                        echo $this->Html->link( 
                            $this->Html->image($image, array('class'=>'img-responsive img-thumbnail')),
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) 
                        );
                        
                        ?>
                        
                        <?php 
                        if($test['Test']['group_id'] == $editable_group_id[0]){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'member'=>true, 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm') 
                                ); 
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                            
                    <div class="col-md-10">
                        <?php 
                        echo $this->Html->link( 
                            '<h2 class="no-margin">'. $test['Test']['name'].'</h2>',
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) ); 
                        ?>
                        <div class="pull-right">
                            <p>
                                <strong>Credits: </strong>
                                <small><?php echo $test['Test']['credits']; ?></small>
                            </p>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php 
                                echo $this->Html->link( 
                                    '<i class="fa fa-eye"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                                    array('escape' => false) ); 
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-calendar"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'member'=>true, 'action'=>'Type', $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal') 
                                );
                                ?>    
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="evaluation">
            <?php
            foreach($evaluations as $test){
                ?>
                <div class="row">
                    <div class="col-md-2">
                        <?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;
                                    
                        echo $this->Html->link( 
                            $this->Html->image($image, array('class'=>'img-responsive img-thumbnail')),
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) 
                        );
                        
                        ?>
                        
                        <?php 
                        if($test['Test']['group_id'] == $editable_group_id[0]){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'member'=>true, 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm') 
                                ); 
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                            
                    <div class="col-md-10">
                        <?php 
                        echo $this->Html->link( 
                            '<h2 class="no-margin">'. $test['Test']['name'].'</h2>',
                            array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) ); 
                        ?>
                        <div class="pull-right">
                            <p>
                                <strong>Credits: </strong>
                                <small><?php echo $test['Test']['credits']; ?></small>
                            </p>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php 
                                echo $this->Html->link( 
                                    '<i class="fa fa-eye"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'member'=>true, 'action'=>'view_group', $test['Test']['id']),
                                    array('escape' => false) ); 
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link( 
                                    '<i class="fa fa-calendar"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'member'=>true, 'action'=>$test['Test']['schedule_type'], $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal') 
                                );
                                ?>    
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
     </div>
</div>