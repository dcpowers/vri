<div class="accident view bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Accident Details: <?=$accident['User']['first_name']?> <?=$accident['User']['last_name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Accidents</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/menu' );?>
        </div>
    </div>
	<ul class="list-inline pull-right">
		<li>
			<?php
			echo $this->Html->link(
				'Request Employee Statement',
				array('controller'=>'Accidents', 'action'=>'sendRequest', $accident['Accident']['id'], 1),
				array('escape'=>false, 'class'=>'btn btn-xs btn-primary', 'data-toggle'=>'modal', 'data-target'=>'#myModal')
			);
			?>
		</li>

		<li>
			<?php
			echo $this->Html->link(
				'Request Supervisor Statement',
				array('controller'=>'Accidents', 'action'=>'sendRequest', $accident['Accident']['id'], 2),
				array('escape'=>false, 'class'=>'btn btn-xs btn-info', 'data-toggle'=>'modal', 'data-target'=>'#myModal')
			);
			?>
		</li>
	</ul>
	<div class="clearfix"></div>
	<div class="row clearfix">
        <div class="col-sm-3">
            <div class="box box-success">

                <div class="box-body">
                    <?php
					$image = (file_exists('img/profiles/'.$this->request->data['User']['id'].'.png')) ? '/img/profiles/'.$this->request->data['User']['id'].'.png' : '/img/profiles/noImage.png' ;
                    echo $this->Html->image($image, array('class'=>'img-responsive img-thumbnail'));
                    ?>
                </div>
            </div>
            <div class="box box-success">
                <div class="box-body">
					<?php #pr($accident); ?>
                    <h3><?=$accident['User']['first_name']?> <?=$accident['User']['last_name']?></h3>

                    <dl>
                        <dt>Account:</dt>
                        <dd><?=$accident['Account']['name']?></dd>
                    </dl>

					<dl>
                        <dt>Department:</dt>
                        <dd><?=$accident['Dept']['name']?></dd>
                    </dl>

					<dl>
                        <dt>Date of Hire:</dt>
                        <dd><?php echo date('F d, Y', strtotime($accident['User']['doh'])); ?></dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="box box-success">
				<div class="box-header">
					<div class="box-tools pull-right">
			            <?php
			            echo $this->Html->link(
			                '<i class="fa fa-plus fa-fw"></i> <span>Add</span>',
			                array('controller'=>'Improvements', 'action'=>'add'),
			                array('escape'=>false,'data-toggle'=>'modal', 'data-target'=>'#myModal', )
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
                <div class="box-body">
					<?php #pr($setting); ?>
                    <div class="row">
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-6">
									<dl>
				                        <dt>Date of Accident:</dt>
				                        <dd><?php echo date('F d, Y', strtotime($accident['Accident']['date'])); ?></dd>
				                    </dl>
								</div>

								<div class="col-md-6">
									<dl>
				                        <dt>Area(s) of Injury:</dt>
				                        <dd>
											<?php
											if(!empty($accident['AccidentArea'])){
												?>
												<ul class="list-unstyled">
												<?php
												foreach($accident['AccidentArea'] as $a){
													?>
													<li><?=$a['AccidentAreaLov']['name']?></li>
													<?php
												}
												?>
												</ul>
												<?php
											}
											?>
										</dd>
				                    </dl>
								</div>
							</div>
							<dl>
		                        <dt>Description:</dt>
		                        <dd><?=$accident['Accident']['description']?></dd>
		                    </dl>
						</div>

                        <div class="col-md-4">
							<div class="well">
								<dl>
			                        <dt>Reported By:</dt>
			                        <dd><?=$accident['CreatedBy']['first_name']?> <?=$accident['CreatedBy']['last_name']?></dd>
			                    </dl>

								<dl>
			                        <dt>Last Update:</dt>
			                        <dd><?php echo date('F d, Y', strtotime($accident['Accident']['modified'])); ?></dd>
			                        <dd><?=$accident['ChangeBy']['first_name']?> <?=$accident['ChangeBy']['last_name']?></dd>
			                    </dl>

								<dl>
			                        <dt>Reported To Workmans Comp?</dt>
			                        <dd><?=$setting[$accident['Accident']['is_insurance']]?></dd>
			                    </dl>

								<dl>
			                        <dt>Reported To OSHA?</dt>
			                        <dd><?=$setting[$accident['Accident']['is_osha']]?></dd>
			                    </dl>
							</div>
						</div>
					</div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Accident Costs</h3>
					<div class="box-tools pull-right">
			            <?php
			            echo $this->Html->link(
			                '<i class="fa fa-plus fa-fw"></i> <span>Add</span>',
			                array('controller'=>'Accidents', 'action'=>'cost', $accident['Accident']['id']),
			                array('escape'=>false,'data-toggle'=>'modal', 'data-target'=>'#myModal', )
			            );
                        ?>
			        </div>
                </div>

                    <table class="table table-striped table-condensed" id="trainingTable">
                        <thead>
                            <tr class="tr-heading">
                                <th>Vri Cost</th>
                                <th>Insurance Cost</th>
                                <th>Type</th>
                                <th class="text-center">Restricted Days</th>
                                <th>Added By</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
							foreach($accident['AccidentCost'] as $file){
								$name = (empty($file['AccidentCostLov']['name'])) ? null : $file['AccidentCostLov']['name'] ;
								?>
                                <tr>
                                    <td><?php echo number_format($file['vri_cost'], 2,'.', ','); ?></td>
                                    <td><?=$file['insurance_cost']?></td>
                                    <td><?=$name?></td>
                                    <td class="text-center"><?=$file['num_days']?></td>
                                    <td><?=$file['CreatedBy']['first_name']?> <?=$file['CreatedBy']['last_name']?></td>
                                    <td><?php echo date('F d, Y', strtotime($file['created'])); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

            </div>
        </div>
    </div>

	<div class="box box-success">
            	<div class="box-header">
                    <h3 class="box-title">Accident Files</h3>
					<div class="box-tools pull-right">
			            <?php
			            echo $this->Html->link(
			                '<i class="fa fa-upload fa-fw"></i> <span>Upload</span>',
			                array('controller'=>'Accidents', 'action'=>'files', $accident['Accident']['id']),
			                array('escape'=>false,'data-toggle'=>'modal', 'data-target'=>'#myModal', )
			            );
                        ?>
			        </div>
                </div>
                    <table class="table table-striped table-condensed" id="trainingTable">
                        <thead>
                            <tr class="tr-heading">
                                <th>Name</th>
                                <th>Description</th>
                                <th>Added By</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach($accident['AccidentFile'] as $f){
                                ?>
                                <tr>
                                    <td>
										<?php
										if($f['is_active'] == 2){
											echo $this->Html->link(
												$f['name'],
                                    			array('controller'=>'accidents', 'action'=>'download', $f['id']),
                                    			array('escape'=>false)
                                			);
										}else{
											echo $f['name'];
										}
										?>
									</td>
                                    <td><?=$f['description']?></td>
                                    <td><?=$f['CreatedBy']['first_name']?> <?=$f['CreatedBy']['last_name']?></td>
                                    <td><?php echo date('M d, Y', strtotime($f['date'])); ?></td>
									<td>
										<?php
										if($f['is_active'] == 2){
											echo $this->Html->link(
												'<i class="fa fa-download fa-fw fa-lg"></i>',
                                    			array('controller'=>'accidents', 'action'=>'download', $f['id']),
                                    			array('escape'=>false)
                                			);
										}else{
											?>
											<span class="fa-stack ">
										  		<i class="fa fa-download fa-stack-1x"></i>
										  		<i class="fa fa-ban fa-stack-2x text-danger"></i>
											</span>
											<?php
										}
										?>
									</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

            </div>
</div>


<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>