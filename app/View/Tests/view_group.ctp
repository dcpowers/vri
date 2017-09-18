<?php
    $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
    $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
    #pr($tests);
    #exit;

    switch($tests[0]['Test']['schedule_type']){
        case "MultiplePeople":
            $type = "MultiplePeople";
            $type2 = "MultiplePeople_add";
            $colCount = 5;
            break;

        default:
            $type = "Type";
            $type2 = "Group_add";
            $colCount = 6;
            break;
    }
?>

<div class="training index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">View Details:</h6>
            <h3 class="dashhead-title"><i class="fa fa-clipboard fa-fw"></i> Testing</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Tests/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Tests/menu' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php #echo $this->element( 'Trainings/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>
    <div class="well">
        <div class="media">
			<div class="media-left media-middle">
				<?php
                $image = (!empty($tests[0]['Test']['logo'])) ? $tests[0]['Test']['logo'] : 'testing.jpg' ;

                echo $this->Html->image($image, array('class'=>'media-object', 'style'=>'width: 136px;'));

                ?>
			</div>

			<div class="media-body">
				<h2 class="media-heading"><?php echo $tests[0]['Test']['name']?></h2>
                <hr />
                <p><?php echo $tests[0]['Test']['description'];?></p>
			</div>
		</div>
    </div>

    <div class="pull-right">
        <?php
        if(AuthComponent::user('Role.permission_level') >= 30){
            ?>

                <ul class="list-inline">
                    <li class="pull-right">
                        <?php
                        echo $this->Html->link(
                            '<i class="fa fa-calendar fa-fw"></i><span class="text">Schedule</span>',
                            array('controller'=>'TestSchedules', 'action'=>$type, $tests[0]['Test']['id']),
                            array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs')
                        );
                        ?>
                    </li>
                </ul>
            <?php
        }
        ?>

    </div>
    <h2>Scheduled</h2>
    <hr />
    <table class="table">
        <thead>
            <tr class="tr-heading">
                <th>
                    <?php echo $this->Paginator->sort('TestSchedule.name', 'Name');?>
                    <?php if ($this->Paginator->sortKey() == 'TestSchedule.name'): ?>
                        <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('TestSchedule.assigned_on', 'Scheduled Date');?>
                    <?php if ($this->Paginator->sortKey() == 'TestSchedule.assigned_on'): ?>
                        <i class='fa fa-sort-<?php echo $this->Paginator->sortDir() === 'asc' ? 'up' : 'down'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('TestSchedule.expires_on', 'Expires Date');?>
                    <?php if ($this->Paginator->sortKey() == 'TestSchedule.expires_on'): ?>
                        <i class='fa fa-sort-<?php echo $this->Paginator->sortDir() === 'asc' ? 'up' : 'down'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                <?php
                    if($type != 'MultiplePeople'){
                        ?>
                        <th>Link</th>
                        <?php
                    }
                ?>
                <th>Completed</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            #pr($tests);

            if(!empty($tests[0]['TestSchedule'])){
                foreach($tests as $key=>$item){
                    unset($links);
                    $links = array();

                    if($item['showReport'] == 1){
                        foreach($item['Test']['ReportSwitch'] as $report){
                            if(($report['Report']['is_user_report'] == 2 || is_null($report['Report']['is_user_report']))){
                                $is_blind = (empty($item['TestSchedule']['link_num'])) ? false : true ;
                                $links[] = $this->Html->link(
                                    '<i class="fa fa-pie-chart"></i>',
                                    array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>$report['Report']['action'], $item['TestSchedule']['id'], $is_blind ),
                                    array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Available Report')
                                );

                            }
                        }
                    }

                    $links[] = $this->Html->link(
                        '<i class="fa fa-trash"></i>',
                        array('controller'=>'TestSchedules', 'action'=>'delete',$item['TestSchedule']['id'], $item['Test']['id'] ),
                        array('escape'=>false),
                        "Are you Sure You Want To Delete This Scheduled Test?"
                    );
                    ?>
                    <tr>
                        <td>
                            <?php
                            if(!empty($item['TestSchedule']['link'])){
                                echo $item['TestSchedule']['name'];
                            }else{
                                echo $this->Html->link(
                                    $item['TestSchedule']['name'],
                                    '#'.$item['TestSchedule']['id'],
                                    array(
                                        'escape' => false,
                                        'data-toggle'=>'collapse',
                                        'data-parent'=>'#accordion',
                                        'aria-expanded'=>'false',
                                        'aria-controls'=>$item['TestSchedule']['id'],
                                        'class'=>'collapsed'
                                    )
                                );
                            }
                            ?>
                        </td>
                        <td><?php echo date( APP_DATE_FORMAT,strtotime($item['TestSchedule']['assigned_on'])); ?></td>
                        <td><?php echo date( APP_DATE_FORMAT,strtotime($item['TestSchedule']['expires_on'])); ?></td>
                        <?php
                            if($type != 'MultiplePeople'){
                                $url = null;

                                if(!empty($item['TestSchedule']['link'])){
                                    $url = Router::url( array('controller'=>'','action'=>'t'), true ).'/'.$item['TestSchedule']['link'];
                                }
                                ?>
                                <td><?=$url?></td>
                                <?php
                            }
                        ?>
                        <td><?=$item['completed_count']?> / <?=$item['total_count']?></td>
                        <td>
                            <ul class="list-inline pull-right">
                                <?php
                                foreach($links as $link){
                                    ?>
                                    <li><?=$link?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="<?=$colCount?>">
                            <div class="collapse" id="<?=$item['TestSchedule']['id'] ?>" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel panel-default">
                                    <table class="table">
                                        <thead>
                                            <tr class="tr-heading">
                                                <th style="width: 35%;">
                                                    User &nbsp;
                                                    <?php
                                                    echo $this->Html->link(
                                                        '<i class="fa fa-user-plus"></i>',
                                                        array('controller'=>'TestSchedules', 'action'=>$type2, $item['Test']['id'], $item['TestSchedule']['id'] ),
                                                        array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                                                    );
                                                    ?>
                                                </th>
                                                <?php
                                                if($type == 'MultiplePeople'){
                                                    ?>
                                                    <th style="width: 25%;">Role</th>
                                                    <?php
                                                }
                                                ?>
                                                <th>Completed</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($item['AssignedTest'] as $details){
                                                unset($sublinks);
                                                $sublinks = array();
                                                $details['complete'] = intval($details['complete']);

                                                if(!in_array('Self',$details['TestRole']) ){
                                                    $sublinks[] = $this->Html->link(
                                                        '<i class="fa fa-trash"></i>',
                                                        array('controller'=>'TestSchedules', 'action'=>'singleUserDelete', $details['id'], $details['user_id'], $tests[0]['Test']['id'] ),
                                                        array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Delete', 'id'=>$details['id']),
                                                        "Are you Sure You Want To Delete This Scheduled Test?"
                                                    );
                                                }

                                                $bar_width = ($details['complete']<= 9) ? 10 : $details['complete'] ;
                                                $bar_text = ($details['complete']<= 9) ? 'info' : 'success' ;
                                                ?>
                                                <tr>
                                                    <td><?=$details['User']['fullname']?></td>
                                                    <?php
                                                    if($type == 'MultiplePeople'){
                                                        ?>
                                                        <td><?=$details['TestRole']['name']?></td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-<?=$bar_text?>" data-transitiongoal="<?=$details['complete']?>" style=width:<?=$bar_width?>%; "min-width: 2em;" aria-valuenow="<?=$bar_width?>"><?=$details['complete']?> %</div>
                                                        </div>
                                                    </td>
                                                    <td clas="text-center">
                                                        <ul class="list-inline pull-right">
                                                            <?php
                                                            foreach($sublinks as $sublink){
                                                                ?>
                                                                <li><?=$sublink?></li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                    //unset($link);
                }
            }else{
                ?>
                <tr>
                    <td colspan="6">No Records Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <div class="paginate">
        <?php
        echo $this->Paginator->numbers(array(
            'before' => '<ul class="pagination pagination-lg">',
            'separator' => '',
            'currentClass' => 'active',
            'currentTag' => 'a',
            'tag' => 'li',
            'after' => '</ul>'
        ));
        ?>
        <div class="pull-right">
            <?php
            echo $this->Paginator->counter(
                'Page {:page} of {:pages}, showing {:current} records out of {:count} total,
                starting on record {:start}, ending on {:end}' );
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>