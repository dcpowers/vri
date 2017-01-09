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
	<div class="row">
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
                </div>

                    <table class="table table-striped table-condensed" id="trainingTable">
                        <thead>
                            <tr class="tr-heading">
                                <th>Employee</th>
                                <th>Cost</th>
                                <th>Added By</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach($accident['AccidentCost'] as $file){

                                ?>
                                <tr>
                                    <td><?=$file['human_name']?></td>
                                    <td><?=$file['file_type']?></td>
                                    <td><?=$fileSize?></td>
                                    <td><?=$file['runtime']?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

            </div>

            <div class="box box-success">
            	<div class="box-header">
                    <h3 class="box-title">Accident Files</h3>
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
                                    <td><?=$f['name']?></td>
                                    <td><?=$f['description']?></td>
                                    <td><?=$f['created_by']?></td>
                                    <td><?php echo date('F d, Y', strtotime($f['created_date'])); ?></td>
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
    });
</script>