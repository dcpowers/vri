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
        'inputDefaults' => array(
        	'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

	$this->request->data['AccountUser']['account_id'] = (!empty(key($account))) ? key($account) : null ;
	$a_id = (!empty(key($account))) ? key($account) : null ;
	$a_name = (!empty($account)) ? $account[$a_id] : null ;

	echo $this->Form->hidden('AccountUser.account_id', array('value'=>$a_id));

	?>
<div class="modal-header modal-header-warning bg-maroon">
	<a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
  	<h2><?php echo __('Add Employee:'); ?></h2>
</div>

<div class="modal-body">
    <div class="row">
    	<div class="col-md-2 headerContent">
        	<div class="form-group">
                        <div class="fileupload fileupload-new " data-provides="fileupload">
                            <div class="fileupload-new thumbnail">
                                <div class="effect3 thumbnail">
                                <?php
                                echo $this->Html->image('/img/profiles/noImage.png', array('class'=>'img-responsive'));
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
                </div>

				<div class="col-md-10">
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
                                        <div class="row">
                                            <div class="col-sm-7">

                                                <div class="form-group">
                                                    <label class="control-label">Account:</label>
													<?php

													if(AuthComponent::user('Role.permission_level') >= 50){
														echo $this->Form->input( 'AccountUser.account_id', array(
						                                	'options'=>$accounts,
						                                    'class'=>'chzn-select form-control',
						                                    'multiple'=>false,
						                                    'between' => '<div class="input-group">',
						                                    'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                ));
						                            }else{
														?>
														<p class="form-control-static"><?=$a_name?></p>
														<?php
													}
													?>
                                                </div>

                                                		<div class="form-group">
                                                            <label class="control-label">Department:</label>
															<?php
						                                    echo $this->Form->input( 'DepartmentUser.department_id', array(
						                                        'options'=>$departments,
						                                        'class'=>'chzn-select form-control',
						                                        'multiple'=>false,
						                                        'between' => '<div class="input-group">',
						                                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                    ));
						                                    ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Supervisor:</label>
															<?php
						                                    echo $this->Form->input( 'supervisor_id', array(
						                                        'options'=>$pickListByAccount,
						                                        'class'=>'chzn-select form-control',
						                                        'data-placeholder'=>'Select An Account',
						                                        'between' => '<div class="input-group">',
						                                        'after' => '<div class="input-group-addon"><i class="fa fa-exclamation text-danger"></i></div></div>',
						                                    ));
						                                    ?>
                                                        </div>

                                            </div>

                                            <div class="col-sm-5">
                                                <div class="well">
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
                                                        <label class="control-label">Permission (Role):</label>
                                                        <?php
					                                    echo $this->Form->input( 'auth_role_id', array(
					                                        'options'=>$roles,
					                                        'class'=>'chzn-select form-control',
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
			                                                'value'=>date('m/d/Y', strtotime('now')),
			                                                'class'=>'datepicker form-control'
			                                            ));
			                                            ?>
                                                    </div>
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
                                                        'empty'=>true,
                                                        'class'=>'datepicker form-control'
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-8"></div>
                                        </div>

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