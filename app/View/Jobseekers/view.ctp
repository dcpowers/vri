<?php
    $this->Html->addCrumb('Job Seekers', array('controller' => 'jobseekers', 'action' => 'index', 'member'=>true));
    
    #pr($user);
    #exit;
    $cc_select = null;
    $pg_select = null;
    foreach($user[0]['AssignedJobseekers'] as $item){
        if($item['model'] == 'cc'){
            $cc_select = $item['wfc_id'];
        }
                                        
        if($item['model'] == 'pg'){
            $pg_select = $item['wfc_id'];
        }
        
    }
?>

<div class="container">
    <h2 class="title"><i class="fa fa-users"></i><span class="text">Job Seekers</span></h2>
    <hr class="solidOrange" />
    <h3>
        <?=$user[0]['User']['first_name']?> <?=$user[0]['User']['last_name']?>:
        <small><?=$user[0]['User']['username']?></small>
    </h3>
    <hr />
    <div class="pull-right">
        <?php 
        echo $this->Form->create('User', array(
            'url' => array('controller'=>'jobseekers', 'action'=>'edituser', 'member'=>true), 
            'role'=>'form',
            'class'=>'form-inline',
            'inputDefaults' => array(
                'label' => false,
                'div' => false,
                'class'=>'form-control',
                'error'=>false
            )
        )); 
        echo $this->Form->hidden( 'AssignedJobseeker.user_id', array( 'value' => $user[0]['User']['id'] ) );
        ?>
        <div class="form-group">
            <label for="Career Counselor">Career Counselor:</label>
            <?php
            echo $this->Chosen->select('AssignedJobseeker.cc_id', 
                array($userList),
                array( 'empty'=>'Select A Career Counselor', 'data-placeholder' => 'Select A Career Counselor', 'multiple' => false, 'default'=>$cc_select )
            ); 
            ?>
        </div>
                                    
        <div class="form-group">
            <label for="Program Manager">Program Manager:</label>
            <?php 
            echo $this->Chosen->select('AssignedJobseeker.pg_id', 
                array($userList),
                array( 'empty'=>'Select A Program Manager', 'data-placeholder' => 'Select A Program Manager', 'multiple' => false, 'default'=>$pg_select )
            ); 
            ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm')); ?>
        </div>
        <div class="clearfix"></div>
        <?php echo $this->Form->end(); ?>    
    </div>
    <div class="clearfix"></div>
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#testing" aria-controls="testing" role="tab" data-toggle="tab"><i class="fa fa-clipboard"></i><span class="text">Testing</span></a></li>
            <li role="presentation"><a href="#training" aria-controls="training" role="tab" data-toggle="tab"><i class="fa fa-graduation-cap"></i><span class="text">Training</span></a></li>
            <li role="presentation"><a href="#job" aria-controls="job" role="tab" data-toggle="tab"><i class="fa fa-search"></i><span class="text">Job search</span></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="testing">
                <h3>Testing</h3>
                <hr class="solidGray" />
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr class="tr-heading">
                            <th>Name</th>
                            <th>Scheduled</th>
                            <th>Completed</th>
                            <th>% Complete</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($user[0]['AssignedTest'] as $test){
                            #pr($test);
                            $date = explode('-', $test['completion_date']);
                            $m = $date[1];
                            $d = $date[2];
                            $y = $date[0];
                                                
                            $c_date = (checkdate($m, $d, $y)) ? date( APP_DATE_FORMAT, strtotime($test['completion_date'])) : null;
                            $a_date = (!empty($test['assigned_date'])) ? date( APP_DATE_FORMAT, strtotime($test['assigned_date'])) : null;
                            $e_date = (checkdate($m, $d, $y)) ? null : date( APP_DATE_FORMAT, strtotime($test['expires_date']));
                            $percent = intval($test['complete']);
                            
                                                
                            //lets get all avaialbe reports
                            if(!empty($test['report']) && checkdate($m, $d, $y)){
                                foreach($test['report'] as $report){
                                    if($report['Report']['is_user_report'] == 3) {
                                        $link[] = $this->Html->link(
                                            '<i class="fa fa-pie-chart"></i>',
                                            array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>$report['Report']['action'], $test['id'] ),
                                            array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'View Coaching Report')
                                        );
                                    }else{
                                        $link = array();
                                    }
                                }
                            }else{
                                $link = array();
                            }
                            ?>
                            <tr>
                                <td><?=$test['Test']['name']?></td>
                                <td><?=$a_date?></td>
                                <td><?=$c_date?></td>
                                <td>
                                    <div class="progress demo-only active">
                                        <div class="progress-bar progress-bar-info" data-transitiongoal="<?=$percent?>" style="width: <?=$percent?>%;" aria-valuenow="<?=$percent?>"><?=$percent?>%</div>
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
                        ?>
                    </tbody>
                </table>
            </div><!-- End: Testing -->
            <div role="tabpanel" class="tab-pane" id="training">
                <h2>Training</h2>
                <hr />
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr class="tr-heading">
                            <th style="width: 40%">Name</th>
                            <th style="width: 30%">Scheduled</th>
                            <th style="width: 30%">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        #pr($user);
                        foreach($user[0]['AssignedTraining'] as $item){
                            #pr($test);
                            $date = explode('-', $item['completion_date']);
                            $m = $date[1];
                            $d = $date[2];
                            $y = $date[0];
                                                
                            $c_date = (checkdate($m, $d, $y)) ? date( APP_DATE_FORMAT, strtotime($item['completion_date'])) : null;
                            $a_date = (!empty($item['assigned_date'])) ? date( APP_DATE_FORMAT, strtotime($item['assigned_date'])) : null;
                                                
                            ?>
                            <tr>
                                <td><?=$item['Training']['name']?></td>
                                <td><?=$a_date?></td>
                                <td><?=$c_date?></td>
                            </tr>
                            <?php
                            unset($link);
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- End: Training -->
            <div role="tabpanel" class="tab-pane" id="job">
                <h2>Job Information</h2>
                <hr />
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr class="tr-heading">
                            <th style="width: 30%">Job</th>
                            <th style="width: 30%">Company</th>
                            <th style="width: 20%">Applied</th>
                            <th style="width: 20%">Not Interested</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($user[0]['ApplyJob'] as $item){
                            $appliedOn = date( APP_DATE_FORMAT, strtotime($item['updated']))
                            ?>
                            <tr>
                                <td><?=$item['JobPosting']['Job']['name']?></td>
                                <td><?=$item['JobPosting']['Job']['Group']['name']?></td>
                                <td><?=$appliedOn?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                        }
                                            
                        foreach($user[0]['ExemptJob'] as $item){
                            $exemptOn = date( APP_DATE_FORMAT, strtotime($item['updated']))
                            ?>
                            <tr>
                                <td><?=$item['JobPosting']['Job']['name']?></td>
                                <td><?=$item['JobPosting']['Job']['Group']['name']?></td>
                                <td>&nbsp;</td>
                                <td><?=$exemptOn?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- End: Job Search -->
        </div>
    </div><!-- End: Tabpanel -->
</div><!-- End: Container -->
       
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chosen-select").chosen();
    });
   
</script>

            