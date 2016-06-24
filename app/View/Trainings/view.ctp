<?php
    #pr($trnCat);
    #exit; 
?>
<div class="training view bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">View/Edit Training: <?=$training['Training']['name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
        </div>
    </div>
    
    <?php 
    echo $this->Form->create('Training', array(
        'url'=>array('controller'=>'Trainings', 'action'=>'edit'),
        'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
                    
    echo $this->Form->hidden('id', array('value'=>$this->request->data['Account']['id'])); 
    ?> 
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab">Information</a></li>
            <li><a href="#records" data-toggle="tab">Records</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active in" id="info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Name:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"><?=$training['Training']['name']?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Status:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"><?=$training['Status']['name']?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Last Update:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"><?php echo date('F d, Y', strtotime($training['Training']['updated_date'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"><?=$training['Training']['description']?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Video</th>
                                    <th>Poster</th>
                                    <th>Runtime</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$training['Training']['video']?></td>
                                    <td><?=$training['Training']['video_poster']?></td>
                                    <td><?=$training['Training']['runtime']?></td>
                                    <td><?php echo date('F d, Y', strtotime($training['Training']['video_date'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Power Point</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$training['Training']['power_point']?></td>
                                    <td><?php echo date('F d, Y', strtotime($training['Training']['power_point_date'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Leader Files</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$training['Training']['leader_files']?></td>
                                    <td><?php echo date('F d, Y', strtotime($training['Training']['leader_files_date']))?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="records">
            </div>
        </div>
    </div>
    <?php echo $this->Form->end();?> 
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