<?php
    $testing = $this->requestAction('Tests/dashboard/');

    $auth_role = Set::extract( AuthComponent::user(), '/AuthRole/id' );
    if( in_array(5,$auth_role) || in_array(6,$auth_role) ){
        $user_report = 1;
    }else{
        $user_report = 2;
    }

    $report_count = 0;
    if(!empty($testing['completed'])){
        foreach($testing['completed'] as $test){
            $hasReport = 0;

            if(!empty($test['Test']['Report'])){
                foreach($test['Test']['Report'] as $report){
                    if($report['is_user_report'] == $user_report) {
                        $hasReport = 1;
                        break;
                    }
                }
            }

            if($hasReport == 1){
                $report_count++;
            }
        }
    }

    $current_count = (!empty($testing['current'])) ? count($testing['current']) : 0;
    $completed_count = (!empty($testing['completed'])) ? count($testing['completed']) : 0;


    #exit;

    ?>
    <style type="text/css">
        .panel.with-nav-tabs .panel-heading{
            padding: 5px 5px 0 5px;
        }

        .panel.with-nav-tabs .nav-tabs{
            border-bottom: none;
        }

        .panel.with-nav-tabs .nav-justified{
            margin-bottom: -1px;
        }

        .label-as-badge {
            border-radius: 1em;
        }

    </style>

    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#currentTesting" data-toggle="tab">My Current Testing &nbsp;<span class="label label-primary label-as-badge pull-right"><?=$current_count?></span></a></li>
                <li class=""><a href="#allTesting" data-toggle="tab">My Completed Testing &nbsp;<span class="label label-primary label-as-badge pull-right"><?=$completed_count?></span></a></li>
                <li class=""><a href="#reportsTesting" data-toggle="tab">Available Reports &nbsp;<span class="label label-primary label-as-badge pull-right"><?=$report_count?></span></a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <!-- Begin: Current Testing-->
                <div class="tab-pane fade active in" id="currentTesting">
                    <?php
                    if(!empty($testing['current']) && isset($testing['current'])){
                        foreach($testing['current'] as $test){
                            $test['complete'] = intval($test['AssignedTest']['complete']);

                            $status = '<i class="fa fa-check-square-o fa-3x text-info" data-toggle="tooltip" data-placement="top" title="Active"></i>';
                            $status_class = 'btn-info';
                            $progress_bar = 'progress-bar-info';
                            $text_status = 'text-default';
                            $completed = 'N/A';
                            $expires = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['expires_date']));
                            $assigned_on = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['assigned_date']));

                            if($test['AssignedTest']['complete'] == 0){
                                $tip = 'Start Testing';
                                $text = 'Start';
                            }else{
                                $text = 'Continue';
                                $tip = 'Continue Testing';

                            }

                            if(strtotime($test['AssignedTest']['expires_date']) < time()){
                                $status = '<i class="fa fa-exclamation-triangle fa-3x text-danger" data-toggle="tooltip" data-placement="top" title="Expired"></i>';
                                $status_class = 'btn-danger';
                                $progress_bar = 'progress-bar-danger';
                                $text_status = 'text-danger';
                            }

                            ?>
							<div class="media">
						  		<div class="media-left media-middle" style="padding-right: 30px;">
						    		<?=$status?>
						  		</div>

								<div class="media-body">
									<h4 class="media-heading"><?=$test['Test']['name']?></h4>

									<div class="progress">
                                    	<div class="progress-bar <?=$progress_bar?>" data-transitiongoal="0" style="width: <?=$test['complete']?>%; min-width: 2em;" aria-valuenow="<?=$test['complete']?>"><?=$test['complete']?>%</div>
                                    </div>

									<ul class="list-inline">
                                    	<li><b>Assigned On:</b>&nbsp;<small><?=$assigned_on?></small></li>
                                        <li class="<?=$text_status?>"><b>Expires On:</b>&nbsp;<small><?=$expires?></small></li>
                                    </ul>

									<?php
                                    if(!empty($test['TestRole']['name'])){
                                    	?>
                                        <ul class="list-inline">
                                        	<li><b>Who:</b>&nbsp;<small><?=$test['TestSchedule']['name']?></small></li>
                                            <li><b>Your Role:</b>&nbsp;<small><?=$test['TestRole']['name']?></small></li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </div>

								<div class="media-right media-middle">
									<?php
                                    echo $this->Html->link(
                                    	$text,
                                        array('controller'=>'tests', 'action'=>'test', $test['AssignedTest']['id'] ),
                                        array('type' => 'button', 'class'=>'btn outline '.$status_class.'')
                                    )
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <!-- End: Current Testing -->

                <!-- Begin: All Testing-->
                <div class="tab-pane fade" id="allTesting">
                    <?php
                    if(!empty($testing['completed'])){
                        foreach($testing['completed'] as $test){
                            $test['complete'] = intval($test['AssignedTest']['complete']);

                            $completed_on = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['completion_date']));
                            $assigned_on = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['assigned_date']));
                            $tip = "View Report";

                            $hasReport = 0;
                            if(!empty($test['Test']['Report'])){
                                foreach($test['Test']['Report'] as $report){
                                    if($report['is_user_report'] == $user_report) {
                                        $hasReport = 1;
                                        $action = $report['action'];
                                        break;
                                    }
                                }
                            }

                            if($hasReport == 1){
                                $testing['report'][] = $test;
                            }
                            ?>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h4 class="list-group-item-heading"><?=$test['Test']['name']?></h4>

                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" data-transitiongoal="0" style="width: <?=$test['complete']?>%; min-width: 2em;" aria-valuenow="<?=$test['complete']?>"><?=$test['complete']?>%</div>
                                    </div>

                                    <ul class="list-inline">
                                        <li><b>Assigned On:</b>&nbsp;<small><?=$assigned_on?></small></li>
                                        <li><b>Completed On:</b>&nbsp;<small><?=$completed_on?></small></li>
                                    </ul>
                                    <?php
                                    if(!empty($test['TestRole']['name'])){
                                        ?>
                                        <ul class="list-inline">
                                            <li><b>Who:</b>&nbsp;<small><?=$test['TestSchedule']['name']?></small></li>
                                            <li><b>Your Role:</b>&nbsp;<small><?=$test['TestRole']['name']?></small></li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <!-- End: All Testing -->

                <!-- Begin: Available Reports -->
                <div class="tab-pane fade" id="reportsTesting">
                    <?php
                    if(!empty($testing['report'])){
                        foreach($testing['report'] as $test){
                            $test['complete'] = intval($test['AssignedTest']['complete']);

                            $completed_on = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['completion_date']));
                            $assigned_on = date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['assigned_date']));

                            $tip = "View Report";

                            $hasReport = 0;
                            if(!empty($test['Test']['Report'])){
                                foreach($test['Test']['Report'] as $report){
                                    if($report['is_user_report'] == $user_report) {
                                        $hasReport = 1;
                                        $action = $report['action'];
                                        break;
                                    }
                                }
                            }

                            if($hasReport == 1){
                                $testing['report'][] = $test;
                            }
                            ?>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h4 class="list-group-item-heading"><?=$test['Test']['name']?></h4>

                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success" data-transitiongoal="0" style="width: <?=$test['complete']?>%; min-width: 2em;" aria-valuenow="<?=$test['complete']?>"><?=$test['complete']?>%</div>
                                            </div>

                                            <ul class="list-inline">
                                                <li><b>Assigned On:</b>&nbsp;<small><?=$assigned_on?></small></li>
                                                <li><b>Completed On:</b>&nbsp;<small><?=$completed_on?></small></li>
                                            </ul>
                                            <?php
                                            if(!empty($test['TestRole']['name'])){
                                                ?>
                                                <ul class="list-inline">
                                                    <li><b>Who:</b>&nbsp;<small><?=$test['TestSchedule']['name']?></small></li>
                                                    <li><b>Your Role:</b>&nbsp;<small><?=$test['TestRole']['name']?></small></li>
                                                </ul>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <?php
                                            echo $this->Html->link(
                                                'Report',
                                                array('plugin'=>'report', 'controller'=>'report', 'action'=>$action, $test['AssignedTest']['id'] ),
                                                array('type' => 'button', 'class'=>'btn outline btn-success')
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>
                </div>
                <!-- End: Available Report -->
            </div>
        </div>

    </div>
