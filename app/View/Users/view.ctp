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
            <h6 class="dashhead-subtitle">Employee Details: <?=$user['User']['first_name']?> <?=$user['User']['last_name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Employees</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Users/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Users/menu' );?>
        </div>
    </div>
    <?php
    $assetCount = count($user['Asset']);
    ?>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab">Employee Details</a></li>
            <li><a href="#records" data-toggle="tab">Training Records</a></li>
            <li><a href="#assets" data-toggle="tab">Assets</a></li>
            <li><a href="#safety" data-toggle="tab">Safety</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade active in" id="info">
                <?php #pr($account); ?>
                <div class="row">
                    <div class="col-md-3">
                        <dl>
                            <dt>Employee Status:</dt>
                            <dd><?=$user['Status']['name']?></dd>
                        </dl>
                        <dl>
                            <dt>Supervisor:</dt>
                            <dd><?=$user['Supervisor']['first_name']?> <?=$user['Supervisor']['last_name']?></dd>
                        </dl>
                        <dl>
                            <dt>Pay Status:</dt>
                            <dd><?=$user['User']['PayStatus']?></dd>
                        </dl>
                        <dl>
                            <dt>All Pay Id:</dt>
                            <dd><?=$user['User']['AllPayID']?></dd>
                        </dl>
                        <dl>
                            <dt>Permissions (Role ):</dt>
                            <dd><?=$user['Role']['name']?></dd>
                        </dl>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-sm-6">
                                <dl>
                                    <dt>Name:</dt>
                                    <dd><?=$user['User']['first_name']?> <?=$user['User']['last_name']?></dd>
                                </dl>
                                <dl>
                                    <dt>Username:</dt>
                                    <dd><?=$user['User']['username']?></dd>
                                </dl>
                                <dl>
                                    <dt>Password:</dt>
                                    <dd id="password">
                                        <?php
                                        echo $this->Html->link(
                                            'Reset Password To: vanguard',
                                            array('controller'=>'Accounts', 'action'=>'view', $user['User']['id']),
                                            array('escape'=>false, 'id'=>'resetPassword')
                                        );
                                        ?>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>E-Mail Address:</dt>
                                    <dd><?=$user['User']['email']?></dd>
                                </dl>

                            </div>

                            <div class="col-sm-6">
                                <?php
                                $doh = (!empty($user['User']['doh'])) ? date('F d, Y', strtotime($user['User']['doh'])) : 'N/A' ;
                                $dob = (!empty($user['User']['dob'])) ? date('F d, Y', strtotime($user['User']['dob'])) : 'N/A' ;
                                ?>
                                <dl>
                                    <dt>Date Of Hire</dt>
                                    <dd><?=$doh?></dd>
                                </dl>
                                <dl>
                                    <dt>Date Of Birth</dt>
                                    <dd><?=$dob?></dd>
                                </dl>
								<?php
								if(AuthComponent::user('Role.permission_level') >= 50){
									?>
		                            <dl>
		                            	<dt>Current Account(s):</dt>
		                                <dd>
		                                	<?php
		                                    if(!empty($user['AccountUser'])){
		                                    	?>
		                                        <ul>
		                                        	<?php
		                                            foreach($user['AccountUser'] as $group){
		                                            	?>
		                                                <li>
		                                                	<?php
		                                                    echo $this->Html->link(
		                                                    	$group['Account']['name'],
		                                                        array('controller'=>'Accounts', 'action'=>'view', $group['Account']['id']),
		                                                        array('escape'=>false)
		                                                    );
		                                                    ?>
		                                                </li>
		                                                <?php
		                                            }
		                                            ?>
		                                        </ul>
		                                        <?php
		                                    }
		                                    ?>
		                                </dd>
		                            </dl>
									<?php
								}
								?>
                                <dl>
                                    <dt>Current Department(s):</dt>
                                    <dd>
                                        <?php
                                        if(!empty($user['DepartmentUser'])){
                                            ?>
                                            <ul>
                                                <?php
                                                foreach($user['DepartmentUser'] as $group){
                                                    ?>
                                                    <li>
                                                        <?=$group['Department']['name']?>
                                                    </li>
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
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="records">
                <div class="tabbable">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#required" data-toggle="tab">Required Training</a></li>
                        <li><a href="#allrecords" data-toggle="tab">All Training</a></li>
                    </ul>

                    <div class="tab-content tab-content-noLine">
                        <div class="tab-pane fade active in" id="required">
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
                                    foreach($requiredTraining as $training){
                                        $status = null;
                                        #pr($records[$training['Training']['id']]);

                                        if($records[$training['Training']['id']][0]['TrainingRecord']['in_progress'] == 1){
                                            $status = 'In Progress';
                                            $label = 'label label-primary';
                                        }

                                        if($records[$training['Training']['id']][0]['TrainingRecord']['expired'] == 1){
                                            $status = 'Expired';
                                            $label = 'label label-danger';
                                        }

                                        if($records[$training['Training']['id']][0]['TrainingRecord']['expiring'] == 1){
                                            $status = 'Expiring';
                                            $label = 'label label-warning';
                                        }

                                        if($records[$training['Training']['id']][0]['TrainingRecord']['no_record'] == 1){
                                            $status = 'No Record Found';
                                            $label = 'label label-danger';
                                        }

                                        $expires = (!empty($records[$training['Training']['id']][0]['TrainingRecord']['expires_on'])) ? date('F d, Y', strtotime($records[$training['Training']['id']][0]['TrainingRecord']['expires_on'])) : '--' ;
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

                                    if(empty($requiredTraining)){
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
                        <div class="tab-pane fade in" id="allrecords">
                            <?php
                            #pr($user['records']);
                            foreach($user['records'] as $title=>$record){
                                ?>
                                <div class="hr-divider">
                                    <h3 class="hr-divider-content hr-divider-heading">
                                        <?=$title?>
                                    </h3>
                                </div>

                                <table class="table table-striped table-condensed" id="assetsTable">
                                    <thead>
                                        <tr class="tr-heading">
                                            <th>Date</th>
                                            <th>Started Date</th>
                                            <th>Completed Date</th>
                                            <th>Trainer</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        #pr($user );
                                        foreach($record as $training){
                                            #pr($training);
                                            $status = null;
                                            #pr($records[$training['Training']['id']]);
                                            $trainer = (isset($training['Trainer']['first_name'])) ? $training['Trainer']['first_name'].' '. $training['Trainer']['last_name'] : 'N/A' ;
                                            $date = (!empty($training['date'])) ? date('F d, Y', strtotime($training['date'])) : null ;
                                            $started_on = (!empty($training['started_on'])) ? date('F d, Y', strtotime($training['started_on'])) : null ;
                                            $completed_on = (!empty($training['completed_on'])) ? date('F d, Y', strtotime($training['completed_on'])) : null ;
                                            ?>
                                            <tr>
                                                <td><?=$date?></td>
                                                <td><?=$started_on?></td>
                                                <td><?=$completed_on?></td>
                                                <td><?=$trainer?></td>
                                                <td><?php #$asset['model']?></td>
                                            </tr>
                                            <?php
                                        }

                                        if(empty($record)){
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No Records Found</td>
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
            <div class="tab-pane fade" id="assets">
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
                                        array('controller'=>'Assets', 'action'=>'quickview', $asset['id']),
                                        array('escape'=>false, 'data-toggle'=>'modal','data-target'=>'#myLgModal')
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
            <div class="tab-pane fade" id="safety">
            </div>
        </div>
    </div>
</div>

<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Accounts', 'action' => 'employeeView', $user['User']['id']));
    $password = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'resetPassword', $user['User']['id']));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $('.statusType').on('click', function () {
            $.ajax({
                type: 'POST',
                url:'<?=$url?>/' + '.json',
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

        $('#resetPassword').on('click', function () {
            $.ajax({
                type: 'POST',
                url:'<?=$password?>/' + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#password').empty();
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                success: function(response) {
                    $('#password').html(response);
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
                url:'<?=$url?>/' + '.json',
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