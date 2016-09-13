    <?php
    $employeesClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'employees') ? 'active' : null;
    $recordsClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'records') ? 'active' : null;
    $assetsClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'assets') ? 'active' : null;
    $safetyClass = (!empty($this->params['pass'][1]) && $this->params['pass'][1] == 'safety') ? 'active' : null;
    
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
            <?php echo $this->element( 'Accounts/menu' );?>                
        </div>
    </div>
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li class="<?=$accountClass?>"><a href="#info" data-toggle="tab">Account Details</a></li>
            <li class="<?=$employeesClass?>"><a href="#users" data-toggle="tab">Employees</a></li>
            <li class="<?=$recordsClass?>"><a href="#records" data-toggle="tab">Training</a></li>
            <li class="<?=$assetsClass?>"><a href="#assets" data-toggle="tab">Assets</a></li>
            <li class="<?=$safetyClass?>"><a href="#safety" data-toggle="tab">Safety</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade <?=$accountClass?> in" id="info">
                <?php #pr($account); ?>
                <div class="row">
                    <div class="col-md-3">
                        <dl>
                            <dt>Account Status:</dt>
                            <dd><?=$account['Status']['name']?></dd>
                        </dl>
                        <dl>
                            <dt>SprocketDB:</dt>
                            <dd><?=$account['Account']['SprocketDB']?></dd>
                        </dl>
                        <dl>    
                            <dt>All Pay Id:</dt>
                            <dd><?=$account['Account']['AllPayID']?></dd>
                        </dl>
                        <dl>    
                            <dt>Supervisor:</dt>
                            <dd><?=$account['Manager']['first_name']?> <?=$account['Manager']['last_name']?></dd>
                        </dl>
                        <dl>    
                            <dt>Systems Coordinator:</dt>
                            <dd><?=$account['Coordinator']['first_name']?> <?=$account['Coordinator']['last_name']?></dd>
                        </dl>
                        <dl>    
                            <dt>Regional Administrator:</dt>
                            <dd><?=$account['RegionalAdmin']['first_name']?> <?=$account['RegionalAdmin']['last_name']?></dd>
                        </dl>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-sm-6">
                                <dl>
                                    <dt>Name:</dt>
                                    <dd><?=$account['Account']['name']?> ( <?=$account['Account']['abr']?> )</dd>
                                </dl>
                                <dl>    
                                    <dt>Address:</dt>
                                    <dd><?=$account['Account']['address']?></dd>
                                </dl>
                            </div>
                                    
                            <div class="col-sm-6">
                                <?php
                                $evs = ($account['Account']['EVS'] == 1) ? true : false;
                                $ce = ($account['Account']['CE'] == 1) ? true : false;
                                $food = ($account['Account']['Food'] == 1) ? true : false;
                                $pom = ($account['Account']['POM'] == 1) ? true : false;
                                $lau = ($account['Account']['LAU'] == 1) ? true : false;
                                $sec = ($account['Account']['SEC'] == 1) ? true : false;
                                ?>
                                <dl>
                                    <dt>Current Department(s):</dt>
                                    <dd>
                                        <?php
                                        if(!empty($account['AccountDepartment'])){
                                            ?>
                                            <ul>
                                                <?php
                                                foreach($account['AccountDepartment'] as $group){
                                                    ?>
                                                    <li><?=$group['Department']['name']?></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        }
                                        ?>
                                    </dd>
                                </dl>
                                <dl>    
                                    <dt>Old Department(s):</dt>
                                    
                                    <dd>
                                        <ul>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('EVS', array(
                                                            'disabled'=>true, 
                                                            'checked'=>$evs
                                                        )); 
                                                        ?>
                                                        EVS
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('CE', array(
                                                            'disabled'=>true, 
                                                            'class'=>'accountInputs'
                                                        )); 
                                                        ?>
                                                        CE
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('Food', array(
                                                            'disabled'=>true, 
                                                            'class'=>'accountInputs'
                                                        )); 
                                                        ?>
                                                        Food
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('POM', array(
                                                            'disabled'=>true, 
                                                            'class'=>'accountInputs'
                                                        )); 
                                                        ?>
                                                        POM
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('LAU', array(
                                                            'disabled'=>true, 
                                                            'class'=>'accountInputs'
                                                        )); 
                                                        ?>
                                                        LAU
                                                    </label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php 
                                                        echo $this->Form->checkbox('SEC', array(
                                                            'disabled'=>true, 
                                                            'class'=>'accountInputs'
                                                        )); 
                                                        ?>
                                                        SEC
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
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