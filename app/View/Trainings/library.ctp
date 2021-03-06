<?php
    #pr($trainings);
    #exit;
?>
<div class="training index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Training Library:</h6>
            <h3 class="dashhead-title"><i class="fa fa-list-alt fa-fw"></i> Training</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Trainings/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Trainings/menu' );?>
        </div>
        <div class="flextable-item">
            <?php echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php echo $this->element( 'Trainings/search_filter', array('cat'=>$cat, 'trncat'=>$trnCat) );?>
        </div>
    </div>

    <table class="table table-striped table-condensed" id="trainingTable">
        <thead>
            <tr class="tr-heading">
                <th class="col-md-3">
                    <?php echo $this->Paginator->sort('name', 'Name');?>
                    <?php if ($this->Paginator->sortKey() == 'name'): ?>
                        <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                    <?php else: ?>
                        <i class='fa fa-sort'></i>
                    <?php endif; ?>
                </th>

                <th class="col-md-2 text-center">Has Files</th>
                <th class="col-md-2 text-center">Has Quiz</th>
				<th class="col-md-1 text-center">Status</th>
                <th class="col-md-3">Categories</th>
                <th class="col-md-1"></th>
            </tr>
        </thead>

        <tbody>
            <?php
            #pr($trainings);
            $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
            
            foreach($trainings as $trn){
            	#pr($trn);
            	#exit;
            	$hasFiles = (!empty($trn['TrainingFile'])) ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-ban fa-2x text-danger"></i>' ;
            	$hasQuiz = (!empty($trn['TrainingQuiz'])) ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-ban fa-2x text-danger"></i>' ;
            	$status = ($trn['Training']['is_active'] == 1) ? '<i class="fa fa-check fa-2x text-success"></i>' : '<i class="fa fa-ban fa-2x text-danger"></i>' ;
            	
				?>
                <tr>
                    <td>
						<?php
						echo $this->Html->link(
                            $trn['Training']['name'],
                            array('controller'=>'Trainings', 'action'=>'view', $trn['Training']['id']),
                            array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal' )
                        );
                        ?>
					</td>

                    <td class="text-center"><?=$hasFiles?></td>
                    <td class="text-center"><?=$hasQuiz?></td>
                    <td class="text-center"><?=$status?></td>

                    <td>
                        <?php
                        if(!empty($trn['TrnCat'])){
                            ?>
                            <ul>
                                <?php
                                foreach($trn['TrnCat'] as $cat){
                                    ?>
                                    <li>
										<?php
										echo $this->Html->link(
				                            $cat['TrainingCategory']['name'],
				                            array('controller'=>'Trainings', 'action'=>'library', $cat['TrainingCategory']['id']),
				                            array('escape'=>false)
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

                    <td>
                        <ul class="list-inline">
							<li>
	                            <?php
								if(AuthComponent::user('Role.permission_level') >= 60 || $trn['Training']['author_id'] == AuthComponent::user('id')){
	                                echo $this->Html->link(
                                        '<i class="fa fa-pencil fa-fw"></i>',
                                        array('controller'=>'Trainings', 'action'=>'edit', $trn['Training']['id']),
                                        array('escape'=>false, 'class'=>'btn btn-primary btn-xs', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Edit Training', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
                                    );
                                }else{
									echo '&nbsp;';
								}
                            	?>
							</li>
                        </ul>
                    </td>
                </tr>
                <?php

            }
            ?>
        </tbody>
    </table>
    <?php echo $this->element( 'paginate' );?>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $("#myModalBig").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });

        $(".modal-wide").on("show.bs.modal", function() {
          var height = $(window).height() - 200;
          $(this).find(".modal-body").css("max-height", height);
        });
     });
</script>