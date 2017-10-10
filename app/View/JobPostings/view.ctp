<?php
    $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
    
    $number_applicants = 0;
    $new_count = 0;
    $count_item = null;
    
    foreach($applicants as $current_item){
        if(!empty($current_item['ApplyJob'])){
            $number_applicants++;
            
            if($current_item['ApplyJob']['status'] == 0){
                $new_count++;
            }
        }
    }
                                
    if($new_count >= 1){
        $count_item = '<small><small class="text-success">'.$new_count. ' New</small></small>'; 
    }
    
    $opened = date( APP_DATE_FORMAT, strtotime($jobInfo['JobPosting']['created']));
?> 
<style type="text/css">
    #myModal2 .modal-dialog{width:75%;}
    .border{ border: 1px #ff0000 solid; }
    
    @media screen and (min-width: 768px) {
        #myModal2 .modal-dialog{width:900px;}
    }
    
    .spacer {
        margin-top: 40px; /* define margin as you see fit */
    }
        
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2 class="title"><?=$jobInfo['Job']['name']?></h2>
            <p><?=$jobInfo['Group']['city']?>, <?=$jobInfo['Group']['State']['state_name']?> </p>
            <?php
            if($jobInfo['User']['id'] == AuthComponent::user('id') || in_array(4, $role_ids)){
                echo $this->Html->link( 
                    'Edit Job Opening', 
                    array('controller'=>'JobPostings','action'=>'edit','member'=>true, $id), 
                    array('escape'=>false, 'class'=>'btn btn-default btn-xs') 
                );
                ?>
                <?php
                echo $this->Html->link( 
                    '<i class="fa fa-trash"></i>', 
                    array('controller'=>'JobPostings','action'=>'confirm','member'=>true, $id),
                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal','class'=>'btn btn-default btn-xs') 
                );
            }
            ?>
        </div>
        <div class="pull-right col-md-6">
            <div class="row">
                <div class="col-md-4 text-center">
                    <?php
                    if($jobInfo['User']['id'] == AuthComponent::user('id')){
                        echo $this->Html->link( 
                            '<i class="fa fa-user-plus fa-2x"></i>', 
                            array('controller'=>'JobPostings','action'=>'collaboraters','member'=>true, $id), 
                            array(
                                'escape'=>false, 
                                'data-toggle'=>'modal', 
                                'data-target'=>'#myModal', 
                                'class'=>'btn btn-default btn-sm', 
                                'id'=>'collaborate',
                                'data-placement'=>'bottom',
                                'title'=>'Collaborate on this Job Opening'
                            ) 
                        );
                    }
                    ?>    
                    
                </div>
                <div class="col-md-3">
                    <p><small>Hiring Lead</small> <br /><b><?=$jobInfo['User']['fullname']?></b></p>
                </div>
                
                <div class="col-md-2">
                    <p><small>Status</small> <br /><b><?php echo $settings['job_status'][$jobInfo['JobPosting']['status']]; ?></b></p>
                </div>
                <div class="col-md-3">
                    <p><small>Opened On</small> <br /><b><?=$opened?></b></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row spacer">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
        </div>
    </div>
    
    <p class="spacer">
        <i class="fa fa-users"></i>&nbsp; <?=$number_applicants?> Applicants &nbsp;<?=$count_item?>
        <?php
        if($jobInfo['User']['id'] == AuthComponent::user('id')){
            echo $this->Html->link(
                '<i class="fa fa-search"></i> Search For Job Seekers',
                array('controller'=>'JobPostings', 'action'=>'search', 'member'=>true, $jobInfo['JobPosting']['id']),
                array('escape'=>false, 'class'=>'btn btn-primary btn-xs')
            );
        }
        ?>
    </p>
    <table class="table table-hover table-condensed">
        <thead>
            <tr class="tr-heading">
                <th>Date</th>
                <th>Percent Match</th>
                <th>Name</th>
                <th>Phone</th>
                <th>E-Mail Address</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
                            
        <tbody>
            <?php
            if(!empty($applicants['none'])){
                ?>
                <tr>
                    <td colspan="7"><?=$applicants['none']?></td>
                </tr>
                <?php
            }else{
                //pr($applicants);
                foreach($applicants as $user){
                    if(empty($user['JobOffer'])){
                        $now = new DateTime("now");
                        $old_time = new DateTime($user['ApplyJob']['updated']);
                                                
                        $accuracy = 1;
                        $difference = $now->diff($old_time);
                                                
                        $intervals = array('y' => 'year', 'm' => 'month', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
                                                
                        $i = 0;
                        $result = '';
                        foreach ($intervals as $interval => $name) {
                            if ($difference->$interval > 1) {
                                $result .= $difference->$interval.' '. $intervals[$interval] . 's';
                                $i++;
                            } elseif ($difference->$interval == 1) {
                                $result .= $difference->$interval.' '. $intervals[$interval];
                                $i++;
                            }
                                                    
                            if ($i == $accuracy) {
                                break;
                            }
                        }
                                                
                        $full_name = $user['User']['first_name'].' '.$user['User']['last_name'];
                        $e_mail = $user['User']['username'];
                        $match = $user['ApplyJob']['percent_match'].'%';
                                            
                        $applied = date( APP_DATE_FORMAT, strtotime($user['ApplyJob']['created']));                                    
                                            
                        $open_tag = null;
                        $close_tag = null;
                                                
                        if($user['ApplyJob']['status'] == 0){
                            $open_tag = '<b>';
                            $close_tag = '</b>';
                        } 
                        ?>
                        <tr>
                            <td><?=$open_tag?><?=$applied?><?=$close_tag?></td>
                            <td><?=$open_tag?><?=$match?><?=$close_tag?></td>
                            <td><?=$open_tag?>
                                <?php
                                echo $this->Html->link( 
                                    $full_name, 
                                    array('controller'=>'Jobseekers','action'=>'view_applicant','member'=>true, $user['ApplyJob']['id']), 
                                    array('escape'=>false) 
                                );
                                ?>
                                <?=$close_tag?>
                            </td>
                            <td><?=$open_tag?><?=$user['User']['DetailUser']['phone']?><?=$close_tag?></td>
                            <td><?=$open_tag?><?=$e_mail?><?=$close_tag?></td>
                            <td><?=$open_tag?><?php echo $settings['applicant_status'][$user['ApplyJob']['status']]; ?> ( <?=$result?> )<?=$close_tag?></td>
                            <td>
                                <ul class="list-inline">
                                    <li>
                                        <?php 
                                        echo $this->Html->link(
                                            '<i class="fa fa-file-text"></i>', 
                                            array('controller'=>'Jobs', 'action'=>'view_notes', 'member'=>true, $user['ApplyJob']['id']),
                                            array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                                        );
                                        ?>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }    
            ?>
        </tbody>
    </table>
</div><!-- end container -->

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModal2").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        
        $('#collaborate').tooltip()
    });
</script>