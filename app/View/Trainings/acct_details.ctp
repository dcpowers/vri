<?php
    
?>
<div class="modal-header modal-header-primary">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Training Details: <small>' . $trn['Training']['name'].'</small>'); ?></h2>
</div>
<div class="modal-body">
	<ul class="list-inline">
    	<li><strong>Required: </strong><?=$settings[$trn['TrainingMembership']['is_required']]?></li>
        <li><strong>Manditory: </strong><?=$settings[$trn['TrainingMembership']['is_manditory']]?></li>
    </ul>

    <ul class="nav nav-tabs" role="tablist">
    	<li class="active">
        	<?php
            echo $this->Html->link(
            	'<i class="fa fa-info fa-fw"></i> Info',
                '#'.$trn['Training']['id'].'home',
                array('escape'=>false, 'aria-controls'=>'home', 'role'=>'tab', 'data-toggle'=>'tab')
            );
            ?>
        </li>
        
        <li>
        	<?php
            echo $this->Html->link(
            	'<i class="fa fa-files-o fa-fw"></i> Records',
                '#'.$trn['Training']['id'].'records',
                array('escape'=>false, 'aria-controls'=>'records', 'role'=>'tab', 'data-toggle'=>'tab')
            );
        	?>
        </li>
		<!--
        <li>
        	<?php
            echo $this->Html->link(
            	'<i class="fa fa-book fa-fw"></i> Classroom Training',
                '#'.$trn['Training']['id'].'classroom',
                array('escape'=>false, 'aria-controls'=>'classroom', 'role'=>'tab', 'data-toggle'=>'tab')
            );
            ?>
        </li>

        <li>
        	<?php
            echo $this->Html->link(
            	'<i class="fa fa-print fa-fw"></i> Print Training Roster',
                array('controller'=>'Trainings', 'action'=>'roster', $trn['Training']['id'], 'ext'=>'pdf'),
                array('escape'=>false)
            );
        	?>
        </li>
		-->
        <?php
        if(AuthComponent::user('Role.permission_level') >= 30 && $trn['TrainingMembership']['is_manditory'] == 0){
        	?>
            <li>
            	<?php
                echo $this->Html->link(
                	'<i class="fa fa-cogs fa-fw"></i> Settings',
                    '#'.$trn['Training']['id'].'settings',
                    array('escape'=>false, 'aria-controls'=>'settings', 'role'=>'tab', 'data-toggle'=>'tab')
                );
                ?>
            </li>
            <?php
        }
        ?>
    </ul>

    <div class="tab-content">
    	<div role="tabpanel" class="tab-pane fade in active" id="<?=$trn['Training']['id']?>home">
        	<div class="well">
            	<dl>
                	<dt>Description:</dt>
                    <dd><?=$trn['Training']['description']?></dd>
                </dl>

                <dl>
                	<dt>Files:</dt>
                    <dd>
                    	<?php
                        if(!empty($trn['Training']['TrainingFile'])){
                        	?>
                            <table class="table table-striped table-condensed" id="trainingTable">
                            	<thead>
                                	<tr>
                                    	<th>File</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Runtime</th>
                                        <th>Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                	<?php
                                    foreach($trn['Training']['TrainingFile'] as $file){
                                    	$filePath = filesize(WWW_ROOT .'/files/training/'.$file['training_id'].'/'.$file['file']);
                                        $fileSize = human_filesize($filePath);
                                        ?>
                                        <tr>
                                        	<td><?=$file['human_name']?></td>
                                            <td><?=$file['file_type']?></td>
                                            <td><?=$fileSize?></td>
                                            <td><?=$file['runtime']?></td>
                                            <td><?php echo date('M d, Y', strtotime($file['modified'])); ?></td>
                                            <td>
												<?php
												$arr_ext = array('mp4', 'ppt', 'zip', 'pdf', 'mp3', 'tiff', 'bmp', 'doc', 'docx', 'pptx', 'txt');

												if(AuthComponent::user('Role.permission_level') >= 30 && in_array($file['file_type'], $arr_ext)){
                                                    ?>
													<ul class="list-inline">
														<li>
															<?php
															echo $this->Html->link(
																'<i class="fa fa-download fa-fw fa-lg"></i>',
                            									array('controller'=>'Trainings', 'action'=>'download', $file['id']),
                            									array('escape'=>false)
                        									);
															?>
														</li>
														<?php
														if($file['file_type'] == 'mp4'){
															?>
															<li>
																<?php
																echo $this->Html->link(
																	'<i class="fa fa-play-circle fa-fw fa-lg"></i>',
                            										array('controller'=>'Trainings', 'action'=>'play', $file['training_id']),
                            										array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
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
                    </dd>
                </dl>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="<?=$trn['Training']['id']?>classroom">
        	<div class="pull-right">
            	<ul class="list-inline">
                	<li>
                    	<?php
                        echo $this->Html->link(
                        	'<i class="fa fa-plus fa-fw"></i> Create Classroom',
                            array('controller'=>'Trainings', 'action'=>'createClassroom', $trn['Training']['id'], $trn['Training']['name']),
                            array('escape'=>false, 'data-target'=>'#myModal', 'data-toggle'=>'modal')
                        );
                        ?>
                    </li>
                </ul>
            </div>

			<table class="table table-striped table-condensed" id="trainingTable">
            	<thead>
                	<tr>
                    	<th class="col-sm-4">Instructor</th>
                        <th class="col-sm-4">Class Date</th>
                        <th class="col-sm-4 text-center">Total</th>
                    </tr>
                </thead>

                <tbody>
                	<?php
					foreach($classRooms as $class){

						$count = count($class['TrainingClassroomDetail']);

						$instructor = (empty($class['Instructor']['first_name']) || empty($class['Instructor']['last_name'])) ? 'Undefined' : $class['Instructor']['first_name'].' '.$class['Instructor']['last_name'] ;
						?>
                        <tr>
                        	<td><?=$instructor?></td>
                            <td><?php echo date('M d, Y', strtotime($class['TrainingClassroom']['date'])); ?></td>
                            <td class="text-center">
								<?php
								echo $this->Html->link(
                                	$count,
                                	array('controller'=>'Trainings', 'action'=>'classroomDetails', $class['TrainingClassroom']['id']),
                                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                                );
								?>
							</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        if(AuthComponent::user('Role.permission_level') >= 30 && $trn['TrainingMembership']['is_manditory'] == 0){
        	?>
            <div role="tabpanel" class="tab-pane fade" id="<?=$trn['Training']['id']?>settings">
            	<?php
                echo $this->Form->create('Training', array(
                	'url' => array('controller'=>'Trainings', 'action'=>'editAccount', $trn['TrainingMembership']['training_id']),
                    'role'=>'form',
                    'class'=>'form-horizontal',
                    'inputDefaults' => array(
                    	'label' => false,
                        'div' => false,
                        'class'=>'form-control',
                        'error'=>false
                    )
                ));

                echo $this->Form->hidden('training_id', array('value'=>$trn['TrainingMembership']['training_id']));
                echo $this->Form->hidden('account_id', array('value'=>$trn['TrainingMembership']['account_id']));

                ?>

                <div class="form-group">
                	<div class="col-sm-offset-4 col-sm-8">
                		<div class="checkbox">
                        	<label>
                            	<?php
								$checked = ($trn['TrainingMembership']['is_required'] == 1) ? 1 : 0 ;
                                echo $this->Form->checkbox('is_required', array('checked'=>$checked));
                                ?>
                                Is Required Training
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                	<label class="col-sm-4 control-label" for="name">Renewal In Months:</label>
                    <div class="col-sm-8">
                    	<?php
                        for($i=0; $i<=48; $i++){
                        	$renewal[$i] = $i;
                        }

                        echo $this->Form->input('renewal', array (
                        	'options'=>$renewal,
                            'type'=>'select',
                            'value'=>$trn['TrainingMembership']['renewal'],
                            'class'=>'form-select chzn-select',
                        ));
                        ?>
                        <label> Months </label><br />
                        <small>Use "0" If Only Needed Once. Will Show Expiring In 50 Years On Training Records.</small>
                    </div>
                </div>

                <div class="form-group">
                	<label class="col-sm-4 control-label" for="name">Use Training For These Department(s) Only:</label>
                    <div class="col-sm-8">
                    	<?php
                        echo $this->Form->input('department_id', array (
                        	'options'=>$depts,
                            'type'=>'select',
                            'empty'=>true,
                            'value'=>$trn['TrainingMembership']['department_id'],
                            'multiple'=>true,
                            'class'=>'form-select chzn-select',
                            'data-placeholder'=>'Select Department(s)'
                        ));
                        ?>
                        <small>Leave Empty If Training Is For Everyone</small>
                    </div>
                </div>

                <div class="form-group">
                	<label class="col-sm-4 control-label" for="name">Use Training For These User(s) Only:</label>
                    <div class="col-sm-8">
                    	<?php
                        echo $this->Form->input('user_id', array (
                        	'options'=>$users,
                            'type'=>'select',
                            'empty'=>true,
                            'value'=>$trn['TrainingMembership']['user_id'],
                            'multiple'=>true,
                            'class'=>'form-select chzn-select',
                            'data-placeholder'=>'Select User(s)'
                        ));
                        ?>
                        <small>Leave Empty If Training Is For Everyone Or You Have Selected Department(s) Above</small>
                    </div>
                </div>

                <div class="form-group">
                	<div class="col-sm-8 col-sm-offset-4">
                    	<?php
                        echo $this->Form->button(
                        	'<i class="fa fa-save fa-fw"></i> Save',
                            array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
                        );
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end();?>
            </div>
            <?php
        }
        ?>
        <div role="tabpanel" class="tab-pane fade" id="<?=$trn['Training']['id']?>records">
            <table class="table table-striped table-condensed" id="trainingTable">
                <thead>
                    <tr>
                        <th class="col-sm-4">User</th>
                        <th class="col-sm-4">Status</th>
                        <th class="col-sm-4 text-center">Expires On</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    foreach($records as $v){
						$expires = (is_null($v['expires_on'])) ? null : date('M d, Y', strtotime($v['expires_on'])) ;
                        ?>
                        <tr>
                            <td><?=$v['User']['first_name']?> <?=$v['User']['last_name']?></td>
							<td><span class="label label-<?=$v['tblrow']?>"><?=$v['status']?></span></td>
                            <td class="text-center"><?=$expires?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>   
</div>
<div class="modal-footer">
    <?php
    echo $this->Html->link(
        'Close Window',
        '#',
        array('escape'=>false, 'class'=>'btn btn-primary pull-left', 'data-dismiss'=>'modal')
    );
    ?>
</div>

<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>

<script type=text/javascript>
	jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $(".chzn-select-noDeselect").chosen({
            allow_single_deselect: false
        });
     });
</script>