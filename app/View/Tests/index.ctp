<?php
    $editable_group_id = AuthComponent::user('AccountUser.0.account_id');
?>
<div class="training index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">All Testing:</h6>
            <h3 class="dashhead-title"><i class="fa fa-clipboard fa-fw"></i> Testing</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Tests/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Tests/menu' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php #echo $this->element( 'Trainings/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>

	<!-- Nav tabs -->
    <ul class="nav nav-pills" role="tablist">
        <li role="presentation" class="active" ><a href="#assessment" aria-controls="assessment" role="tab" data-toggle="tab">Assessments</a></li>
        <li role="presentation"><a href="#survey" aria-controls="survey" role="tab" data-toggle="tab">Surveys</a></li>
        <li role="presentation"><a href="#evaluation" aria-controls="evaluation" role="tab" data-toggle="tab">Evaluations</a></li>
		<?php
        if(AuthComponent::user('Role.permission_level') >= 60){
            ?>
            <li>
				<?php
                        echo $this->Html->link(
                            '<i class="fa fa-info-circle fa-lg text-success fa-fw"></i><span class="text text-success">Custom Testing</span>',
                            array('controller'=>'TestGroups', 'action'=>'index'),
                            array('escape'=>false)
                        );
                ?>
            </li>
			<?php
        }
        ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" style="margin-top: 30px; border: 0px">
        <div role="tabpanel" class="tab-pane active fade in" id="assessment">
            <?php

            foreach($assessments as $test){
            	?>
				<div class="media">
					<div class="media-left media-middle">
				    	<?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;

                        echo $this->Html->link(
                            $this->Html->image($image, array('class'=>'media-object', 'style'=>'width: 136px;')),
                            array('controller'=>'tests', 'action'=>'view_single', $test['Test']['id']),
                            array('escape' => false)
                        );
                        ?>

						<?php
						if($test['Test']['account_id'] == $editable_group_id){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm')
                                );
                                ?>
                            </div>
                            <?php
                        }
                        ?>
				  	</div>

					<div class="media-body">
						<?php
                        echo $this->Html->link(
                            '<h3 class="media-heading">'. $test['Test']['name'].'</h3>',
                            array('controller'=>'tests', 'action'=>'view_single', $test['Test']['id']),
                            array('escape' => false) );
                        ?>

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info fa-fw"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-eye fa-fw"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'action'=>'view_single', $test['Test']['id']),
                                    array('escape' => false) );
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-calendar fa-fw"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'action'=>$test['Test']['schedule_type'], $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                                );
                                ?>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
				  	</div>
				</div>
                <hr/>
                <?php
            }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="survey">
            <?php
            foreach($surveys as $test){
                ?>
                <div class="media">
					<div class="media-left media-middle">
                        <?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;

                        echo $this->Html->link(
                            $this->Html->image($image, array('class'=>'media-object', 'style'=>'width: 136px;')),
                            array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false)
                        );

                        ?>

                        <?php
                        if($test['Test']['account_id'] == $editable_group_id[0]){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm')
                                );
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="media-body">
                        <?php
                        echo $this->Html->link(
                            '<h2 class="no-margin">'. $test['Test']['name'].'</h2>',
                            array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) );
                        ?>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info fa-fw"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-eye fa-fw"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                                    array('escape' => false) );
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-calendar fa-fw"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'action'=>'Type', $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModalBig')
                                );
                                ?>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="evaluation">
            <?php
            foreach($evaluations as $test){
                ?>
                <div class="row">
                    <div class="col-md-2">
                        <?php
                        $image = (!empty($test['Test']['logo'])) ? $test['Test']['logo'] : 'testing.jpg' ;

                        echo $this->Html->link(
                            $this->Html->image($image, array('class'=>'img-responsive img-thumbnail')),
                            array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false)
                        );

                        ?>

                        <?php
                        if($test['Test']['group_id'] == $editable_group_id[0]){
                            ?>
                            <div class="col-md-12 text-center" style="margin-top: 20px;">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-pencil-square-o"></i><span class="text">Edit</span>',
                                    array('controller'=>'TestGroups', 'action'=>'index', $test['Test']['id']),
                                    array('escape' => false, 'class'=>'btn btn-success btn-sm')
                                );
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="col-md-10">
                        <?php
                        echo $this->Html->link(
                            '<h2 class="no-margin">'. $test['Test']['name'].'</h2>',
                            array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                            array('escape' => false) );
                        ?>
                        <div class="pull-right">
                            <p>
                                <strong>Credits: </strong>
                                <small><?php echo $test['Test']['credits']; ?></small>
                            </p>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active" ><a href="#assessment#about" aria-controls="assessment" role="tab" data-toggle="tab"><i class="fa fa-info"></i><span class="text">About</span></a></li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-eye"></i><span class="text">View</span>',
                                    array('controller'=>'tests', 'action'=>'view_group', $test['Test']['id']),
                                    array('escape' => false) );
                                ?>
                            </li>
                            <li role="presentation">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-calendar"></i><span class="text">Schedule</span>',
                                    array('controller'=>'TestSchedules', 'action'=>$test['Test']['schedule_type'], $test['Test']['id']),
                                    array('escape' => false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
                                );
                                ?>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in sub-content" id="about">
                                <p><?php echo $test['Test']['description'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
            }
            ?>
        </div>
     </div>
</div>