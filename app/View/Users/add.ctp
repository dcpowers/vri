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
            'error'=>true
        )
    ));

	
	echo $this->Form->hidden('AccountUser.account_id', array('value'=>$account));

	?>
	<style type="text/css">
        .fileupload-new{ color: black; }
        .fileupload-exists{ color: black; }

        .fileupload-exists a{ padding: 0px; }
        
        
    </style>
<div class="modal-header modal-header-warning bg-maroon">
	<a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
  	<h2><?php echo __('Add Employee:'); ?></h2>
</div>

<div class="modal-body">
	<div class="alert alert-danger alertdisplay" role="alert" style="display: none"></div>
    <div class="row">
    	<div class="col-md-3 headerContent">
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

		<div class="col-md-9">
    		<h3><i class="fa fa-address-card-o fa-fw" aria-hidden="true"></i> Personal</h3>
            <hr/>
            <label class="control-label text-danger" for="firstname">Name:</label>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        echo $this->Form->input('first_name', array (
                            'type'=>'text',
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
                                'value'=>$account,
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
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left', 'id'=>'save')
    );
    ?>
</div>
<?php echo $this->Form->end();?>
<?php
	$userRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'updateSupervisorList'));
    $groupRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'updateDeptList'));
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Users', 'action' => 'add'));
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
        
        $('#account_id').change(function() {
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
        
        $('#UserAddForm').submit(function(e){
            var formData = $(this).serializeArray();
            e.preventDefault();
            $.ajax({
                type : 'POST',
                url : '<?=$url?>',
                data: formData,
                beforeSend: function(xhr){
                	//xhr.setRequestHeader('Content-type', 'application/json');
                	$(".overlay").show();
        
                    $('#save').html('Saving...');
                    $('#save').attr('disabled', true);
                    $(".error-message").remove();
                },
                success : function(response) {
                	var response1=jQuery.parseJSON(response);
                    $('#save').attr('disabled', false);
                    
                    if(response1.status=='success'){
                    	console.log(response1.data);
                        $('#save').html('Saving...');
                        window.location.href = '/Users/index';
                        $('#myLgModal').modal('hide');
                    }else if(response1.status=='error'){
                        $('#save').attr('disabled', false);
                        $('#save').html('Save');
                        $('.alertdisplay').show();
                        $(".alertdisplay").text(response1.message);
                        
                        console.log(response1.data);
                        console.log(response1.data.validationErrors);
                        
                        $.each(response1.data.validationErrors, function(model, errors) {
                            for (fieldName in this) {
                                console.log(fieldName);
                                var element = $("#" + camelcase(model + '_' + fieldName));
                                console.log(element);
                                $(element).parent('div').addClass('has-error has-feedback');
                                /*$(element).closest('div').find('label').(':'+this[fieldName][0]);
                                $(element).closest('div').find('label').append(':'+this[fieldName][0]);*/
                                var create = $(document.createElement('div')).appendTo(element.parent('div'));
                                create.addClass('controls help-block error-message').text(this[fieldName][0])
                            }
                        });
            
                    }
                },
                error : function() {
                }
            });
        });
        
        function camelcase(inputstring) {
            var a = inputstring.split('_'), i;
            s = [];
            for (i=0; i<a.length; i++){
                s.push(a[i].charAt(0).toUpperCase() + a[i].substring(1));
            }
            s = s.join('');
            return s;
        }
    });
</script>