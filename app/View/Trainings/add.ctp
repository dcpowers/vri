<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'add'),
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
    <h2><?php echo __('Add Training:'); ?></h2>
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
										array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
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
										array('value'=>1, 'legend' => false, 'separator'=>'</label><label class="radio-inline">')
									);
									?>
								</label>
							</div>
							
							<div class="form-group">
								<label class="control-label" for="name">Category(s):</label>
	                            <?php
	                            echo $this->Form->input('TrnCat.training_category_id', array (
	                                'options'=>$TrnCategory,
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
			</div>
			<div class="tab-pane fade" id="quiz">
                <h4>
                    Quiz
                    <span id="sets-control">
                        <a class="btn btn-default btn-xs append-row"><i class="fa fa-plus fa-fw"></i>Add Question</a>
                    </span>
                </h4>
                <div id="quizSet">
                    
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