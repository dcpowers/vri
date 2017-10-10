<?php
    echo $this->Form->create('User', array(
        'url' => array('controller'=>'users', 'action'=>'search', 'admin'=>true), 
        'role'=>'form',
        'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
?>

<div class="container">
    <h1>People: <small><?=$user[0]['User']['first_name']?> <?=$user[0]['User']['last_name']?></small></h1>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel  panel-primary">
                <div class="panel-heading">
                    <h4>User Profile</h4>
                </div>
                            
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Surname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="surname">Surname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('sur_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['sur_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="status">Account Status:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('is_active', array (
                                        'type'=>'text',
                                        'id'=> 'status', 
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Firstname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="firstname">Firstname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('first_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['first_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- lastname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="lastname">Lastname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('last_name', array (
                                        'type'=>'text',
                                        'id'=> 'last_name', 
                                        'placeholder'=>'Lastname',
                                        'value'=>$user[0]['User']['last_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Username -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="Username">Username:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('username', array (
                                        'type'=>'email',
                                        'id'=> 'username', 
                                        'placeholder'=>'Username',
                                        'value'=>$user[0]['User']['username']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password']) ? $validationErrors['User']['password'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password">Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password', array (
                                        'type'=>'password',
                                        'id'=> 'password', 
                                        'placeholder'=>'Password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                            
                            <!-- Repeat Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password_confirm']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password_confirm']) ? $validationErrors['User']['password_confirm'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password_confirm">Repeat Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password_confirm', array (
                                        'type'=>'password',
                                        'id'=> 'password_confirm', 
                                        'placeholder'=>'Repeat Password',
                                        'value'=>''
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                        </div> <!-- end div of 3 -->
                        <div class="col-md-3">
                            <!-- Surname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="surname">Surname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('sur_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['sur_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="status">Account Status:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('is_active', array (
                                        'type'=>'text',
                                        'id'=> 'status', 
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Firstname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="firstname">Firstname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('first_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['first_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- lastname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="lastname">Lastname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('last_name', array (
                                        'type'=>'text',
                                        'id'=> 'last_name', 
                                        'placeholder'=>'Lastname',
                                        'value'=>$user[0]['User']['last_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Username -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="Username">Username:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('username', array (
                                        'type'=>'email',
                                        'id'=> 'username', 
                                        'placeholder'=>'Username',
                                        'value'=>$user[0]['User']['username']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password']) ? $validationErrors['User']['password'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password">Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password', array (
                                        'type'=>'password',
                                        'id'=> 'password', 
                                        'placeholder'=>'Password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                            
                            <!-- Repeat Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password_confirm']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password_confirm']) ? $validationErrors['User']['password_confirm'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password_confirm">Repeat Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password_confirm', array (
                                        'type'=>'password',
                                        'id'=> 'password_confirm', 
                                        'placeholder'=>'Repeat Password',
                                        'value'=>''
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                        </div> <!-- end div of 3 -->
                        
                        <div class="col-md-3">
                            <!-- Surname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="surname">Surname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('sur_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['sur_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="status">Account Status:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('is_active', array (
                                        'type'=>'text',
                                        'id'=> 'status', 
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Firstname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="firstname">Firstname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('first_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['first_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- lastname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="lastname">Lastname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('last_name', array (
                                        'type'=>'text',
                                        'id'=> 'last_name', 
                                        'placeholder'=>'Lastname',
                                        'value'=>$user[0]['User']['last_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Username -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="Username">Username:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('username', array (
                                        'type'=>'email',
                                        'id'=> 'username', 
                                        'placeholder'=>'Username',
                                        'value'=>$user[0]['User']['username']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password']) ? $validationErrors['User']['password'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password">Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password', array (
                                        'type'=>'password',
                                        'id'=> 'password', 
                                        'placeholder'=>'Password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                            
                            <!-- Repeat Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password_confirm']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password_confirm']) ? $validationErrors['User']['password_confirm'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password_confirm">Repeat Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password_confirm', array (
                                        'type'=>'password',
                                        'id'=> 'password_confirm', 
                                        'placeholder'=>'Repeat Password',
                                        'value'=>''
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                        </div> <!-- end div of 3 -->
                        
                        <div class="col-md-3">
                            <!-- Surname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="surname">Surname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('sur_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['sur_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="status">Account Status:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('is_active', array (
                                        'type'=>'text',
                                        'id'=> 'status', 
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Firstname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="firstname">Firstname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('first_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        'value'=>$user[0]['User']['first_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- lastname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="lastname">Lastname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('last_name', array (
                                        'type'=>'text',
                                        'id'=> 'last_name', 
                                        'placeholder'=>'Lastname',
                                        'value'=>$user[0]['User']['last_name']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Username -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="Username">Username:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('username', array (
                                        'type'=>'email',
                                        'id'=> 'username', 
                                        'placeholder'=>'Username',
                                        'value'=>$user[0]['User']['username']
                                    ));?>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password']) ? $validationErrors['User']['password'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password">Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password', array (
                                        'type'=>'password',
                                        'id'=> 'password', 
                                        'placeholder'=>'Password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                            
                            <!-- Repeat Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password_confirm']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password_confirm']) ? $validationErrors['User']['password_confirm'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password_confirm">Repeat Password:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('password_confirm', array (
                                        'type'=>'password',
                                        'id'=> 'password_confirm', 
                                        'placeholder'=>'Repeat Password',
                                        'value'=>''
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Leave blank if no change</small>
                                </div>
                            </div>
                        </div> <!-- end div of 3 -->
                    </div> <!-- end row -->
                </div><!-- End of body content -->
            </div><!-- end of panel -->
        </div><!-- end of div of 12 -->
    </div><!-- end row -->
    
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="panel  panel-danger">
                <div class="panel-heading">
                    <span>Testing</span>
                </div>
                            
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <th style="width: 30%">Name</th>
                            <th style="width: 10%">Scheduled</th>
                            <th style="width: 10%">Expires</th>
                            <th style="width: 10%">Completed</th>
                            <th style="width: 20%">%</th>
                            <th style="width: 20%">Options</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach($user[0]['AssignedTesting'] as $test){
                                #pr($test);
                                $date = explode('-', $test['AssignedTest']['completion_date']);
                                $m = $date[1];
                                $d = $date[2];
                                $y = $date[0];
                                
                                $c_date = (checkdate($m, $d, $y)) ? date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['completion_date'])) : null;
                                $a_date = (!empty($test['AssignedTest']['assigned_date'])) ? date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['assigned_date'])) : null;
                                $e_date = (checkdate($m, $d, $y)) ? null : date( APP_DATE_FORMAT, strtotime($test['AssignedTest']['expires_date']));
                                $percent = $test['AssignedTest']['complete'] * 100;
                                
                                //lets get all avaialbe reports
                                if(!empty($test['AssignedTest']['report']) && checkdate($m, $d, $y)){
                                    foreach($test['AssignedTest']['report'] as $report){
                                        $link[] = $this->Html->link(
                                            '<span class="label label-success label-as-badge"><i class="icon ion-compose"></i></span><br /><span><small>'.$report['Report']['name'].'</small></span>',
                                            array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>$report['Report']['action'], $test['AssignedTest']['id'] ),
                                            array('escape'=>false)
                                        );
                                    }
                                }else{
                                    if($percent >= 1){
                                        $link[] = $this->Html->link(
                                            'Reset Test',
                                            array('member'=>true, 'controller'=>'users', 'action'=>'reset', $test['AssignedTest']['id'], $user[0]['User']['id'] ),
                                            array('escape'=>false)
                                        );
                                    }else{
                                        $link = array();
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?=$test['name']?></td>
                                    <td><?=$a_date?></td>
                                    <td><?=$e_date?></td>
                                    <td><?=$c_date?></td>
                                    <td>
                                        <div class="progress demo-only progress-striped active">
                                            <div class="progress-bar progress-bar-info" data-transitiongoal="<?=$percent?>" style="width: <?=$percent?>%;" aria-valuenow="<?=$percent?>"><?=$percent?>%</div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        foreach($link as $reportLink){
                                            ?>
                                            <div class="pull-left text-center">
                                                <?=$reportLink?> 
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                unset($link);
                            }
                            ?>
                        </tbody>
                    </table>            
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6">
            <div class="panel  panel-danger">
                <div class="panel-heading">
                    <span>Training</span>
                </div>
                            
                <div class="panel-body">
                            
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="panel  panel-success">
                <div class="panel-heading">
                    <span>Group Membership</span>
                </div>
                            
                <div class="panel-body">
                            
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-sm-6">
            <div class="panel  panel-warning">
                <div class="panel-heading">
                    <span>Talent Pattern</span>
                </div>
                            
                <div class="panel-body">
                            
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="panel  panel-warning">
                <div class="panel-heading">
                    <span>Applied Jobs</span>
                </div>
                            
                <div class="panel-body">
                            
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-sm-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <span>Exempt Jobs</span>
                </div>
                            
                <div class="panel-body">
                            
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $('#editButton').click(function() {
            $('#userInfo .editable').editable('toggleDisabled');
            $(this).html($(this).html() == 'Done' ? 'Edit' : 'Done');
        });
        
        $('#userInfo .address').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .city').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .state').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .zip').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .phone').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .mobile').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .fax').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .dob').editable({
            disabled: true,
            format: 'yyyy-mm-dd',
            viewformat: 'dd/mm/yyyy',
            datepicker: {
                weekStart: 1
            }
        });
        
        $('#userInfo .soc').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .gender').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
        
        $('#userInfo .county').editable({
            disabled: true,
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            }
        });
    });
   
</script>

            