    <?php
    ?>
    <div class="account index bg-white">
        <div class="dashhead">
            <div class="dashhead-titles">
                <h6 class="dashhead-subtitle">Add New Employee:</h6>
                <h3 class="dashhead-title"><i class="fa fa-users fa-fw"></i>Employees:</h3>
            </div>
            <div class="dashhead-toolbar">
                <?php echo $this->element( 'Users/search' );?>
            </div>
        </div>
        <div class="flextable">
            <div class="flextable-item">
                <?php echo $this->element( 'Users/menu', array('user'=>$this->request->data) );?>                
            </div>
        </div>
        
        <div id="employeeList">
            <?php
            $today = date('Y-m-d h:i a');
    
            $m = date('m', strtotime('now'));
            $d = date('d', strtotime('now'));
            $y = date('Y', strtotime('now'));
            $h = date('h', strtotime('now'));
            $i = date('i', strtotime('now'));
            
            echo $this->Form->create('User', array(
                'url'=>array('controller'=>'Users', 'action'=>'add'),
                #'class'=>'form-horizontal',
                'role'=>'form',
                'inputDefaults'=>array(
                    'label' => array('class' => 'control-label'),
                    'div' => array('class' => 'form-group'),
                    #'between' => '<div class="input-group">',
                    'class'=>'form-control',
                    #'after' => '</div>',
                    'error' => false
                )
            ));
            #echo $this->Form->hidden('id', array('value'=>$this->request->data['User']['id'])); 
            ?>
            <div class="row">
                <div class="col-md-3">
                    <dl>
                        <dt>Employee Status:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'is_active', array(
                                'options'=>$status,
                                'class'=>'chzn-select form-control',
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>    
                        <dt>Supervisor:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'supervisor_id', array(
                                'options'=>$pickListByAccount,
                                'class'=>'chzn-select form-control',
                                'required'=>false,
                                'label'=>false,
                                'data-placeholder'=>'Select An Account',
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>    
                        <dt>Pay Status:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'PayStatus', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>    
                        <dt>All Pay Id:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'AllPayId', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>    
                        <dt>Permissions ( Role ):</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'auth_role_id', array(
                                'options'=>$roles,
                                'class'=>'chzn-select form-control',
                                'required'=>false,
                                'label'=>false,
                                'value'=>10,
                            ));
                            ?>
                        </dd>
                    </dl>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['first_name']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['first_name']) ? $validationErrors['User']['first_name'][0] : 'Firstname';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="control-label" for="state"><?=$error?>:</label>
                                <div class="input-group">
                                    <?php
                                    echo $this->Form->input( 'first_name', array(
                                        'required'=>true,
                                        'label'=>false,
                                    ));
                                    ?>
                                    <div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div>
                                </div>
                            </div>
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['last_name']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['last_name']) ? $validationErrors['User']['last_name'][0] : 'Lastname';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="control-label" for="state"><?=$error?>:</label>
                                <div class="input-group">
                                    <?php
                                    echo $this->Form->input( 'last_name', array(
                                        'required'=>false,
                                        'label'=>false,
                                    ));
                                    ?>
                                    <div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div>
                                </div>
                            </div>
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['username']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['username']) ? $validationErrors['User']['username'][0] : 'Username';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="control-label" for="state"><?=$error?>:</label>
                                <div class="input-group">
                                    <?php
                                    echo $this->Form->input( 'username', array(
                                        'required'=>false,
                                        'label'=>false,
                                    ));
                                    ?>
                                    <div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div>
                                </div>
                            </div>
                            
                            <?php 
                            unset($class,$error);
                            $class = !empty($validationErrors['User']['email']) ? 'has-error has-feedback' : ''; 
                            $error = !empty($validationErrors['User']['email']) ? $validationErrors['User']['email'][0] : 'E-Mail Address';
                            ?>
                            <div class="form-group <?=$class?>">
                                <label class="control-label" for="state"><?=$error?>:</label>
                                <div class="input-group">
                                    <?php
                                    echo $this->Form->input( 'email', array(
                                        'required'=>false,
                                        'label'=>false,
                                    ));
                                    ?>
                                    <div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div>
                                </div>
                            </div>
                        </div>
                                            
                        <div class="col-sm-6">
                            <dl>
                                <dt>Date Of Hire</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input( 'doh', array(
                                        'type'=>'text',
                                        'required'=>false,
                                        'label'=>false,
                                        #'value'=>date('m/d/Y', strtotime($this->request->data['User']['doh'])),
                                        'class'=>'datepicker form-control'
                                    ));
                                    ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>Date Of Birth</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input( 'dob', array(
                                        'type'=>'text',
                                        'required'=>false,
                                        'label'=>false,
                                        #'value'=>date('m/d/Y', strtotime($this->request->data['User']['dob'])),
                                        'class'=>'datepicker form-control'
                                    ));
                                    ?>
                                </dd>
                            </dl>
                            <dl>                                    
                                <dt>Current Account(s):</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input( 'AccountUser.account_id', array(
                                        'options'=>$accounts,
                                        'class'=>'chzn-select form-control',
                                        'required'=>false,
                                        'label'=>false,
                                        'multiple'=>true,
                                    ));
                                    ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>Current Department(s):</dt>
                                <dd>
                                    <?php
                                    echo $this->Form->input( 'DepartmentUser.department_id', array(
                                        'options'=>$departments,
                                        'class'=>'chzn-select form-control',
                                        'multiple'=>true,
                                        'required'=>false,
                                        'label'=>false,
                                    ));
                                    ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            echo $this->Form->button('Save', array(
                'type'=>'submit', 
                'class'=>'btn btn-primary pull-left'
            )); 
            ?>        
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
    <?php
        $groupRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'updateSupervisorList'));
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $(".chzn-select").chosen({
                allow_single_deselect: true
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
        });
            
    </script>