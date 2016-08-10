<?php 
    $today = date('Y-m-d h:i a');
    
    $m = date('m', strtotime('now'));
    $d = date('d', strtotime('now'));
    $y = date('Y', strtotime('now'));
    $h = date('h', strtotime('now'));
    $i = date('i', strtotime('now'));
    
    echo $this->Form->create('User', array(
        'url'=>array('controller'=>'Users', 'action'=>'edit'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label' => array('class' => 'control-label'),
            'div' => array('class' => 'form-group'),
            #'between' => '<div class="input-group">',
            'class'=>'form-control',
            #'after' => '</div>',
            'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-block'))
        )
    ));
                                
    echo $this->Form->hidden('id', array('value'=>$this->request->data['User']['id'])); 
?>
<div class="modal-header modal-header-primary">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit User: '. $this->request->data['User']['first_name'].' '.$this->request->data['User']['last_name']); ?></h2>
</div>
<div class="modal-body">
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
                        'between' => '<div class="input-group">',
                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
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
                        'value'=>$this->request->data['User']['supervisor_id'],
                        'between' => '<div class="input-group">',
                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
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
                <dt>Permissions (Role ):</dt>
                <dd>
                    <?php
                    echo $this->Form->input( 'auth_role_id', array(
                        'options'=>$roles,
                        'class'=>'chzn-select form-control',
                        'required'=>false,
                        'label'=>false,
                        'value'=>$this->request->data['User']['auth_role_id'],
                        'between' => '<div class="input-group">',
                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
                    ));
                    ?>
                </dd>
            </dl>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-sm-6">
                    <dl>
                        <dt>First Name:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'first_name', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    
                    <dl>
                        <dt>Last Name:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'last_name', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    
                    <dl>    
                        <dt>Username:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'username', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>    
                        <dt>E-Mail Address:</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'email', array(
                                'required'=>false,
                                'label'=>false,
                            ));
                            ?>
                        </dd>
                    </dl>
                </div>
                                    
                <div class="col-sm-6">
                    <?php
                    $doh = (!empty($user['User']['doh'])) ? date('F d, Y', strtotime($user['User']['doh'])) : 'N/A' ;
                    $dob = (!empty($user['User']['dob'])) ? date('F d, Y', strtotime($user['User']['dob'])) : 'N/A' ;
                    ?>
                    <dl>
                        <dt>Date Of Hire</dt>
                        <dd>
                            <?php
                            echo $this->Form->input( 'doh', array(
                                'type'=>'text',
                                'required'=>false,
                                'label'=>false,
                                'value'=>date('m/d/Y', strtotime($this->request->data['User']['doh'])),
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
                                'value'=>date('m/d/Y', strtotime($this->request->data['User']['dob'])),
                                'class'=>'datepicker form-control'
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>                                    
                        <dt>Current Account(s):</dt>
                        <dd>
                            <?php
                            $this->request->data['AccountUser']['account_id'] = Set::extract( $this->request->data['AccountUser'], '/account_id' );
                            echo $this->Form->input( 'AccountUser.account_id', array(
                                'options'=>$accounts,
                                'class'=>'chzn-select form-control',
                                'required'=>false,
                                'label'=>false,
                                'multiple'=>true,
                                'between' => '<div class="input-group">',
                                'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
                            ));
                            ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt>Current Department(s):</dt>
                        <dd>
                            <?php
                            $this->request->data['DepartmentUser']['department_id'] = Set::extract( $this->request->data['DepartmentUser'], '/department_id' );
                            echo $this->Form->input( 'DepartmentUser.department_id', array(
                                'options'=>$departments,
                                'class'=>'chzn-select form-control',
                                'multiple'=>true,
                                'required'=>false,
                                'label'=>false,
                                'between' => '<div class="input-group">',
                                'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
                            ));
                            ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    <?php echo $this->Form->button('Save', array('type'=>'submit', 'class'=>'btn btn-primary pull-left')); ?>
</div>

<?php echo $this->Form->end(); ?>
    
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
    });
</script>
