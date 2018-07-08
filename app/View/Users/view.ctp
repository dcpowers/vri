    <?php
    $doh = (!empty($this->request->data['User']['doh'])) ? date('F d, Y', strtotime($this->request->data['User']['doh'])) : 'N/A' ;
    $dob = (!empty($this->request->data['User']['dob'])) ? date('F d, Y', strtotime($this->request->data['User']['dob'])) : 'N/A' ;

	$jobClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'employees') ? 'active' : null;
    $recordsClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'records') ? 'active' : null;
    $assetsClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'assets') ? 'active' : null;
    $testingClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'testing') ? 'active' : null;
    $awardClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'award') ? 'active' : null;
    $safetyClass = (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'safety') ? 'active' : null;

    $personalClass = (empty($this->params['pass'][1]) || $this->params['pass'][1] == 'info') ? 'active' : null;

    $this->Html->css('bootstrap-fileupload.min.css', '', array('block' => 'csslib') );
    $this->Html->script('bootstrap-fileupload.js', array('block' => 'scriptsBottom'));

    $today = date('Y-m-d h:i a');

    $m = date('m', strtotime('now'));
    $d = date('d', strtotime('now'));
    $y = date('Y', strtotime('now'));
    $h = date('h', strtotime('now'));
    $i = date('i', strtotime('now'));

    echo $this->Form->create('User', array(
        'type'=>'file',
        'url'=>array('controller'=>'Users', 'action'=>'view'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

    echo $this->Form->hidden('id', array('value'=>$this->request->data['User']['id']));
    echo $this->Form->hidden('AccountUser.account_id', array('value'=>$this->request->data['AccountUser'][0]['account_id']));
	
	#pr($this->request->data);
	#pr($user);
    #pr($requiredTraining);
    #pr($allRecords);
    #exit;    
    ?>
    <style type="text/css">
        .headerDiv{
            background-color: #ff6700 ;
            min-height: 100px;
            position: absolute;
            left: 0px;
            right: 0px;
            min-width: 100% !important;
        }

        .headerContent{
            color: #ffffff;
        }

        .headerContent .fileupload {
            color: #000000;
        }

        h2.title{
            font-family: 'Sonsie One', cursive;
        }

        .fileupload-new{ color: black; }
        .fileupload-exists{ color: black; }

        .fileupload-exists a{ padding: 0px; }
        
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
    
    

    <div style="margin-top: -7px">
        <div class="headerDiv">
        </div>
        <div id="employeeList">
            <div class="row">
                <div class="col-md-2 headerContent bg-white">
					<div class="form-group">
                        <div class="fileupload fileupload-new " data-provides="fileupload">
                            <div class="fileupload-new thumbnail">
                                <div class="effect3 thumbnail">
                                <?php
                                #pr($this->request->data);
                                #exit;
                                clearstatcache();
                                $image = (file_exists('img/profiles/'.$this->request->data['User']['id'].'.png')) ? '/img/profiles/'.$this->request->data['User']['id'].'.png' : '/img/profiles/noImage.png' ;
                                echo $this->Html->image($image, array('class'=>'img-responsive'));
                                #echo $this->Html->image('/img/profiles/'.$this->request->data['User']['id'].'.png', array('class'=>'img-responsive'));
                                ?>
                                </div>
                            </div>

                            <div class="effect3 thumbnail">
                                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                            </div>


                            <div class="text-center">
                                <span class="btn btn-file">
                                    <span class="text-danger"><small>( 2mb Limit )</small></span><br />
                                    <span class="fileupload-new">Select Image</span>
                                    <span class="fileupload-exists">Select New Image</span>
                                    <?php echo $this->Form->file('file'); ?>
                                </span>

                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>

                            </div>
                        </div>

                    </div>
					<div style="color: #000;">
                    	<div class="form-group">
                        	<label class="control-label">Status:</label>
							<?php
					        echo $this->Form->input( 'is_active', array(
					        	'options'=>$status,
					            'class'=>'chzn-select form-control',
					            'between' => '<div class="input-group">',
					            'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
					        ));
					        ?>
                        </div>
                        <div class="form-group">
                        	<label class="control-label">Employment Type:</label>
							<?php
					        echo $this->Form->input( 'pay_status', array(
					        	'options'=>$empStatus,
					            'class'=>'chzn-select form-control',
					            'between' => '<div class="input-group">',
					            'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
					        ));
					        ?>
                        </div>
                        <div class="form-group">
                        	<label class="control-label">Permission (Role):</label>
                            <?php
					        echo $this->Form->input( 'auth_role_id', array(
					        	'options'=>$roles,
					            'class'=>'chzn-select form-control',
					            'value'=>$this->request->data['User']['auth_role_id'],
					            'between' => '<div class="input-group">',
					            'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
					        ));
					        ?>
                        </div>

                        <div class="form-group">
                        	<label class="control-label">Date Of Hire:</label>
                            <?php
			                echo $this->Form->input( 'doh', array(
			                	'type'=>'text',
			                    'required'=>false,
			                    'label'=>false,
			                    'value'=>date('m/d/Y', strtotime($this->request->data['User']['doh'])),
			                    'class'=>'datepicker form-control'
			                ));
			                ?>
                        </div>

						<div class="form-group" >
                        	<label class="sr-only control-label">Eligible Bingo:</label>
                            <div class="checkbox">
			                	<label> <?php echo $this->Form->checkbox('is_bingo', array()); ?>Plays Bingo</label>
							</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12 headerContent">
                            <h2 class="title">
                                <?=$this->request->data['User']['first_name']?> <?=$this->request->data['User']['last_name']?>
                            </h2>
                        </div>
                        <div class="col-md-12">

                            <div class="tabbable bg-white" style="margin-top: 20px;">
                                <ul class="nav nav-tabs">
                                    <li class="<?=$personalClass?>"><a href="#info" data-toggle="tab"><i class="fa fa-address-card-o fa-fw" aria-hidden="true"></i> Personal</a></li>
                                    <li class="<?=$recordsClass?>"><a href="#records" data-toggle="tab"><i class="fa fa-book fa-fw" aria-hidden="true"></i> Training</a></li>
									<li class="<?=$testingClass?>"><a href="#testing" data-toggle="tab"><i class="fa fa-clipboard fa-fw" aria-hidden="true"></i> Testing</a></li>
                                    <!--<li class="<?=$assetsClass?>"><a href="#assets" data-toggle="tab"><i class="fa fa-car fa-fw" aria-hidden="true"></i> Assets</a></li>-->
                                    <li class="<?=$awardClass?>"><a href="#award" data-toggle="tab"><i class="fa fa-trophy fa-fw" aria-hidden="true"></i> Awards</a></li>
                                    <li class="<?=$safetyClass?>"><a href="#safety" data-toggle="tab"><i class="fa fa-ambulance fa-fw" aria-hidden="true"></i> Safety</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade <?=$personalClass?> in" id="info">
                                        <?php echo $this->Flash->render('profile') ?>

                                        <h3><i class="fa fa-address-card-o fa-fw" aria-hidden="true"></i> Personal</h3>
                                        <hr/>
                                        <label class="control-label text-danger" for="firstname">Name:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php
                                                    echo $this->Form->input('first_name', array (
                                                        'type'=>'text',
                                                        'id'=> 'first_name',
                                                        'placeholder'=>'Firstname',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?php
                                                    echo $this->Form->input('last_name', array (
                                                        'type'=>'text',
                                                        'id'=> 'last_name',
                                                        'placeholder'=>'Lastname',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Username:</label>
                                                    <?php
                                                    echo $this->Form->input('username', array (
                                                        'type'=>'text',
                                                        'id'=> 'user_name',
                                                        'placeholder'=>'Username',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">E-Mail Address:</label>
                                                    <?php
                                                    echo $this->Form->input('email', array (
                                                        'type'=>'text',
                                                        'id'=> 'email',
                                                        'placeholder'=>'E-Mail Address',
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>


                                        <hr style="border: 1px #C0C0C0 solid; "/>
                                        <h3><i class="fa fa-suitcase fa-fw" aria-hidden="true"></i> Job</h3>
                                        <hr/>

                                                <div class="form-group">
                                                    <label class="control-label">Account:</label>
													<?php
													if(AuthComponent::user('Role.permission_level') >= 50){
														$this->request->data['AccountUser']['account_id'] = Set::extract( $this->request->data['AccountUser'], '/account_id' );

						                                echo $this->Form->input( 'AccountUser.account_id', array(
						                                	'options'=>$accounts,
						                                    'class'=>'chzn-select form-control',
						                                    'multiple'=>false,
						                                    'between' => '<div class="input-group">',
						                                    'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                ));
						                            }else{
														?>
														<p class="form-control-static"><?= $this->request->data['AccountUser'][0]['Account']['name']; ?></p>
														<?php
													}
													?>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
														<div class="form-group">
                                                            <label class="control-label">Department:</label>
															<?php
						                                    $this->request->data['DepartmentUser']['department_id'] = Set::extract( $this->request->data['DepartmentUser'], '/department_id' );

						                                    echo $this->Form->input( 'DepartmentUser.department_id', array(
						                                        'options'=>$departments,
						                                        'class'=>'chzn-select form-control',
						                                        'multiple'=>false,
						                                        'empty'=>true,
						                                        'between' => '<div class="input-group">',
						                                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                    ));
						                                    ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Supervisor:</label>
															<?php
						                                    echo $this->Form->input( 'supervisor_id', array(
						                                        'options'=>$pickListByAccount,
						                                        'class'=>'chzn-select form-control',
						                                        'data-placeholder'=>'Select An Account',
						                                        'value'=>$this->request->data['User']['supervisor_id'],
						                                        'between' => '<div class="input-group">',
						                                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                    ));
						                                    ?>
                                                        </div>
                                                    </div>
                                                </div>


                                        <hr style="border: 1px #C0C0C0 solid; "/>
                                        <h3><i class="fa fa-gavel fa-fw" aria-hidden="true"></i> EEOC <small>( Equal Employment Opportunity Commission )</small></h3>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="control-label">Birthday:</label>
                                                    <?php
                                                    echo $this->Form->input( 'dob', array(
                                                        'type'=>'text',
                                                        'required'=>false,
                                                        'label'=>false,
                                                        'value'=>date('m/d/Y', strtotime($this->request->data['User']['dob'])),
                                                        'class'=>'datepicker form-control'
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-8"></div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->Form->button('<i class="fa fa-floppy-o fa-fw"></i><span class="text">Save</span>', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade <?=$recordsClass?> in" id="records">
										<div class="tabbable" style="padding: 0px;">
			                                <ul class="nav nav-pills">
			                                    <li class="active"><a href="#required" data-toggle="tab" id="requiredLink"><i class="fa fa-pencil-square-o fa-fw" aria-hidden="true"></i> Required Training</a></li>
			                                    <li><a href="#all" data-toggle="tab" id="allLink"><i class="fa fa-book fa-fw" aria-hidden="true"></i>All Training</a></li>
			                                </ul>

			                                <div class="tab-content" style="border: 0px;">
			                                	<div id="LoadingDiv" style="display:none;">
						                        	<?php echo $this->Html->image('ajax-loader-red.gif'); ?>
						                    	</div>
			                                    <div class="tab-pane fade active in" id="required">
                                                    
												</div>
												<div class="tab-pane fade" id="all">
													
												</div>
											</div>
										</div>
										
                                    </div>
									<!--
                                    <div class="tab-pane fade <?=$assetsClass?> in" id="assets">
                                        <table class="table table-striped table-condensed" id="assetsTable">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th class="col-md-6">Asset</th>
                                                    <th>Tag</th>
                                                    <th>Manufacturer</th>
                                                    <th>Model</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                foreach($user['Asset'] as $asset){
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            echo $this->Html->link(
                                                                $asset['asset'],
                                                                array('controller'=>'Assets', 'action'=>'view', $asset['id']),
                                                                array('escape'=>false)
                                                            );
                                                            ?>
                                                        </td>
                                                        <td><?=$asset['tag_number']?></td>
                                                        <td><?=$asset['Manufacturer']['name']?></td>
                                                        <td><?=$asset['model']?></td>
                                                    </tr>
                                                    <?php
                                                }

                                                if(empty($user['Asset'])){
                                                    ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Records Found</td>
                                                    </tr>
                                                    <?php
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
									-->
                                    <div class="tab-pane fade <?=$safetyClass?> in" id="safety">
                                        <table class="table table-striped table-condensed" id="assetsTable">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th class="col-md-3">Date</th>
                                                    <th class="col-md-6">Description</th>
                                                    <th class="col-md-3">Area(s) of Injury</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                if(empty($user['Accident'])){
                                                    ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Records Found</td>
                                                    </tr>
                                                    <?php
                                                } else {
	                                                foreach($user['Accident'] as $v){
	                                                    ?>
	                                                    <tr>
	                                                        <td><?php echo date('F d, Y', strtotime($v['date'])); ?></td>
	                                                        <td><?=$v['description']?></td>
	                                                        <td>
																<ul class="list-unstyled">
																	<?php
																	foreach($v['AccidentArea'] as $l){
																		?>
																		<li><?=$l['AccidentAreaLov']['name']?></li>
																		<?php
																	}
																	?>
																</ul>
															</td>
	                                                    </tr>
	                                                    <?php
	                                                }
												}	
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
									<div class="tab-pane fade <?=$testingClass?> in" id="testing">
										<?php #pr($user['AssignedTest']); ?>
										<table class="table table-hover table-condensed">
									        <thead>
									            <tr class="tr-heading">
									                <th>Test</th>
									                <th>Scheduled Date</th>
									                <th>Expires Date</th>
													<th>Completion Date</th>
													<th>Completed</th>
													<th></th>
												</tr>
									        </thead>
									        <tbody>
									            <?php
									            if(!empty($user['AssignedTest'])){
									                foreach($user['AssignedTest'] as $key=>$item){
														#pr($item);
									                    $c_date = (!is_null($item['completion_date'])) ? date( APP_DATE_FORMAT,strtotime($item['completion_date'])) : null;
									                    $warning = strtotime("-14 day", strtotime($item['expires_date']));
									                    $item['complete'] = intval($item['complete']);

									                    $time = time();
                                                        $link = array();
									                    $text_class = 'text-success';
									                    $bar_class = 'success';

									                    if(is_null($c_date)){
									                        $text_class = ($time >= $warning) ? 'text-warning' : $text_class ;
									                        $bar_class = ($time >= $warning) ? 'warning' : $bar_class ;

									                        $text_class = (strtotime($item['expires_date']) <= $time) ? 'text-danger' : $text_class ;
									                        $bar_class = (strtotime($item['expires_date']) <= $time) ? 'danger' : $bar_class ;
									                    }

									                    if(!is_null($c_date)){
															#pr($item['Test']);
									                        foreach($item['Test']['ReportSwitch'] as $report){
									                            if(($report['Report']['is_user_report'] == 2 || is_null($report['Report']['is_user_report']))){
									                                $link[] = $this->Html->link(
									                                    '<i class="fa fa-pie-chart"></i>',
									                                    array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>$report['Report']['action'], $item['id'] ),
									                                    array('escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Available Report')
									                                );
									                            }
									                        }
									                    }


									                    ?>
									                    <tr>
															<td><?=$item['Test']['name']?></td>
									                        <td><?php echo date( APP_DATE_FORMAT,strtotime($item['assigned_date'])); ?></td>
									                        <td class="<?=$text_class?>"><?php echo date( APP_DATE_FORMAT,strtotime($item['expires_date'])); ?></td>
									                        <td><?=$c_date?></td>
									                        <td>
									                            <div class="progress">
									                                <div class="progress-bar progress-bar-<?=$bar_class?>" data-transitiongoal="<?=$item['complete']?>" style="width: <?=$item['complete']?>%; min-width: 2em;" aria-valuenow="<?=$item['complete']?>"><?=$item['complete']?> %</div>
									                            </div>
									                        </td>
									                        <td>
									                            <ul class="list-inline pull-right">
									                                <?php
									                                foreach($link as $reportLink){
									                                    ?>
									                                    <li><?=$reportLink?></li>
									                                    <?php
									                                }
									                                ?>
									                            </ul>
									                        </td>
									                    </tr>
									                    <?php
									                    unset($link);
									                }
									            }else{
									                ?>
									                <tr>
									                    <td colspan="5">No Records Found</td>
									                </tr>
									                <?php
									            }
									            ?>
									        </tbody>
									    </table>
                                        <?php #pr(); ?>
                                    </div>

									<div class="tab-pane fade <?=$awardClass?> in" id="award">

										<table class="table table-striped table-condensed" id="assetsTable">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th class="col-md-6">Award</th>
                                                    <th class="col-md-3">Amount</th>
                                                    <th class="col-md-3">Paid Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                if(empty($user['Award'])){
                                                    ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Records Found</td>
                                                    </tr>
                                                    <?php
                                                } else {
	                                                foreach($user['Award'] as $award){
														$paid_date = (!empty($award['paid_date'])) ? date('F d, Y', strtotime($award['paid_date'])) : '<span class="fa-stack fa-lg"><i class="fa fa-dollar fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>' ;
														?>
	                                                    <tr>
	                                                        <td><?=$award['Type']['award']?></td>
	                                                        <td><?php echo $this->Number->currency($award['amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));?></td>
	                                                        <td><?=$paid_date?></td>
	                                                    </tr>
	                                                    <?php
	                                                }
												}
												?>
                                            </tbody>
                                        </table>
                                        <?php #pr(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>

        </div>
    </div>
    <?php echo $this->Form->end(); ?>
	<?php
        $userRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'updateSupervisorList'));
        $groupRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'updateDeptList'));
        $trnRecord = $this->Html->url(array('plugin'=>false, 'controller'=>'Trainings', 'action' => 'deleteRecord'));
        $getRecord = $this->Html->url(array('plugin'=>false, 'controller'=>'Trainings', 'action' => 'getRecord'));
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $(".chzn-select").chosen({
                allow_single_deselect:true
            });

            $('.datepicker').datetimepicker({
                'format': 'MM/DD/YYYY',
                'showTodayButton': true,
                'icons': {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    close: "fa fa-trash",

                }
            });
			
			$.ajax({
                type: 'post',
                url: '<?=$getRecord?>/required/<?=$this->request->data['User']['id']?>' + '.json',
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                	$('#required').empty();
                },
                success: function(response) {
                    console.log(response);
                    $('#required').html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
            });
                
            $('#AccountUserAccountId').change(function() {
                $.ajax({
                    type: 'post',
                    url: '<?=$groupRequest_url?>/' + $(this).val() + '.json',
                    dataType: "html",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						
                    },
                    success: function(response) {
                        console.log(response);
                        $('#DepartmentUserDepartmentId').html(response);
                        $('#DepartmentUserDepartmentId' ).val('').trigger( 'chosen:updated' );
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function(){
                        $('#overlay').remove();
                    },
                });

                $.ajax({
                    type: 'post',
                    url: '<?=$userRequest_url?>/' + $(this).val() + '.json',
                    dataType: "html",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    },
                    success: function(response) {
                        console.log(response);
                        $('#UserSupervisorId').html(response);
                        $('#UserSupervisorId' ).val('').trigger( 'chosen:updated' );
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function(){
                        $('#overlay').remove();
                    },
                });
            });

			$('.trnRecord').on('click', function () {
				var Id = $(this).attr("data-value");
				var div = $(this).parents('div:eq(0)').attr('id');

                $.ajax({
                    type: 'post',
                    url: '<?=$trnRecord?>/' + Id + '/' + div + '/<?=$this->request->data['User']['id']?>' + '.json',
                    dataType: "html",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    },
                    success: function(response) {
                        console.log(response);
                        $('#' + div).html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function(){
                        $('#overlay').remove();
                    },
                });
			});

			$('#requiredLink').on('click', function () {
				$.ajax({
                    type: 'post',
                    url: '<?=$getRecord?>/required/<?=$this->request->data['User']['id']?>' + '.json',
                    dataType: "html",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        $('#LoadingDiv').show();
                    	$('#required').empty();
                    },
                    success: function(response) {
                        console.log(response);
                        $('#required').html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function(){
                        $('#LoadingDiv').hide();
                    },
                });
			});

			$('#allLink').on('click', function () {
				$.ajax({
                    type: 'post',
                    url: '<?=$getRecord?>/all/<?=$this->request->data['User']['id']?>' + '.json',
                    dataType: "html",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        $('#LoadingDiv').show();
                    	$('#all').empty();
                    },
                    success: function(response) {
                        console.log(response);
                        $('#all').html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function(){
                        $('#LoadingDiv').hide();
                    },
                });
			});
        });
    </script>