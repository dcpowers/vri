<?php
    //pr($this->Session->read('Auth.User.Location.name'));
    #pr($user);
    #exit;
    
    $this->Html->addCrumb('Users', array('controller' => 'users', 'action' => 'index', 'member'=>true));
    $this->Html->addCrumb('New User', array('controller' => 'users', 'action' => 'add', 'member'=>true));
    
    $this->Html->script('plugins/bootstrap-editable/jquery.mockjax.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/moment/moment.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-datepicker/bootstrap-datepicker.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-editable/bootstrap-editable.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/typeahead/typeahead.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/typeahead/typeaheadjs.1.5.1.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/select2/select2.min.js', array('block' => 'scriptsBottom'));
    //$this->Html->script('plugins/bootstrap-editable/address.custom.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-editable/demo-mock.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/select2/select2.min.js', array('block' => 'scriptsBottom'));
    $this->Html->script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js', array('block' => 'scriptsBottom'));
    //$this->Html->script('queen-elements.js', array('block' => 'scriptsBottom'));
    #pr($user);
    #exit;
?>

<div class="container">
    <div class="row">
        <div class="users form">
            <h1>New Account:</h1>
            <div class="widget widget-no-header">
                <div class="widget-content">
                    <?php echo $this->Form->create('User', array(
                        'url' => array('controller'=>'users', 'action'=>'add', 'member'=>true), 
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
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Firstname -->
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="firstname">Firstname:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('first_name', array (
                                        'type'=>'text',
                                        'id'=> 'first_name', 
                                        'placeholder'=>'Firstname',
                                        //'value'=>$user[0]['User']['first_name']
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
                                        //'value'=>$user[0]['User']['last_name']
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
                                <label class="col-sm-4 control-label" for="Username">E-Mail Address / Username:</label>
                                <div class="col-sm-8">
                                    <?php echo $this->Form->input('username', array (
                                        'type'=>'email',
                                        'id'=> 'username', 
                                        'placeholder'=>'Username',
                                        //'value'=>$user[0]['User']['username']
                                    ));?>
                                    <small>Whether company provided or private, their e-mail address will be their username</small>
                                    <?php
                                    if(!empty($error)){
                                        ?>
                                        <small class="text-danger"><?=$error?></small>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="password_confirm">Select Group:</label>
                                <div class="col-sm-8">
                                    <?=$error?>
                                    <?php echo $this->Form->input('GroupMembership.group_id', array (
                                        'type'=>'select',
                                        'options'=> $groups, 
                                        
                                    ));?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Password -->
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['password']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['password']) ? $validationErrors['User']['password'][0] : '';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="col-sm-4 control-label" for="password">Password:</label>
                                <div class="col-sm-8">
                                    
                                    <?php echo $this->Form->input('password', array (
                                        'type'=>'password',
                                        'id'=> 'password', 
                                        'placeholder'=>'Password',
                                        'value'=>'password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <small>Default Password is: password</small>
                                    <?php
                                    if(!empty($error)){
                                        ?>
                                        <small class="text-danger"><?=$error?></small>
                                        <?php
                                    }
                                    ?>
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
                                    
                                    <?php echo $this->Form->input('password_confirm', array (
                                        'type'=>'password',
                                        'id'=> 'password_confirm', 
                                        'placeholder'=>'Repeat Password',
                                        'value'=>'password'
                                    ));?>
                                    <small>Password must have a mimimum of 6 characters</small><br />
                                    <?php
                                    if(!empty($error)){
                                        ?>
                                        <small class="text-danger"><?=$error?></small>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-footer">
                    <div class="widget-header-toolbar">
                        <input type="submit" class="btn btn-success pull-right" value="Save">
                    </div>
                    <div class="clearfix"></div>
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

            