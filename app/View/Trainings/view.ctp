<?php
    #pr($trnCat);
    #exit;
    http://motherless.com/u/DreamWitness?t=v
?>
<?php
    echo $this->Form->create('Training', array(
    'url' => array('controller'=>'Trainings', 'action'=>'addToAccount', $training['Training']['id']),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
?>
<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Training Details: '.$training['Training']['name']); ?></h2>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-3">
            <div class="box box-warning">
                <div class="box-body">
                    <?php

                    $name = '/files/'.$training['Training']['id'].'/'.$training['Training']['image'];
                    $image = (!empty($training['Training']['image'])) ? $name : 'noTraining.jpg' ;

                    echo $this->Html->image($image, array('class'=>'img-responsive img-thumbnail'));
                    ?>
                </div>
            </div>
            <div class="box box-warning">
                <div class="box-body">
                    <dl>
                        <dt>Status:</dt>
                        <dd><?=$training['Status']['name']?></dd>
                    </dl>

                    <dl>
                        <dt>Last Update:</dt>
                        <dd><?php echo date('F d, Y', strtotime($training['Training']['modified'])); ?></dd>
                    </dl>
                </div>
            </div>
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Categories</h3>
                    <!--
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <ul>
                        <?php
                        foreach($training['TrnCat'] as $cat){
                            ?>
                            <li><?=$cat['TrainingCategory']['name']?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="box box-warning">
                <div class="box-body">
                    <dl>
                        <dt>Name:</dt>
                        <dd><?=$training['Training']['name']?></dd>
                    </dl>

                    <dl>
                        <dt>Description:</dt>
                        <dd><?=$training['Training']['description']?></dd>
                    </dl>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Training Files</h3>
                    <!--
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <table class="table table-striped table-condensed" id="trainingTable">
                        <thead>
                            <tr class="tr-heading">
                                <th>File</th>
                                <th>File Type</th>
                                <th>File Size</th>
                                <th>Runtime</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach($training['TrainingFile'] as $file){

                                $filePath = filesize(WWW_ROOT .'/files/'.$training['Training']['id'].'/'.$file['file']);
                                $fileSize = human_filesize($filePath);
                                ?>
                                <tr>
                                    <td><?=$file['human_name']?></td>
                                    <td><?=$file['file_type']?></td>
                                    <td><?=$fileSize?></td>
                                    <td><?=$file['runtime']?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-body">
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
                                'value'=>12,
                                'class'=>'form-select chzn-select',
                            ));
                            ?><label> Months </label><br />
                            <small>Use "0" If Only Needed Once. </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="name">Training For These Department(s) Only:</label>
                        <div class="col-sm-8">
                            <?php
                            echo $this->Form->input('department_id', array (
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
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="name">Training For These User(s) Only:</label>
                        <div class="col-sm-8">
                            <?php
                            echo $this->Form->input('user_id', array (
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

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <?php
                            if(!empty($trn['TrainingMembership'][0]['id'])){
                                echo $this->Html->link(
                                    '<i class="fa fa-minus fa-fw"></i> Remove This Training From My Account',
                                    array('controller'=>'Trainings', 'action'=>'removeFromAccount', $training['TrainingMembership'][0]['id']),
                                    array('escape'=>false, 'class'=>'btn btn-danger')
                                );
                            }else{
                                echo $this->Form->button(
                                    '<i class="fa fa-plus fa-fw"></i> Add This Training To My Account',
                                    array('type'=>'submit', 'class'=>'btn btn-success')
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
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
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>