    <?php
    $employeesClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'employees') ? 'active' : null;
    $recordsClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'records') ? 'active' : null;
    $assetsClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'assets') ? 'active' : null;
    $safetyClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'safety') ? 'active' : null;
    $awardsClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'awards') ? 'active' : null;
    $testClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'tests') ? 'active' : null;

    $accountClass = (empty($this->params['pass'][1]) || $this->params['pass'][1] == 'accounts') ? 'active' : null;


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

<div class="account view bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Account Details: <?=$account['Account']['name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Accounts</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/menu' );?>
        </div>
    </div>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?=$accountClass?>"><a href="#info" data-toggle="tab">Account Details</a></li>
            <li class="<?=$employeesClass?>"><a href="#users" data-toggle="tab">Employees</a></li>
            <li class="<?=$recordsClass?>"><a href="#records" data-toggle="tab">Training</a></li>
            <li class="<?=$assetsClass?>"><a href="#assets" data-toggle="tab">Assets</a></li>
            <li class="<?=$safetyClass?>"><a href="#safety" data-toggle="tab">Safety</a></li>
            <li class="<?=$awardsClass?>"><a href="#awards" data-toggle="tab">Awards</a></li>
            <li class="<?=$testClass?>"><a href="#tests" data-toggle="tab">Testing</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade <?=$accountClass?> in" id="info">
                <?php #pr($account); ?>
				<?php
			    echo $this->Form->create('Account', array(
			        'url'=>array('controller'=>'Accounts', 'action'=>'edit', $this->request->data['Account']['id']),
			        #'class'=>'form-horizontal',
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
				<div class="row">
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-8">
                    			<div class="form-group">
			                        <label for="name" class="control-label">Name:</label>
			                        <?php
			                        echo $this->Form->input( 'name', array());
			                        ?>
			                    </div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
			                        <label for="abr" class="control-label">Abbreviation:</label>
			                        <?php
			                        echo $this->Form->input( 'abr', array());
			                        ?>
			                    </div>
							</div>
						</div>

	                    <div class="form-group">
	                        <label for="address" class="control-label">Address:</label>
	                        <?php
	                        echo $this->Form->input( 'address', array());
	                        ?>
	                    </div>
                        <div class="row">
							<div class="col-md-6">
			                    <div class="form-group">
			                        <label for="supervisor" class="control-label">Supervisor:</label>
			                        <?php
			                        echo $this->Form->input( 'manager_id', array(
			                            'options'=>$userList,
			                            'class'=>'chzn-select form-control',
			                            'empty' => true,
			                            'data-placeholder'=>'Select a Supervisor.....',
			                        ));
			                        ?>
			                    </div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
			                        <label for="manager" class="control-label">Systems Coordinator:</label>
			                        <?php
			                        echo $this->Form->input('coordinator_id', array(
			                            'options'=>$userList,
			                            'class'=>'chzn-select form-control',
			                            'empty' => true,
			                            'data-placeholder'=>'Select a Systems Coordinator.....',
			                        ));
			                        ?>
			                    </div>
							</div>
						</div>
                        <div class="row">
							<div class="col-md-6">
								<div class="form-group">
			                        <label for="department" class="control-label">Current Department(s):</label>
			                        <?php
			                        echo $this->Form->input('AccountDepartment.department_id', array(
			                            'options'=>$departments,
			                            'class'=>'chzn-select form-control',
			                            'empty' => true,
			                            'multiple'=>true,
			                            'data-placeholder'=>'Select Department(s).....',
			                        ));
			                        ?>
			                    </div>
                            </div>

							<div class="col-md-6">
			                    <div class="form-group">
			                        <label for="AllPayID" class="control-label">Old Department(s):</label>
			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('EVS', array());
			                                ?>
			                                EVS
			                            </label>
			                        </div>

			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('CE', array());
			                                ?>
			                                CE
			                            </label>
			                        </div>

			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('Food', array());
			                                ?>
			                                Food
			                            </label>
			                        </div>

			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('POM', array());
			                                ?>
			                                POM
			                            </label>
			                        </div>

			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('LAU', array());
			                                ?>
			                                LAU
			                            </label>
			                        </div>

			                        <div class="checkbox">
			                            <label>
			                                <?php
			                                echo $this->Form->checkbox('SEC', array());
			                                ?>
			                                SEC
			                            </label>
			                        </div>
			                    </div>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="well">
							<div class="form-group">
		                        <label for="status" class="control-label">Account Status:</label>
		                        <?php
		                        echo $this->Form->input('is_active', array(
		                            'options'=>$status,
		                            'value'=>$account['Status']['id'],
		                            'class'=>'chzn-select-status form-control',
		                            'empty' => true,
		                            'multiple'=>false,
		                            'data-placeholder'=>'Select Account Status.....',
		                        ));
		                        ?>
		                    </div>

		                    <div class="form-group">
		                        <label for="SprocketDB" class="control-label">SprocketDB:</label>
		                        <?php
		                        echo $this->Form->input('SprocketDB', array());
		                        ?>
		                    </div>

		                    <div class="form-group">
		                        <label for="AllPayID" class="control-label">All Pay Id:</label>
		                        <?php
		                        echo $this->Form->input('AllPayID', array('type'=>'text'));
		                        ?>
		                    </div>
							<div class="form-group">
		                        <label for="regional_admin" class="control-label">Regional Administrator:</label>
		                        <?php
		                        echo $this->Form->input('regional_admin_id', array(
		                            'options'=>$userList['Vanguard Resources'],
		                            'class'=>'chzn-select form-control',
		                            'empty' => true,
		                            'data-placeholder'=>'Select a Regional Administrator.....',
		                        ));
		                        ?>
		                    </div>
						</div>
					</div>
				</div>
                <?php
	            echo $this->Form->button('Save', array(
	                'type'=>'submit',
	                'class'=>'btn btn-primary'
	            ));
	            ?>
                <?php echo $this->Form->end();?>
            </div>
            <div class="tab-pane fade <?=$employeesClass?> in" id="users">
                <div id="LoadingDiv" style="display:none;">
                        <?php echo $this->Html->image('ajax-loader-red.gif'); ?>
                    </div>
                <div id="employeeList">

                    <div class="row">
                        <div class="col-sm-4">
                            <ul class="pagination pagination-sm">
                                <li class="<?=$deptClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'View By Department',
                                        '#',
                                        array('escape'=>false, 'class'=>'type', 'id'=>'department')
                                    );
                                    ?>
                                </li>

                                <li class="<?=$roleClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'View By Role',
                                        '#',
                                        array('escape'=>false, 'class'=>'type', 'id'=>'role')
                                    );
                                    ?>
                                </li>

                                <li class="<?=$superClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'View By Supervisor',
                                        '#',
                                        array('escape'=>false, 'class'=>'type', 'id'=>'supervisor')
                                    );
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-8">
                            <ul class="pagination pagination-sm">
                                <li class="<?=$aStatusClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'Active',
                                        '#',
                                        array('escape'=>false, 'class'=>'statusType', 'id'=>1)
                                    );
                                    ?>
                                </li>

                                <li class="<?=$iStatusClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'Inactive',
                                        '#',
                                        array('escape'=>false, 'class'=>'statusType', 'id'=>2)
                                    );
                                    ?>
                                </li>

                                <li class="<?=$allStatusClass?>">
                                    <?php
                                    echo $this->Html->link(
                                        'View All',
                                        '#',
                                        array('escape'=>false, 'class'=>'statusType', 'id'=>'all')
                                    );
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php
                    #pr($employees);
                    foreach($employees as $department=>$employee){
                        ?>
                        <div class="hr-divider">
                            <h3 class="hr-divider-content hr-divider-heading">
                                <?=$department?>
                            </h3>
                        </div>
                        <table class="table table-striped table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Name</th>
                                    <th class="col-sm-2">Username</th>
                                    <th class="col-sm-2">E-Mail</th>
                                    <th class="col-sm-2">Role</th>
                                    <th class="col-sm-2">Supervisor</th>
                                    <th class="col-sm-2 text-center">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach($employee as $user){
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $this->Html->link(
                                                $user['first_name'].' '.$user['last_name'],
                                                array('controller'=>'Users', 'action'=>'view', $user['id']),
                                                array('escape'=>false)
                                            );
                                            ?>
                                        </td>

                                        <td><?=$user['username']?></td>

                                        <td><?=$user['email']?></td>

                                        <td><?=$user['Role']['name']?></td>
                                        <td>
                                            <?php
                                            if(array_key_exists('first_name', $user['Supervisor'])){
                                                echo $user['Supervisor']['first_name'].' '.$user['Supervisor']['last_name'];
                                            }else{
                                                echo '--';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="<?=$user['Status']['color']?> label-as-badge"><i class="fa <?=$user['Status']['icon']?>"></i></span>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="tab-pane fade <?=$recordsClass?> in" id="records">
                <?php
                #pr($account['TrainingMembership']);
                ?>
                <table class="table table-striped table-condensed" id="assetsTable">
                    <thead>
                        <tr class="tr-heading">
                            <th class="col-md-4">Training</th>
                            <th>Required</th>
                            <th>Corp Required</th>
                            <th class="col-md-2">Renewal Length</th>
                            <th class="col-md-4">Required For</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        #pr($trainings);
                        #exit;
                        foreach($trainings as $title=>$trn){
                            #pr($trn);
                            #exit;
                            $required = ($trn[0]['is_required'] ==1) ? '<i class="fa fa-check-circle fa-lg" aria-hidden="true" style="color: #00A65A" ></i>' : '<i class="fa fa-times-circle fa-lg" aria-hidden="true" style="color: #DD4B39"></i>' ;
                            $maditory = ($trn[0]['is_manditory'] ==1) ? '<i class="fa fa-check-circle fa-lg" aria-hidden="true" style="color: #00A65A" ></i>' : '<i class="fa fa-times-circle fa-lg" aria-hidden="true" style="color: #DD4B39"></i>' ;

                            foreach($trn as $record){
                                if(!empty($record['Department'])){
                                    $requiredFor['Departments'][] = $record['Department']['name'];
                                }

                                if(!empty($record['RequiredUser'])){
                                    $requiredFor['Users'][] = $record['RequiredUser']['first_name'] .' '.$record['RequiredUser']['last_name'];
                                }
                            }

                            ?>
                            <tr>
                                <td>
                                    <?php
                                    echo $this->Html->link(
                                        $trn[0]['Training']['name'],
                                        array('controller'=>'Trainings', 'action'=>'index'),
                                        array('escape'=>false)
                                    );
                                    ?>
                                </td>

                                <td class="text-center"><?=$required?></td>

                                <td class="text-center"><?=$maditory?></td>

                                <td><?=$trn[0]['renewal']?> Mo(s)</td>

                                <td>
                                    <?php
                                    if(!empty($requiredFor)){
                                        foreach($requiredFor as $key=>$val){
                                            ?>
                                            <ul>
                                                <li><?=$key?>
                                                    <ul>
                                                        <?php
                                                        foreach($val as $item){
                                                            ?>
                                                            <li><?=$item?></li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <?php
                                        }
                                    }else if($trn[0]['is_required'] ==1){
                                        ?>
                                        <ul>
                                            <li>Everyone</li>
                                        </ul>
                                        <?php
                                    }else{
                                        ?>
                                        <ul>
                                            <li>--</li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            unset($requiredFor);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade <?=$assetsClass?> in" id="assets">
                <table class="table table-striped table-condensed" id="assetsTable">
                    <thead>
                        <tr class="tr-heading">
                            <th class="col-md-6">Asset</th>
                            <th>Tag</th>
                            <th>Manufacturer</th>
                            <th>Model</th>
                            <th class="col-md-3">Assigned To</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach($assets as $asset){
                            $name = (!empty($asset['AssignedTo']['first_name'])) ? $asset['AssignedTo']['first_name'].' '.$asset['AssignedTo']['last_name'] : '--' ;
                            $manName = (!empty($asset['Manufacturer']['name'])) ? $asset['Manufacturer']['name'] : '--' ;
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

                                <td><?=$manName?></td>

                                <td><?=$asset['model']?></td>

                                <td><?=$name?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade <?=$safetyClass?> in" id="safety">
				<table class="table table-striped" id="accountsTable">
			        <thead>
			            <tr class="tr-heading">
			                <th class="text-center col-md-1">Id</th>
			                <th class="col-md-2">Name</th>
			                <th class="col-md-2">Department</th>
							<th class="col-md-2">Date</th>
							<th class="col-md-3">Description</th>
                            <th class="col-md-2"></th>
                        </tr>
			        </thead>

			        <tbody>
			            <?php
			            foreach($account['Accident'] as $a){
							$class= ($a['is_active'] == 2) ? 'danger' : null ;
							$date = date('F d, Y', strtotime($a['date']));
			                ?>
			                <tr class="<?=$class?>">
								<td class="text-center"><?=$a['id']?></td>
			                    <td>
			                        <?php
			                        echo $this->Html->link(
			                            $a['first_name'].' '.$a['last_name'],
			                            array('controller'=>'Accidents', 'action'=>'view', $a['id']),
			                            array('escape'=>false)
			                        );
			                        ?>
			                    </td>

			                    <td><?=$a['Dept']['name']?></td>

			                    <td><?=$date?></td>
			                    <td><?=$a['description']?></td>
		                        <td>
									<ul class="list-inline">
										<li>
											<?php
											if($a['is_active'] == 1){
												echo $this->Html->link(
						                            '<i class="fa fa-fw fa-unlock"></i>',
						                            array('controller'=>'Accidents', 'action'=>'open', $a['id']),
						                            array('escape'=>false)
						                        );
											}else{
												echo $this->Html->link(
						                            '<i class="fa fa-fw fa-lock"></i>',
						                            array('controller'=>'Accidents', 'action'=>'close', $a['id']),
						                            array('escape'=>false)
						                        );
											}


											?>
										</li>
		                            </ul>
								</td>
			                </tr>
			                <?php
			            }
			            ?>
			        </tbody>
			    </table>

            </div>
            <div class="tab-pane fade <?=$awardsClass?> in" id="awards">
            </div>

			<div class="tab-pane fade <?=$testClass?> in" id="tests">
				<?php
				foreach($testing as $title=>$test){
					?>
					<div class="hr-divider">
                    	<h3 class="hr-divider-content hr-divider-heading">
                        	<?=$title?>
                        </h3>
                    </div>
                    <table class="table table-striped table-condensed table-hover">
                    	<thead>
                        	<tr>
                            	<th class="col-md-3">Employee</th>
                                <th class="col-md-2">Assigned On</th>
                                <th class="col-md-2">Completed On</th>
                                <th class="col-md-2">Expires On</th>
                                <th class="col-md-3">Completed</th>
                            </tr>
                        </thead>
						<?php
                        foreach($test as $v){
                            $warning = strtotime("-14 day", strtotime($v['Expires']));
							$v['Complete'] = intval($v['Complete']);

		                    $time = time();

							$text_class = null;
		                    $bar_class = 'success';

		                    if(is_null($v['Completed'])){
                                $text_class = ($time >= $warning) ? 'warning' : $text_class ;
								$bar_class = ($time >= $warning) ? 'warning' : $bar_class ;

								$text_class = (strtotime($v['Expires']) <= $time) ? 'danger' : $text_class ;
		                        $bar_class = (strtotime($v['Expires']) <= $time) ? 'danger' : $bar_class ;
		                    }
                        	?>
                            <tr class="<?=$text_class?>">
                            	<td><?=$v['User']?></td>
                            	<td><?=$v['Assigned']?></td>
                            	<td><?=$v['Completed']?></td>
                            	<td><?=$v['Expires']?></td>
                                <td>
		                            <div class="progress">
		                                <div class="progress-bar progress-bar-<?=$bar_class?>" data-transitiongoal="<?=$v['Complete']?>" style="width: <?=$v['Complete']?>%; min-width: 2em;" aria-valuenow="<?=$v['Complete']?>"><?=$v['Complete']?> %</div>
		                            </div>
		                        </td>
                            </tr>
                            <?php
                        }
                        ?>
                    	</tbody>
                	</table>
					<?php
				}
				?>
            </div>
		</div>
    </div>
</div>

<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Accounts', 'action' => 'employeeView', $account['Account']['id']));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $('.statusType').on('click', function () {
            $.ajax({
                type: 'POST',
                url:'<?=$url?>/' + $(this).attr("id") + '/<?=$viewBy?>' + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#employeeList').empty();
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                success: function(response) {
                    $('#employeeList').html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }

            });

            return false;

        });

        $('.type').on('click', function () {
            $.ajax({
                type: 'POST',
                url:'<?=$url?>/' + '<?=$pageStatus?>/' + $(this).attr("id") + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#employeeList').empty();
                },
                success: function(response) {
                    $('#employeeList').html(response);
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }

            });

            return false;

        });
    });
</script>