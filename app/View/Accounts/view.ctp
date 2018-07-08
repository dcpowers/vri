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
            <!--<li class="<?=$assetsClass?>"><a href="#assets" data-toggle="tab">Assets</a></li>-->
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
		                        <label for="AllPayID" class="control-label">Paylocity Id:</label>
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
                </div>
            </div>
            <div class="tab-pane fade <?=$recordsClass?> in" id="records">
            	<div id="LoadingDiv" style="display:none;">
                    <?php echo $this->Html->image('ajax-loader-red.gif'); ?>
                </div>
                <div id="trainingList">
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
            -->
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
				<div class="row">
					<?php
					foreach($awards as $title=>$a){
						?>
						<div class="col-md-2">
						<div class="hr-divider">
                    		<h3 class="hr-divider-content hr-divider-heading">
                        		<?=$title?>
	                        </h3>
	                    </div>
						<ul class="list-unstyled">
							<?php
							foreach($a as $month=>$v){
								$m = date('m', strtotime($month));
								?>
								<li>
									<?php
									echo $this->Html->link(
										$month .' ( '. $v['count'] .' ) ',
						                array('controller'=>'Awards', 'action'=>'monthView', $title, $m, $id),
						                array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
						            );
									?>

								</li>
								<?php
							}
							?>
						</ul>
						</div>
						<?php
					}
					?>
				</div>
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
    $trnurl = $this->Html->url(array('plugin'=>false, 'controller'=>'TrainingMemberships', 'action' => 'getAccountTraining', $account['Account']['id']));
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
        
        $.ajax({
            type: 'POST',
            url:'<?=$url?>/' + '<?=$pageStatus?>/' + '/<?=$viewBy?>' + '.json',
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
        
        $.ajax({
            type: 'POST',
            url:'<?=$trnurl?>/' + '1.json',
            cache: false,
            dataType: "html",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                $('#LoadingDiv').show();
                $('#trainingList').empty();
            },
            complete: function(){
                $('#LoadingDiv').hide();
            },
            success: function(response) {
                $('#trainingList').html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
	});
</script>