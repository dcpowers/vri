<?php
    #pr($trnCat);
    #exit; 
?>
<div class="training view bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Training Details: <?=$training['Training']['name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-pencil fa-fw"></i> Edit',
                array('controller'=>'Trainings', 'action'=>'edit', $training['Training']['id']),
                array( 'escape'=>false, 'class'=>'btn btn-primary btn-outline' )
            );
            ?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
        </div>
    </div>
    
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab">Training Details</a></li>
            <li><a href="#records" data-toggle="tab">Training Records</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active in" id="info">
                <div class="row">
                    <div class="col-sm-2">
                        <?php
                        $name = '../groupFiles/'.$training['Training']['image'];
                        $image = (!empty($training['Training']['image'])) ? $name : 'noImage.png' ;
                                        
                        echo $this->Html->image($image, array('class'=>'img-responsive img-thumbnail'));
                        ?>
                        <dl>
                            <dt>Status:</dt>
                            <dd><?=$training['Status']['name']?></dd>
                        </dl>
                        
                        <dl>
                            <dt>Is Required:</dt>
                            <dd><?=$settings[$training['Training']['is_active']]?></dd>
                        </dl>
                        
                        <dl>
                            <dt>Last Update:</dt>
                            <dd><?php echo date('F d, Y', strtotime($training['Training']['updated_date'])); ?></dd>
                        </dl>
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Name:</dt>
                                    <dd><?=$training['Training']['name']?></dd>
                                </dl>
                                
                                <dl>
                                    <dt>Description:</dt>
                                    <dd><?=$training['Training']['description']?></dd>
                                </dl>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Video Files</h3>
                                    </div>
                                    <div class="panel-body">
                                        <dl class="dl-horizontal">
                                            <dt>Name:</dt>
                                            <dd><?=$training['Training']['video']?></dd>
                                        
                                            <dt>Poster:</dt>
                                            <dd><?=$training['Training']['video_poster']?></dd>
                                        
                                            <dt>Runtime:</dt>
                                            <dd><?=$training['Training']['runtime']?></dd>
                                            
                                            <dt>Last Updated:</dt>
                                            <dd><?php echo date('F d, Y', strtotime($training['Training']['video_date'])); ?></dd>
                                        </dl>
                                    </div>
                                </div>
                                
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Power Point Files</h3>
                                    </div>
                                    <div class="panel-body">
                                        <dl class="dl-horizontal">
                                            <dt>Name:</dt>
                                            <dd><?=$training['Training']['power_point']?></dd>
                                        
                                            <dt>Last Updated:</dt>
                                            <dd><?php echo date('F d, Y', strtotime($training['Training']['power_point_date'])); ?></dd>
                                        </dl>
                                    </div>
                                </div>
                                
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Leader Files</h3>
                                    </div>
                                    <div class="panel-body">
                                        <dl class="dl-horizontal">
                                            <dt>Name:</dt>
                                            <dd><?=$training['Training']['leader_files']?></dd>
                                        
                                            <dt>Last Updated:</dt>
                                            <dd><?php echo date('F d, Y', strtotime($training['Training']['leader_files_date']))?></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End: Info Tab -->
            
            <div class="tab-pane fade" id="records">
                <table class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Hrs</th>
                            <th>Is Exempt</th>
                            <th>Expires On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($training['TrainingRecord'] as $record){
                            #pr($record);
                            $expiresDate = !empty($record['expires_on']) ? date('F d, y', strtotime($record['expires_on'])) : null ;
                            ?>
                            <tr>
                                <td><?php echo ucwords( strtolower($record['User']['first_name']));?> <?php echo ucwords( strtolower($record['User']['last_name'])); ?></td>
                                <td><?php echo date('F d, Y', strtotime($record['date'])); ?></td>
                                <td><?=$record['hours']?></td>
                                <td><?=$settings[$record['is_exempt']]?></td>
                                <td><?=$expiresDate?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
        $(".btn-edit").click(function(){
            $('.accountInputs').prop('disabled', false);
            $('.accountInputs').prop('disabled', false).trigger("chosen:updated");
            
            $(".btn-edit-set").hide();
            $(".btn-cancel-set").show();
            
        });
        
        $(".btn-cancel").click(function(){
            $('.accountInputs').prop('disabled', true);
            $('.accountInputs').prop('disabled', true).trigger("chosen:updated");
            
            $(".btn-edit-set").show();
            $(".btn-cancel-set").hide();
        });
        
        $('#UserFirstName').editable({
            type: 'text',
            name: 'name'
        });
    });
</script>