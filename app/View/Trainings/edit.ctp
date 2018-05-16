<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'edit', $this->request->data['Training']['id']),
        'role'=>'form',
		'type'=>'file',
        #'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

	echo $this->Form->hidden('id', array('value'=>$this->request->data['Training']['id']));

	if(AuthComponent::user('Role.permission_level') >= 60 ){
		$divWidthOne = 8;
	}else{
		$divWidthOne = 12;
	}
?>
<style type="text/css">
	.chosen-container .chosen-choices .search-field:only-child input {
    width: 100% !important;
}
</style>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit Training: <small>'.$this->request->data['Training']['name'].'</small>'); ?></h2>
</div>

<div class="modal-body">
	<div class="tabbable" style="margin-top: 20px;">
    	<ul class="nav nav-tabs">
        	<li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-book fa-fw" aria-hidden="true"></i> Training Information</a></li>
        	<li><a href="#files" data-toggle="tab"><i class="fa fa-book fa-fw" aria-hidden="true"></i> Training Files</a></li>
            <?php
			if(AuthComponent::user('Role.permission_level') >= 60 ){
				?>
				<li><a href="#quiz" data-toggle="tab"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> Training Quiz</a></li>
				<li><a href="#admin" data-toggle="tab"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i> Administrative Settings</a></li>
            	<li><a href="#use" data-toggle="tab"><i class="fa fa-flag fa-fw" aria-hidden="true"></i> Account Use</a></li>
				<?php
			}
			?>
        </ul>

        <div class="tab-content">
        	<div class="tab-pane fade active in" id="info">
            	<div class="row">
					<div class="col-md-8">
						<div class="form-group">
            				<label for="name" class="control-label">Name:</label>
			                <?php
							echo $this->Form->input('name', array (
								'type'=>'text'
							));
							?>
    					</div>
						<div class="form-group">
    						<label for="name" class="control-label">Description:</label>
					        <?php
							echo $this->Form->input('description', array (
								'type'=>'textarea'
							));
							?>
					    </div>

					</div>

					<div class="col-md-4">
						<div class="well">
							<div class="form-group">
								<label for="name" class="control-label">Status:</label><br />
								<label class="radio-inline">
									<?php
									echo $this->Form->radio('is_active',
										array (1=>'Active', 0=>'Inactive'),
										array('value'=>$this->request->data['Training']['is_active'], 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
									);
									?>
								</label>
							</div>
							<div class="form-group">
								<label for="name" class="control-label">Make Available To Others:</label><br />
								<label class="radio-inline">
									<?php
									echo $this->Form->radio('is_public',
										array (1=>'Make Public', 0=>'Make Private'),
										array('value'=>$this->request->data['Training']['is_public'], 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
									);
									?>
								</label>
							</div>
							
							<div class="form-group">
								<label class="control-label" for="name">Category(s):</label>
	                            <?php
	                            $current_cat_ids = Set::extract('{n}.training_category_id',$this->request->data['TrnCat']);
	                            echo $this->Form->input('TrnCat.training_category_id', array (
	                                'options'=>$TrnCategory,
									'value'=>$current_cat_ids,
	                                'type'=>'select',
	                                'empty'=>true,
	                                'multiple'=>true,
	                                'class'=>'form-select chzn-select',
	                                'data-placeholder'=>'Select Category(s)'
	                            ));
	                            ?>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="tab-pane fade in" id="files">
				<div class="form-group">
    				<label for="name" class="control-label">Files:</label>
			        <?php
					echo $this->Form->input('files.', array(
						'type' => 'file',
						'multiple'=>true
					));
					?>
					<small>Videos MUST BE in MP4 format!</small>
			    </div>
				<div id="files">
				    <?php
					if(!empty($this->request->data['TrainingFile'])){
    					?>
						<table class="table table-striped table-condensed" id="trainingTable">
        					<thead>
            					<tr class="tr-heading">
                					<th class="col-md-1">Order</th>
                					<th>File</th>
				                    <th>File Type</th>
				                    <th>File Size</th>
				                    <th></th>
				                </tr>
				            </thead>

				            <tbody>
            					<?php
            					$c = 0;
				                foreach($this->request->data['TrainingFile'] as $file){
									$filePath = file_exists(WWW_ROOT .'/files/training/'.$this->request->data['Training']['id'].'/'.$file['file']) ? filesize(WWW_ROOT .'/files/training/'.$this->request->data['Training']['id'].'/'.$file['file']) : null;
				                    $fileSize = human_filesize($filePath);

									?>
				                    <tr>
				                    	<td>
				                    		<div class="form-group">
				                    			<?php echo $this->Form->hidden('TrainingFile.'.$c.'.id', array('value'=>$file['id'])); ?>
                                                <label for="inputTitle" class="control-label sr-only">Order:</label>
                                                <?php 
                                                echo $this->Form->input('TrainingFile.'.$c.'.order_by', array(
                                                    'type'=>'text',
                                                    'value'=>$file['order_by']
                                                )); 
                                                ?>
                                            </div>
				                    	</td>
                    					<td><?=$file['human_name']?></td>
				                        <td><?=$file['file_type']?></td>
				                        <td><?=$fileSize?></td>
				                        <td>
											<?php
											if(AuthComponent::user('Role.permission_level') >= 60 ){
				                                echo $this->Html->link(
			                    					'<i class="fa fa-trash text-warning fa-2x" aria-hidden="true"></i>',
							                        '#',
							                        array('class'=>'trnRecord', 'data-value'=>$file['id'], 'data-file'=>$file['file'], 'escape'=>false, 'data-toggle'=>'tooltip', 'data-placement'=>'left', 'title'=>'Delete File')
							                    );
											}else{
												echo '&nbsp;';
											}
											?>
										</td>
				                    </tr>
				                    <?php
				                    $c++;
				                }
				                ?>
				            </tbody>
				        </table>
						<?php
					}
				    ?>
				</div>
			</div>
			<div class="tab-pane fade" id="quiz">
                <h4>
                    Quiz
                    <span id="sets-control">
                        <a class="btn btn-default btn-xs append-row"><i class="fa fa-plus fa-fw"></i>Add Question</a>
                    </span>
                </h4>
                <div id="quizSet">
                    <?php
                    $c = 0; 
                    foreach( $this->request->data['TrainingQuiz'] as $record_info ) { 
                        echo $this->Form->hidden('TrainingQuiz.'.$c.'.id', array('value'=>$record_info['id'])); 
                        $answerName = 'data[TrainingQuiz]['.$c.'][answer]';
                        ?>
                        <div class="panel panel-primary questions">
                            <div class="panel-heading">
                                <ul class="list-inline">
                                    <li class="col-md-1">
                                        <div class="form-group">
                                            <label for="inputTitle" class="control-label sr-only">Quiz Order:</label>
                                            <?php 
                                            echo $this->Form->input('TrainingQuiz.'.$c.'.quiz_order', array(
                                                'type'=>'text',
                                                'value'=>$record_info['quiz_order'],
                                            )); 
                                            ?>
                                        </div>
                                    </li>
                                    <li class="col-md-11">
                                        <div class="form-group">
                                            <label for="inputTitle" class="control-label sr-only">Question:</label>
                                            <?php 
                                            echo $this->Form->input('TrainingQuiz.'.$c.'.question', array(
                                                'type'=>'text',
                                                'value'=>$record_info['question']
                                            )); 
                                            ?>
                                        </div>
                                    </li>
                                </ul>
                                <div class="clearfix"></div> 
                            </div>
                            <div class="panel-body">
                                <h4>Options</h4>
                                <ul class="list-unstyled">
                                    <li>
                                        <ul class="list-inline">
                                            <li class="col-md-1 text-center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$answerName?>" id="" value="A" <?php if($record_info['answer'] == 'A'){ echo 'checked';} ?>/>
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputTitle" class="control-label sr-only">Answer A:</label>
                                                    <?php 
                                                    echo $this->Form->input('TrainingQuiz.'.$c.'.answer_a', array(
                                                        'type'=>'text',
                                                        'value'=>$record_info['answer_a']
                                                    )); 
                                                    ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul class="list-inline">
                                            <li class="col-md-1 text-center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$answerName?>" id="" value="B" <?php if($record_info['answer'] == 'B'){ echo 'checked';} ?> />
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputTitle" class="control-label sr-only">Answer B:</label>
                                                    <?php 
                                                    echo $this->Form->input('TrainingQuiz.'.$c.'.answer_b', array(
                                                        'type'=>'text',
                                                        'value'=>$record_info['answer_b']
                                                    )); 
                                                    ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <li>
                                        <ul class="list-inline">
                                            <li class="col-md-1 text-center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$answerName?>" id="" value="C" <?php if($record_info['answer'] == 'C'){ echo 'checked';} ?> />
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputTitle" class="control-label sr-only">Answer C:</label>
                                                    <?php 
                                                    echo $this->Form->input('TrainingQuiz.'.$c.'.answer_c', array(
                                                        'type'=>'text',
                                                        'value'=>$record_info['answer_c']
                                                    )); 
                                                    ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <li>
                                        <ul class="list-inline">
                                            <li class="col-md-1 text-center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$answerName?>" id="" value="D" <?php if($record_info['answer'] == 'D'){ echo 'checked';} ?> />
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputTitle" class="control-label sr-only">Answer D:</label>
                                                    <?php 
                                                    echo $this->Form->input('TrainingQuiz.'.$c.'.answer_d', array(
                                                        'type'=>'text',
                                                        'value'=>$record_info['answer_d']
                                                    )); 
                                                    ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <li>
                                        <ul class="list-inline">
                                            <li class="col-md-1 text-center">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="<?=$answerName?>" id="" value="E" <?php if($record_info['answer'] == 'E'){ echo 'checked';} ?> />
                                                    </label>
                                                </div>
                                            </li>
                                            <li class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputTitle" class="control-label sr-only">Answer E:</label>
                                                    <?php 
                                                    echo $this->Form->input('TrainingQuiz.'.$c.'.answer_e', array(
                                                        'type'=>'text',
                                                        'value'=>$record_info['answer_e']
                                                    )); 
                                                    ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <h4>Action Step</h4>
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Action Step:</label>
                                    <?php 
                                    echo $this->Form->input('TrainingQuiz.'.$c.'.action_step', array(
                                        'type'=>'text',
                                        'value'=>$record_info['action_step']
                                    )); 
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $c++;
                    }
                    ?>
                </div>
            </div>
			<?php
			if(AuthComponent::user('Role.permission_level') >= 60 ){
				?>
				<div class="tab-pane fade in" id="admin">
					<div class="row">
    					<div class="col-md-8">
							<div class="form-group">
        						<div class="checkbox">
                					<label>
	                                    <?php
	                                    echo $this->Form->checkbox('TrainingMembership.is_manditory', array());
	                                    ?>
	                                    Is Mandatory Training For Everyone
	                                </label>
			                	</div>
							</div>
							<div class="form-group">
                				<label class="control-label" for="name">Is Manadatory For These Account(s):</label>
	                            <?php
	                            echo $this->Form->input('TrainingMembership.account_id', array (
	                                'options'=>$accts,
									'value'=>$account_ids,
	                                'type'=>'select',
	                                'empty'=>true,
	                                'multiple'=>true,
	                                'class'=>'form-select chzn-select',
	                                'data-placeholder'=>'Select Accounts(s)'
	                            ));
	                            ?>
	                            <small>Leave Empty If Training Is For Everyone</small>
        					</div>

							<div class="form-group">
                				<label class="control-label" for="name">Is Manadatory For These Department(s):</label>
	                            <?php
	                            echo $this->Form->input('TrainingMembership.department_id', array (
	                                'options'=>$depts,
									'value'=>$department_ids,
	                                'type'=>'select',
	                                'empty'=>true,
	                                'multiple'=>true,
	                                'class'=>'form-select chzn-select',
	                                'data-placeholder'=>'Select Department(s)'
	                            ));
	                            ?>
	                            <small>Leave Empty If Training Is For Everyone</small>
        					</div>

							<div class="form-group">
                				<label class="control-label" for="name">Is Manadatory For These User(s):</label>
	                            <?php
	                            echo $this->Form->input('TrainingMembership.user_id', array (
	                                'options'=>$users,
									'value'=>$user_ids,
	                                'type'=>'select',
	                                'empty'=>true,
	                                'multiple'=>true,
	                                'class'=>'form-select chzn-select',
	                                'data-placeholder'=>'Select User(s)'
	                            ));
	                            ?>
	                            <small>Leave Empty If Training Is For Everyone Or You Have Selected Department(s) Above</small>
        					</div>
						</div>

						<div class="col-md-4">
							<div class="well">


        						<div class="form-group">
			                        <label class="control-label" for="name">Renewal In Months:</label>
			                        <?php
			                        for($i=0; $i<=48; $i++){
                						$renewal[$i] = $i;
			                        }

									echo $this->Form->input('TrainingMembership.renewal', array (
                						'options'=>$renewal,
			                            'type'=>'select',
			                            'value'=>12,
			                            'class'=>'form-select chzn-select',
			                        ));
			                        ?><label> Months </label><br />
			                        <small>Use "0" If Only Needed Once. </small>
            					</div>
							</div>
						</div>
					</div>

					<div class="clearFix">&nbsp;</div>
		        </div>

				<div class="tab-pane fade in" id="use">
					<table class="table table-striped table-condensed" id="trainingTable">
					<thead>
    						<tr class="tr-heading">
        						<th class="col-md-8">Account(s)</th>
			                    <th class="text-center col-md-2">Required</th>
			                    <th class="text-center col-md-2">Mandatory</th>
			                </tr>
			            </thead>

			            <tbody>
    						<?php
							$acctFound = 0;
			                foreach($trnAccount as $file){
								#pr($file);
								if(!empty($file['ReqAcct']['name'])){
									$acctFound = 1;

									$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
									$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
        							?>
				                    <tr>
            							<td><?=$file['ReqAcct']['name']?></td>
				                        <td class="text-center"><?=$required?></td>
				                        <td class="text-center"><?=$man?></td>
				                    </tr>
				                    <?php
								}
			                }

							if($acctFound == 0){
								?>
								<tr>
									<td colspan="3" class="text-center">No Records Found</td>
								</tr>
								<?php
							}
			                ?>
			            </tbody>
			        </table>
					<table class="table table-striped table-condensed" id="trainingTable">
					<thead>
    						<tr class="tr-heading">
        						<th class="col-md-8">Department(s)</th>
			                    <th class="text-center col-md-2">Required</th>
			                    <th class="text-center col-md-2">Mandatory</th>
			                </tr>
			            </thead>

			            <tbody>
    						<?php
							$acctFound = 0;
			                foreach($trnAccount as $file){
								#pr($file);
								if(!empty($file['ReqDept']['name'])){
									$acctFound = 1;

									$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
									$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
        							?>
				                    <tr>
            							<td><?=$file['ReqDept']['name']?></td>
				                        <td class="text-center"><?=$required?></td>
				                        <td class="text-center"><?=$man?></td>
				                    </tr>
				                    <?php
								}
			                }

							if($acctFound == 0){
								?>
								<tr>
									<td colspan="3" class="text-center">No Records Found</td>
								</tr>
								<?php
							}
			                ?>
			            </tbody>
			        </table>
					<table class="table table-striped table-condensed" id="trainingTable">
					<thead>
    						<tr class="tr-heading">
        						<th class="col-md-8">User(s)</th>
			                    <th class="text-center col-md-2">Required</th>
			                    <th class="text-center col-md-2">Mandatory</th>
			                </tr>
			            </thead>

			            <tbody>
    						<?php
							$acctFound = 0;
			                foreach($trnAccount as $file){
								#pr($file);
								if(!empty($file['ReqUser']['first_name'])){
									$acctFound = 1;

									$required = ($file['TrainingMembership']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
									$man = ($file['TrainingMembership']['is_manditory'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
        							?>
				                    <tr>
            							<td><?=$file['ReqUser']['first_name']?> <?=$file['ReqUser']['last_name']?></td>
				                        <td class="text-center"><?=$required?></td>
				                        <td class="text-center"><?=$man?></td>
				                    </tr>
				                    <?php
								}
			                }

							if($acctFound == 0){
								?>
								<tr>
									<td colspan="3" class="text-center">No Records Found</td>
								</tr>
								<?php
							}
			                ?>
			            </tbody>
			        </table>
				</div>
				<?php
			}
			?>
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
<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>
<?php
$trnFile = $this->Html->url(array('plugin'=>false, 'controller'=>'Trainings', 'action' => 'deleteFile'));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        function addRow(  ) {
	        var rowCount = $('div.questions').length - 1;

	        var value = $("#template").html();
	        //var text = value.replace('{val}', rowCount);
	        $("#quizSet").prepend(value);

	        $("#quizSet :input").each(function(){
	            //var input = $(this); // This is the jquery object of the input, do what you will
	            $(this).attr('name',$(this).attr('name').replace('{val}',rowCount));
	        });

	    }
        $(".chzn-select").chosen({
            allow_single_deselect: true,

        });

        $('.trnRecord').on('click', function () {
			var Id = $(this).attr("data-value");

			$.ajax({
            	type: 'post',
                url: '<?=$trnFile?>/' + Id + '/<?=$this->request->data['Training']['id']?>/.json',
                dataType: "html",
                beforeSend: function(xhr) {
                	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                },
                success: function(response) {
                	console.log(response);
                    $('#files').html(response);
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
		
		$( '#sets-control' ).on( 'click', function() {
            addRow( $( '#sets tr:last' ), 10 );
        });
    });
</script>

<div class="hide" id="template">
    <div class="panel panel-primary questions">
        <div class="panel-heading">
            <ul class="list-inline">
                <li class="col-md-1">
                    <div class="form-group">
                        <label for="inputTitle" class="control-label sr-only">Quiz Order:</label>
                        <?php
                        echo $this->Form->input('TrainingQuiz.{val}.quiz_order', array(
                            'type'=>'text',
                        ));
                        ?>
                    </div>
                </li>

                <li class="col-md-11">
                    <div class="form-group">
                        <label for="inputTitle" class="control-label sr-only">Question:</label>
                        <?php
                        echo $this->Form->input('TrainingQuiz.{val}.question', array(
                            'type'=>'text',
                        ));
                        ?>
                    </div>
                </li>
            </ul>

            <div class="clearfix"></div>
        </div>

        <div class="panel-body">
            <h4>Options</h4>

            <ul class="list-unstyled">
                    <li>
                        <ul class="list-inline">
                            <li class="col-md-1 text-center">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="data[TrainingQuiz][{val}][answer]" id="" value="A" />
                                    </label>
                                </div>
                            </li>

                            <li class="col-md-11">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Answer A:</label>
                                    <?php
                                    echo $this->Form->input('TrainingQuiz.{val}.answer_a', array(
                                        'type'=>'text',
                                    ));
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <ul class="list-inline">
                            <li class="col-md-1 text-center">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="data[TrainingQuiz][{val}][answer]" id="" value="B" />
                                    </label>
                                </div>
                            </li>

                            <li class="col-md-11">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Answer B:</label>
                                    <?php
                                    echo $this->Form->input('TrainingQuiz.{val}.answer_b', array(
                                        'type'=>'text',
                                    ));
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <ul class="list-inline">
                            <li class="col-md-1 text-center">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="data[TrainingQuiz][{val}][answer]" id="" value="C"/>
                                    </label>
                                </div>
                            </li>
                            <li class="col-md-11">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Answer C:</label>
                                    <?php
                                    echo $this->Form->input('TrainingQuiz.{val}.answer_c', array(
                                        'type'=>'text',
                                    ));
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <ul class="list-inline">
                            <li class="col-md-1 text-center">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="data[TrainingQuiz][{val}][answer]" id="" value="D" />
                                    </label>
                                </div>
                            </li>
                            <li class="col-md-11">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Answer D:</label>
                                    <?php
                                    echo $this->Form->input('TrainingQuiz.{val}.answer_d', array(
                                        'type'=>'text',
                                    ));
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <ul class="list-inline">
                            <li class="col-md-1 text-center">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="data[TrainingQuiz][{val}][answer]" id="" value="E"/>
                                    </label>
                                </div>
                            </li>
                            <li class="col-md-11">
                                <div class="form-group">
                                    <label for="inputTitle" class="control-label sr-only">Answer E:</label>
                                    <?php
                                    echo $this->Form->input('TrainingQuiz.{val}.answer_e', array(
                                        'type'=>'text',
                                    ));
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </li>
            </ul>

            <h4>Action Step</h4>
            <div class="form-group">
                <label for="inputTitle" class="control-label sr-only">Action Step:</label>
                <?php
                echo $this->Form->input('TrainingQuiz.{val}.action_step', array(
                    'type'=>'text',
                ));
                ?>
            </div>
        </div>
    </div>

</div>