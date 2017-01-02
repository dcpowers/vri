<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'add'),
        'role'=>'form',
		'type'=>'file',
        #'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

	if(AuthComponent::user('Role.permission_level') >= 60 ){
		$divWidthOne = 8;
	}else{
		$divWidthOne = 12;
	}
?>
<style type="text/css">
	.chosen-container .chosen-choices .search-field:only-child input {
    width: 100% !important;
}
</style>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Add Training:'); ?></h2>
</div>

<div class="modal-body">
	<div class="tabbable" style="margin-top: 20px;">
    	<ul class="nav nav-tabs">
        	<li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-book fa-fw" aria-hidden="true"></i> Training Information</a></li>
            <?php
			if(AuthComponent::user('Role.permission_level') >= 60 ){
				?>
				<li><a href="#admin" data-toggle="tab"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i> Administrative Settings</a></li>
            	<!--<li><a href="#use" data-toggle="tab"><i class="fa fa-flag fa-fw" aria-hidden="true"></i> Account Use</a></li>-->
				<?php
			}
			?>
        </ul>

        <div class="tab-content">
        	<div class="tab-pane fade active in" id="info">
            	<div class="row">
					<div class="col-md-8">
						<div class="form-group">
            				<label for="name" class="control-label">Name:</label>
			                <?php
							echo $this->Form->input('name', array (
								'type'=>'text'
							));
							?>
    					</div>
						<div class="form-group">
    						<label for="name" class="control-label">Description:</label>
					        <?php
							echo $this->Form->input('description', array (
								'type'=>'textarea'
							));
							?>
					    </div>

					</div>

					<div class="col-md-4">
						<div class="well">
							<div class="form-group">
										<label for="name" class="control-label">Status:</label><br />
										<label class="radio-inline">
											<?php
											echo $this->Form->radio('is_active',
												array (1=>'Active', 0=>'Inactive'),
												array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
											);
											?>
										</label>
									</div>
									<div class="form-group">
										<label for="name" class="control-label">Make Avaiable To Others:</label><br />
										<label class="radio-inline">
											<?php
											echo $this->Form->radio('is_public',
												array (1=>'Make Public', 0=>'Make Private'),
												array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
											);
											?>
										</label>
									</div>

						</div>
					</div>
				</div>


				<div class="form-group">
    				<label for="name" class="control-label">Files:</label>
			        <?php
					echo $this->Form->input('files.', array(
						'type' => 'file',
						'multiple'=>true
					));
					?>
					<small>Videos MUST BE in MP4 format!</small>
			    </div>

			</div>
			<?php
			if(AuthComponent::user('Role.permission_level') >= 60 ){
				?>
				<div class="tab-pane fade in" id="admin">

							<div class="row">
            					<div class="col-md-8">
									<div class="form-group">
                        						<div class="checkbox">
                                					<label>
					                                    <?php
					                                    echo $this->Form->checkbox('TrainingMembership.is_manditory', array());
					                                    ?>
					                                    Is Mandatory Training For Everyone
					                                </label>
					                        </div>
										</div>
									<div class="form-group">
                        				<label class="control-label" for="name">Is Manadatory For These Account(s):</label>
			                            <?php
			                            echo $this->Form->input('TrainingMembership.account_id', array (
			                                'options'=>$accts,
											'type'=>'select',
			                                'empty'=>true,
			                                'multiple'=>true,
			                                'class'=>'form-select chzn-select',
			                                'data-placeholder'=>'Select Accounts(s)'
			                            ));
			                            ?>
			                            <small>Leave Empty If Training Is For Everyone</small>
                					</div>

									<div class="form-group">
                        				<label class="control-label" for="name">Is Manadatory For These Department(s):</label>
			                            <?php
			                            echo $this->Form->input('TrainingMembership.department_id', array (
			                                'options'=>$depts,
											'type'=>'select',
			                                'empty'=>true,
			                                'multiple'=>true,
			                                'class'=>'form-select chzn-select',
			                                'data-placeholder'=>'Select Department(s)'
			                            ));
			                            ?>
			                            <small>Leave Empty If Training Is For Everyone</small>
                					</div>

									<div class="form-group">
                        				<label class="control-label" for="name">Is Manadatory For These User(s):</label>
			                            <?php
			                            echo $this->Form->input('TrainingMembership.user_id', array (
			                                'options'=>$users,
											'type'=>'select',
			                                'empty'=>true,
			                                'multiple'=>true,
			                                'class'=>'form-select chzn-select',
			                                'data-placeholder'=>'Select User(s)'
			                            ));
			                            ?>
			                            <small>Leave Empty If Training Is For Everyone Or You Have Selected Department(s) Above</small>
                					</div>
								</div>

								<div class="col-md-4">
									<div class="well">


                						<div class="form-group">
					                        <label class="control-label" for="name">Renewal In Months:</label>
					                        <?php
					                        for($i=0; $i<=48; $i++){
                        						$renewal[$i] = $i;
					                        }

											echo $this->Form->input('TrainingMembership.renewal', array (
                        						'options'=>$renewal,
					                            'type'=>'select',
					                            'value'=>12,
					                            'class'=>'form-select chzn-select',
					                        ));
					                        ?><label> Months </label><br />
					                        <small>Use "0" If Only Needed Once. </small>
	                					</div>
									</div>
								</div>
							</div>

					<div class="clearFix">&nbsp;</div>
		        </div>

				<div class="tab-pane fade in" id="use">
					<table class="table table-striped table-condensed" id="trainingTable">
        						<thead>
            						<tr class="tr-heading">
                						<th class="col-md-8">Account(s)</th>
					                    <th class="text-center col-md-2">Required</th>
					                    <th class="text-center col-md-2">Mandatory</th>
					                </tr>
					            </thead>

					            <tbody>
            						<?php
									$acctFound = 0;
					                foreach($trnAccount as $file){
										#pr($file);
										if(!empty($file['ReqAcct']['name'])){
											$acctFound = 1;

											$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
											$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                							?>
						                    <tr>
                    							<td><?=$file['ReqAcct']['name']?></td>
						                        <td class="text-center"><?=$required?></td>
						                        <td class="text-center"><?=$man?></td>
						                    </tr>
						                    <?php
										}
					                }

									if($acctFound == 0){
										?>
										<tr>
											<td colspan="3" class="text-center">No Records Found</td>
										</tr>
										<?php
									}
					                ?>
					            </tbody>
					        </table>
							<table class="table table-striped table-condensed" id="trainingTable">
        						<thead>
            						<tr class="tr-heading">
                						<th class="col-md-8">Department(s)</th>
					                    <th class="text-center col-md-2">Required</th>
					                    <th class="text-center col-md-2">Mandatory</th>
					                </tr>
					            </thead>

					            <tbody>
            						<?php
									$acctFound = 0;
					                foreach($trnAccount as $file){
										#pr($file);
										if(!empty($file['ReqDept']['name'])){
											$acctFound = 1;

											$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
											$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                							?>
						                    <tr>
                    							<td><?=$file['ReqDept']['name']?></td>
						                        <td class="text-center"><?=$required?></td>
						                        <td class="text-center"><?=$man?></td>
						                    </tr>
						                    <?php
										}
					                }

									if($acctFound == 0){
										?>
										<tr>
											<td colspan="3" class="text-center">No Records Found</td>
										</tr>
										<?php
									}
					                ?>
					            </tbody>
					        </table>
							<table class="table table-striped table-condensed" id="trainingTable">
        						<thead>
            						<tr class="tr-heading">
                						<th class="col-md-8">User(s)</th>
					                    <th class="text-center col-md-2">Required</th>
					                    <th class="text-center col-md-2">Mandatory</th>
					                </tr>
					            </thead>

					            <tbody>
            						<?php
									$acctFound = 0;
					                foreach($trnAccount as $file){
										#pr($file);
										if(!empty($file['ReqUser']['first_name'])){
											$acctFound = 1;

											$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
											$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
                							?>
						                    <tr>
                    							<td><?=$file['ReqUser']['first_name']?> <?=$file['ReqUser']['last_name']?></td>
						                        <td class="text-center"><?=$required?></td>
						                        <td class="text-center"><?=$man?></td>
						                    </tr>
						                    <?php
										}
					                }

									if($acctFound == 0){
										?>
										<tr>
											<td colspan="3" class="text-center">No Records Found</td>
										</tr>
										<?php
									}
					                ?>
					            </tbody>
					        </table>

				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

    echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    );
    ?>
</div>
<?php echo $this->Form->end();?>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true,

        });
    });
</script>