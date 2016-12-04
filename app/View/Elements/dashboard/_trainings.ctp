<?php
    $data = $this->requestAction('/Users/view/'.AuthComponent::user('id'));
    #pr($data);
?>

<div class="box box-warning" style="border-left: 1px solid #F39C12; border-right: 1px solid #F39C12;">
    <div class="box-header">
        <h3 class="box-title">My Training</h3>
        <div class="box-tools pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> <span>Add</span>',
                array('controller'=>'Improvements', 'action'=>'add'),
                array('escape'=>false)
            );
            
            if(AuthComponent::user('Role.permission_level') >= 60){
                echo $this->Html->link(
                    '<i class="fa fa-wrench fa-fw"></i> <span>Manage</span>',
                    array('controller'=>'Improvements', 'action'=>'index'),
                    array('escape'=>false)
                );
                
            }
            ?>
        </div>
    </div>
        <table class="table table-striped table-condensed" id="assetsTable">
                                <thead>
                                    <tr class="tr-heading">
                                        <th class="col-md-6">Training</th>
                                        <th>Status</th>
                                        <th>Expires Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                    #pr($requiredTraining );
                                    foreach($data[0] as $training){
                                        $status = null;
                                        #pr($records[$training['Training']['id']]);
                                        $status = 'Current';
                                        $label = 'label label-success';
                                            
                                        if($data[1][$training['Training']['id']][0]['TrainingRecord']['in_progress'] == 1){
                                            $status = 'In Progress';
                                            $label = 'label label-primary';
                                        }
                                        
                                        if($data[1][$training['Training']['id']][0]['TrainingRecord']['expired'] == 1){
                                            $status = 'Expired';
                                            $label = 'label label-danger';
                                        }
                                        
                                        if($data[1][$training['Training']['id']][0]['TrainingRecord']['expiring'] == 1){
                                            $status = 'Expiring';
                                            $label = 'label label-warning';
                                        }
                                        
                                        if($data[1][$training['Training']['id']][0]['TrainingRecord']['no_record'] == 1){
                                            $status = 'No Record Found';
                                            $label = 'label label-danger';
                                        }
                                        
                                        $expires = (!empty($data[1][$training['Training']['id']][0]['TrainingRecord']['expires_on'])) ? date('F d, Y', strtotime($data[1][$training['Training']['id']][0]['TrainingRecord']['expires_on'])) : '--' ;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                echo $this->Html->link(
                                                    $training['Training']['name'],
                                                    '#',
                                                    array('escape'=>false)
                                                );
                                                ?> 
                                            </td>
                                            <td>
                                                <span class="<?=$label?>"><?=$status?></span>
                                            </td>
                                            <td><?=$expires?></td>
                                            <td><?php #$asset['model']?></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if(empty($data[0])){
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No Records Found</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
        </table>
    
    <div class="box-footer" style="border-bottom: 1px solid #F39C12;"></div>
</div>