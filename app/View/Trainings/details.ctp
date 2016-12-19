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
    foreach($trainings as $key=>$trn){
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
                                    '<i class="fa fa-book fa-fw"></i> Classroom Training',
                                    '#'.$trn['Training']['id'].'classroom',
                                    array('escape'=>false, 'aria-controls'=>'classroom', 'role'=>'tab', 'data-toggle'=>'tab')
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
                                                            <th class="col-sm-5">File</th>
                                                            <th class="col-sm-1">Type</th>
                                                            <th class="col-sm-1">Size</th>
                                                            <th class="col-sm-1">Runtime</th>
                                                            <th class="col-sm-2">Updated</th>
                                                            <th class="col-sm-2">Actions</th>
                                                        </tr>
                                                    </thead>
                                                        
                                                    <tbody>
                                                        <?php
                                                        foreach($trn['Training']['TrainingFile'] as $file){
                                                            $filePath = filesize(WWW_ROOT .'/files/'.$file['training_id'].'/'.$file['file']);
                                                            $fileSize = human_filesize($filePath);
                                                            ?>
                                                            <tr>
                                                                <td><?=$file['human_name']?></td>
                                                                <td><?=$file['file_type']?></td>
                                                                <td><?=$fileSize?></td>
                                                                <td><?=$file['runtime']?></td>
                                                                <td><?php echo date('M d, Y', strtotime($file['modified'])); ?></td>
                                                                <td></td>
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
                                            <th class="col-sm-4">Name</th>
                                            <th class="col-sm-2">Instructor</th>
                                            <th class="col-sm-2">Date</th>
                                            <th class="col-sm-1 text-center">Attended</th>
                                            <th class="col-sm-1 text-center">Total</th>
                                            <th class="col-sm-2">Actions</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php 
                                        foreach($trn['Training']['TrainingClassroom'] as $class){
                                            #pr($class);
                                            
                                            ?>
                                            <tr>
                                                <td><?=$class['name']?></td>
                                                <td><?=$class['Instructor']['first_name']?> <?=$class['Instructor']['last_name']?></td>
                                                <td><?php echo date('M d, Y', strtotime($class['date'])); ?></td>
                                                <td class="text-center"><?=$class['attend']?></td>
                                                <td class="text-center"><?=$class['total']?></td>
                                                <td>
                                                    <?php
                                                    echo $this->Html->link(
                                                        'Roster',
                                                        array('controller'=>'Trainings', 'action'=>'viewRecords', $trn['Training']['id']),
                                                        array('escape'=>false, 'class'=>'btn btn-primary btn-xs', 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
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
                                        'url' => array('controller'=>'Trainings', 'action'=>'editAccount', $this->request->data[$key]['Training']['training_id']), 
                                        'role'=>'form',
                                        'class'=>'form-horizontal',
                                        'inputDefaults' => array(
                                            'label' => false,
                                            'div' => false,
                                            'class'=>'form-control',
                                            'error'=>false
                                        )
                                    ));
                                        
                                    echo $this->Form->hidden('training_id', array('value'=>$this->request->data[$key]['Training']['training_id']));
                                    echo $this->Form->hidden('account_id', array('value'=>$this->request->data[$key]['Training']['account_id']));

                                    ?>

                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <div class="checkbox">
                                                <label>
                                                    <?php 
                                                    echo $this->Form->checkbox('is_required', array());
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
                                                'value'=>$this->request->data[$key]['Training']['renewal'],
                                                'class'=>'form-select chzn-select',
                                            ));
                                            ?>
                                            <label> Months </label><br />
                                            <small>Use "0" If Only Needed Once. </small>
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
                                                'value'=>$this->request->data[$key]['Training']['department_id'],
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
                                                'value'=>$this->request->data[$key]['Training']['user_id'],
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

<?php
function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>

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