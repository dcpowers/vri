<?php
    $data = $this->requestAction('/Users/view/'.AuthComponent::user('id'));
    #pr($data);
?>

<div class="box box-warning" style="border-left: 1px solid #F39C12; border-right: 1px solid #F39C12;">
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
            foreach($data[0] as $training){
            	$status = null;
                #pr($records[$training['Training']['id']]);
                $status = 'Current';
                $label = 'label label-success';
				$keep = 0;

                if($data[1][$training['Training']['id']]['TrainingRecord']['in_progress'] == 1){
                	$status = 'In Progress';
                    $label = 'label label-primary';
					$keep = 1;
                }

                if($data[1][$training['Training']['id']]['TrainingRecord']['expired'] == 1){
                	$status = 'Expired';
                    $label = 'label label-danger';
					$keep = 1;
                }

                if($data[1][$training['Training']['id']]['TrainingRecord']['expiring'] == 1){
                	$status = 'Expiring';
                    $label = 'label label-warning';
					$keep = 1;
                }

                if($data[1][$training['Training']['id']]['TrainingRecord']['no_record'] == 1){
                	$status = 'No Record Found';
                    $label = 'label label-danger';
					$keep = 1;
                }

                $expires = (!empty($data[1][$training['Training']['id']]['TrainingRecord']['expires_on'])) ? date('F d, Y', strtotime($data[1][$training['Training']['id']]['TrainingRecord']['expires_on'])) : '--' ;

				if($keep == 0){
					break;
				}
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