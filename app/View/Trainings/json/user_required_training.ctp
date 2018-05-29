<table class="table table-striped table-condensed" id="trainingTable">
    	<thead>
        	<tr class="tr-heading">
            	<th class="col-md-6">Training</th>
                <th class="col-md-3">Status</th>
                <th class="col-md-3">Expires Date</th>
            </tr>
        </thead>

        <tbody>
        	<?php
            #pr($training );
            #pr($data);
            #exit;
            foreach($training as $t){
            	
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
                ?>
                <tr>
                    <td>
                        <?php
                        if(!empty($t['TrainingFile'])){
                        	echo $this->Html->link(
                        		$t['TrainingRecord']['name'] .' <i class="fa fa-fw fa-play-circle fa-lg"></i>',
                            	array('controller'=>'Trainings', 'action'=>'play', $t['TrainingFile'][0]['training_id']),
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