<?php
	#$data = $this->requestAction('/Trainings/userRequiredTraining/');
    #pr($data);
    #exit;
?>
<style type="text/css">
	#LoadingDiv{
        margin:0px 0px 0px 0px;
        position: relative;
        min-height: 100%;
        height: 100vh;
        z-index:9999;
        padding-top: 200px;
        padding-left: 45%;
        width: 100%;
        clear:none;
        background-color: #fff;
  		opacity: 0.5;
        /*background:url(/img/transbg.png);
        background-color:#666666;
        border:1px solid #000000;*/
    }
</style>
<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">My Required Training</h3>
        <div class="box-tools pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-eye fa-fw"></i> <span>View All My Training</span>',
                array('controller'=>'Users', 'action'=>'profile', 'records'),
                array('escape'=>false)
            );

            ?>
        </div>
    </div>
    <div id="LoadingDiv" style="display:none;">
    	<?php echo $this->Html->image('ajax-loader-red.gif'); ?>
	</div>
    <table class="table table-striped table-condensed" id="trainingTable">
    	<thead>
        	<tr class="tr-heading">
            	<th class="col-md-6">Training</th>
                <th class="col-md-3">Status</th>
                <th class="col-md-3">Expires Date</th>
            </tr>
        </thead>
    </table>

    <div class="box-footer" style="border-bottom: 1px solid #C0C0C0;"></div>
</div>

<!--
<table class="table table-striped table-condensed" id="trainingTable">
    	<thead>
        	<tr class="tr-heading">
            	<th class="col-md-6">Training</th>
                <th>Status</th>
                <th>Expires Date</th>
                <th>Required</th>
            
            </tr>
        </thead>

        <tbody>
        	<?php
            #pr($requiredTraining );
            #pr($data);
            #exit;
            foreach($data as $t){
            	$status = 'Current';
                $label = 'label label-success';
				$showRest = 1;

                if($t['TrainingRecord']['in_progress'] == 1){
                    $status = 'In Progress';
                    $label = 'label label-primary';
				}

                if($t['TrainingRecord']['expired'] == 1){
                    $status = 'Expired';
                    $label = 'label label-danger';
				}

                if($t['TrainingRecord']['expiring'] == 1){
                    $status = 'Expiring';
                    $label = 'label label-warning';
				}

                if($t['TrainingRecord']['no_record'] == 1){
                    $status = 'No Record Found';
                    $label = 'label label-danger';
					$showRest = 0;
                }

                $expires = (!empty($t['TrainingRecord']['expires_on'])) ? date('F d, Y', strtotime($t['TrainingRecord']['expires_on'])) : '--' ;
                $required = ($t['TrainingRecord']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                ?>
                <tr>
                    <td>
                        <?php
						if(!empty($t['TrainingFile'])){
                        	echo $this->Html->link(
                        		$trn['Training']['name'] .' <i class="fa fa-fw fa-play-circle fa-lg"></i>',
                            	array('controller'=>'Trainings', 'action'=>'play', $trn['Training']['id']),
                            	array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                        	);
						}else{
							echo $t['TrainingRecord']['name'];
						}
                        ?>
                    </td>
                    <td>
                        <span class="<?=$label?>"><?=$status?></span>
                    </td>
                    <td><?=$expires?></td>
                    <td class="text-center"><?=$required?></td>
                    
                </tr>
                
                <?php
            }

            if(empty($data)){
            	?>
                <tr>
                	<td colspan="4" class="text-center">No Records Found</td>
                </tr>
            	<?php
            }
            ?>
        </tbody>
    </table>
    -->