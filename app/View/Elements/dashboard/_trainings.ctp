<?php
	$data = $this->requestAction('/Trainings/userRequiredTraining/');
    #pr($data);
    #exit;
?>

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
            foreach($data as $trn){
            	#pr($trn);
            	#exit;
                $status = null;
                #pr($records[$training['Training']['id']]);
                $status = 'Current';
                $label = 'label label-success';
				$expires = null;
				
				if(empty($trn['Training']['TrainingRecord'])){
                	$status = 'No Record Found';
                    $label = 'label label-danger';
                    
                }else{
                	#pr($trn);
                	#exit;
                	if(!is_null($trn['Training']['TrainingRecord'][0]['expires_on'])){
						$expires = date('F d,Y', strtotime($trn['Training']['TrainingRecord'][0]['expires_on']));
					}
                	
                	if(!is_null($trn['Training']['TrainingRecord'][0]['started_on']) && is_null($trn['Training']['TrainingRecord'][0]['completed_on'])){
						$status = 'In Progress';
	                    $label = 'label label-primary';
	                }
	                
	                if(strtotime($trn['Training']['TrainingRecord'][0]['expires_on']) < strtotime('now')){
                        $status = 'Expired';
                    	$label = 'label label-danger';
                    }

                    if(strtotime($trn['Training']['TrainingRecord'][0]['expires_on']) >= strtotime('now') && strtotime($trn['Training']['TrainingRecord'][0]['expires_on']) <= strtotime('+30 days') ){
                        $status = 'Expiring';
                    	$label = 'label label-warning';
                    }
                }
				///////////////////////////
                
                ?>
                <tr>
                	<td>
                    	<?php
						if(!empty($trn['Training']['TrainingFile'])){
                        	echo $this->Html->link(
                        		$trn['Training']['name'] .' <i class="fa fa-fw fa-play-circle fa-lg"></i>',
                            	array('controller'=>'Trainings', 'action'=>'play', $trn['Training']['id']),
                            	array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                        	);
						}else{
							echo $trn['Training']['name'];
						}
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

    <div class="box-footer" style="border-bottom: 1px solid #C0C0C0;"></div>
</div>