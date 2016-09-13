<?php
    #pr($trainings);
    #exit; 
?>
<div class="training index">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Account Training:</h6>
            <h3 class="dashhead-title"><i class="fa fa-book fa-fw"></i> Training</h3>
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
            <?php #echo $this->element( 'Trainings/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Trainings/settings');?>
            <?php #echo $this->element( 'Trainings/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>
    
    <?php
    #pr($trainings);
    #exit;
    $c=0;
    foreach($trainings as $trn){
        
        ?>
        <div class="box box-orange" style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
            <div class="box-body" style="padding: 15px;">
                <div class="row">
                    <div class="col-sm-2" style="margin-top: 20px;">
                        <?php   
                        $name = '/files/'.$trn['Training']['id'].'/'.$trn['Training']['image'];
                        
                        $image = (!empty($trn['Training']['image'])) ? $name : 'noTraining.jpg' ;
                                
                        echo $this->Html->image($image, array('class'=>'img-thumbnail '));
                        ?>
                    </div><!--End col 1 -->
                    
                    <div class="col-sm-10">
                        <?php
                        echo $this->Html->link(
                            '<h3>'.$trn['Training']['name'].'</h3>',
                            array('controller'=>'Trainings', 'action'=>'viewRecords', $trn['Training']['id']),
                            array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
                        );
                        ?>
                        
                        <ul class="nav nav-pills" style="margin-bottom: 20px;">
                            <li class="active"><a href="#<?=$c?>info" data-toggle="tab"><i class="fa fa-info-circle fa-fw"></i> Info</a></li>
                            <li><a href="#<?=$c?>records" data-toggle="tab"><i class="fa fa-files-o fa-fw"></i> Records</a></li>
                            <?php
                            if(AuthComponent::user('Role.permission_level') >= 30 && $trn['TrainingMembership']['is_manditory'] == 0){
                                ?>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-cogs fa-fw"></i> Settings',
                                        array('controller'=>'Trainings', 'action'=>'editAccount', $trn['Training']['id']),
                                        array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
                                    );
                                    ?>                                    
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        
                        <div class="well">
                            <div class="row">
                                <div class="col-sm-6">
                                    <dl>
                                        <dt>Description:</dt>
                                        <dd><?=$trn['Training']['description']?></dd>
                                    </dl>
                                </div><!--End col 6 -->
                                            
                                <div class="col-sm-6">
                                    <ul class="list-inline">
                                        <li><strong>Required: </strong><?=$settings[$trn['TrainingMembership']['is_required']]?></li>
                                        <li><strong>Corporate Required: </strong><?=$settings[$trn['TrainingMembership']['is_manditory']]?></li>
                                    </ul>
                                        
                                    <dl>
                                        <dt>Available Files</dt>
                                        <?php
                                        if(!empty($trn['Training']['TrainingFile'])){
                                            foreach($trn['Training']['TrainingFile'] as $file){
                                                ?>
                                                <dd><?=$file['human_name']?></dd>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </dl>
                                </div><!--End col 6 -->
                            </div>
                        </div>
                    </div>
                </div><!--End row -->
            </div><!-- End Box Body -->
        </div>
        <?php
        $c++;
    }
    ?>
    
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
        
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
        $(".chzn-select-noDeselect").chosen({
            allow_single_deselect: false
        });
     });
</script>