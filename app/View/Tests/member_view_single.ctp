<?php
    $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
    $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
    
    #pr($tests);
    #exit;
?>

<div class="container">
    <h2 class="title"><i class="fa fa-clipboard"></i> Testing</h2>
    <hr class="solidOrange" />
    <div class="well">
        <div class="row">
            <div class="col-md-2">
                <?php
                $image = (!empty($tests[0]['Test']['logo'])) ? $tests[0]['Test']['logo'] : 'testing.jpg' ;
                            
                echo $this->Html->image($image, array('class'=>'img-responsive img-thumbnail'));
                    
                ?>
            </div>
            
            <div class="col-md-10">
                <h2 class="no-margin"><?php echo $tests[0]['Test']['name']?></h2>
                <hr />
                <p><strong>Credits: </strong>
                    <small><?php echo $tests[0]['Test']['credits']; ?></small>
                </p>
                <p><?php echo $tests[0]['Test']['description'];?></p>
            </div>
        </div>
    </div>
    
    <div class="pull-right">
        <?php
        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            ?>
            
                <ul class="list-inline">
                    <li class="pull-right">
                        <?php
                        echo $this->Html->link( 
                            '<i class="fa fa-calendar"></i><span class="text">Schedule</span>',
                            array('controller'=>'TestSchedules', 'member'=>true, 'action'=>$tests[0]['Test']['schedule_type'], $tests[0]['Test']['id']),
                            array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-xs') 
                        );
                        ?>
                    </li>
                    <li class="pull-right"><b>Available Credits: </b> <?php echo $credits; ?></li>
                </ul>
            <?php
        }
        ?>
        
    </div>
            
    <h2>Scheduled</h2>
    <hr />
    <table class="table table-hover table-condensed">
        <thead>
            <tr class="tr-heading">
                <th>
                    <?php echo $this->Paginator->sort('User.first_name', 'User');?>  
                    <?php if ($this->Paginator->sortKey() == 'User.first_name'): ?>
                        <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                                            
                <th>
                    <?php echo $this->Paginator->sort('assigned_date', 'Scheduled Date');?>  
                    <?php if ($this->Paginator->sortKey() == 'assigned_date'): ?>
                        <i class='fa fa-sort-<?php echo $this->Paginator->sortDir() === 'asc' ? 'up' : 'down'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                                            
                <th>
                    <?php echo $this->Paginator->sort('expires_date', 'Expires Date');?>
                    <?php if ($this->Paginator->sortKey() == 'expires_date'): ?>
                        <i class='fa fa-sort-<?php echo $this->Paginator->sortDir() === 'asc' ? 'up' : 'down'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                                            
                <th>
                    <?php echo $this->Paginator->sort('completion_date', 'Completion Date');?>
                    <?php if ($this->Paginator->sortKey() == 'completion_date' || $this->Paginator->sortKey() == 'complete'): ?>
                        <i class='fa fa-sort-<?php echo $this->Paginator->sortDir() === 'asc' ? 'up' : 'down'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                                
                <th>
                    <?php echo $this->Paginator->sort('complete', 'Completed');?>
                    <?php if ($this->Paginator->sortKey() == 'complete' || $this->Paginator->sortKey() == 'completion_date'): ?>
                        <i class='fa fa-sort-amount-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>
                                            
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($tests[0]['AssignedTest'])){ 
                foreach($tests as $key=>$item){
                    $c_date = ($item['AssignedTest']['completion_date'] != '0000-00-00') ? date( APP_DATE_FORMAT,strtotime($item['AssignedTest']['completion_date'])) : null;        
                    $warning = strtotime("-14 day", strtotime($item['AssignedTest']['expires_date']));
                    $item['AssignedTest']['complete'] = intval($item['AssignedTest']['complete']);
                                                
                    $time = time();
                                                
                    $text_class = 'text-success';
                    $bar_class = 'success';
                                            
                    if(is_null($c_date)){
                        $text_class = ($time >= $warning) ? 'text-warning' : $text_class ;
                        $bar_class = ($time >= $warning) ? 'warning' : $bar_class ;
                                                
                        $text_class = (strtotime($item['AssignedTest']['expires_date']) <= $time) ? 'text-danger' : $text_class ;
                        $bar_class = (strtotime($item['AssignedTest']['expires_date']) <= $time) ? 'danger' : $bar_class ;
                    }
                    
                    if(!is_null($c_date)){
                        foreach($item['Test']['ReportSwitch'] as $report){
                            if(($report['Report']['is_user_report'] == 2 || is_null($report['Report']['is_user_report']))){
                                $link[] = $this->Html->link(
                                    '<i class="fa fa-pie-chart"></i>',
                                    array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>$report['Report']['action'], $item['AssignedTest']['id'] ),
                                    array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Available Report')
                                );
                            }
                        }
                    }
                                    
                    if($item['AssignedTest']['complete'] >= 1 ){
                        $link[] = $this->Html->link(
                            '<i class="fa fa-undo"></i>',
                            array(
                                'member'=>true, 
                                'controller'=>'tests', 
                                'action'=>'reset', 
                                $item['AssignedTest']['id'],
                                $item['AssignedTest']['user_id'],
                                $item['Test']['id']
                            ),
                            array(
                                'escape'=>false, 
                                'data-toggle'=>'tooltip', 
                                'data-placement'=>'top', 
                                'title'=>'Reset Test'
                            )
                        );
                    }        
                                    
                    $link[] = $this->Html->link(
                        '<i class="fa fa-trash"></i>', 
                        array('controller'=>'TestSchedules', 'member'=>true, 'action'=>'singleUserDelete', $item['AssignedTest']['id'], $item['AssignedTest']['user_id'], $tests[0]['Test']['id'] ),
                        array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Delete', 'id'=>$item['AssignedTest']['id']),
                        "Are you Sure You Want To Delete This Scheduled Test?"
                    );
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo $this->Html->link( 
                                $item['User']['first_name'].' '.$item['User']['last_name'],
                                array('controller'=>'users', 'member'=>true, 'action'=>'view', $item['User']['id']),
                                array('escape' => false) 
                            );    
                            ?>
                        </td>
                        <td><?php echo date( APP_DATE_FORMAT,strtotime($item['AssignedTest']['assigned_date'])); ?></td>
                        <td class="<?=$text_class?>"><?php echo date( APP_DATE_FORMAT,strtotime($item['AssignedTest']['expires_date'])); ?></td>
                        <td><?=$c_date?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-<?=$bar_class?>" data-transitiongoal="<?=$item['AssignedTest']['complete']?>" style="width: <?=$item['AssignedTest']['complete']?>%; min-width: 2em;" aria-valuenow="<?=$item['AssignedTest']['complete']?>"><?=$item['AssignedTest']['complete']?> %</div>
                            </div>
                        </td>
                        <td>
                            <ul class="list-inline pull-right">
                                <?php
                                foreach($link as $reportLink){
                                    ?>
                                    <li><?=$reportLink?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <?php
                    unset($link);
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
    
<div class="modal bootstrap-dialog type-primary fade size-normal in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        
    });
</script>            