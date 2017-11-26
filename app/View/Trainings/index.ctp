<?php
    #pr($trainings);
    #exit;
?>
<div class="training index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Account Training:</h6>
            <h3 class="dashhead-title"><i class="fa fa-book fa-fw"></i> Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Trainings/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Trainings/menu' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php #echo $this->element( 'Trainings/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>

    <table class="table table-striped table-condensed table-hover" id="trainingTable">
        <thead>
            <tr>
                <th class="col-sm-3">Name</th>
                <th class="col-sm-4">Description</th>
                <th class="col-sm-1 text-center">Required</th>
                <th class="col-sm-1 text-center">Mandatory</th>
                <th class="col-sm-2 text-center">Mandatory For</th>
                <th class="col-sm-1 text-center">Print Roster</th>
            </tr>
        </thead>

        <tbody>
            <?php
            #pr($trainings);
            #exit;
            $c=0;
            foreach($trainings as $key=>$trn){
				#pr($trn);
                $required = (isset($trn['TrainingMembership']['is_required']) && $trn['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                $manditory = (isset($trn['TrainingMembership']['is_manditory']) && $trn['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                ?>
                <tr>
                    <td>
						<?php
                        echo $this->Html->link(
                        	$trn['Training']['name'],
                            array('controller'=>'Trainings', 'action'=>'details', $trn['Training']['id']),
                            array('escape'=>false)
                        );
                        ?>
					</td>
                    <td><?=$trn['Training']['description']?></td>
                    <td class="text-center"><?=$required?></td>
                    <td class="text-center"><?=$manditory?></td>
                    <td>
						<?php
						if(!empty($trn['ReqDept'])){
							?>
							<dl>
  								<dt>Departments:</dt>
                                <?php
								foreach($trn['ReqDept'] as $b){
									?>
									<dd><?=$b['name']?></dd>
									<?php
								}
								?>
							</dl>
							<?php
						}

						if(!empty($trn['ReqUser'])){
							?>
							<dl>
								<dt>Users:</dt>
								<?php
								foreach($trn['ReqUser'] as $b){
									?>
									<dd><?=$b['first_name']?> <?=$b['last_name']?></dd>
									<?php
								}
								?>
							</dl>
							<?php
						}

						if((isset($trn['TrainingMembership']['is_manditory']) && $trn['TrainingMembership']['is_manditory'] == 1) && empty($trn['ReqDept']) && empty($trn['ReqUser'])){
							?>
							<dl>
								<dt>Everyone</dt>
							</dl>
							<?php
						}
						?>
					</td>
					<td>
						<?php
	                    echo $this->Html->link(
	                    	'<i class="fa fa-print fa-fw"></i>',
	                        array('controller'=>'Trainings', 'action'=>'roster', $trn['Training']['id'], 'ext'=>'pdf'),
	                        array('escape'=>false)
	                    );
	                	?>	
					</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>


</div>

<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $("#myModalBig").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $(".modal-wide").on("show.bs.modal", function() {
            var height = $(window).height() - 200;
            $(this).find(".modal-body").css("max-height", height);
        });

        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $(".chzn-select-noDeselect").chosen({
            allow_single_deselect: false
        });
     });
</script>