<?php 
echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobseekers', 'action'=>'update_applicant', 'member'=>true), 
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('ApplyJob.id', array('value'=>$id));
?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="name">Update Status:</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Chosen->select('ApplyJob.status', 
                        array($settings['applicant_status']),
                        array(
                            'default' => $key, 
                            'empty' => 'Select A Status', 
                            'data-placeholder' => 'Select A Status', 
                            'multiple' => false, 
                            'class'=>'col-md-12'
                        )
                    );
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="notes">Notes/Comments:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('ApplyJob.notes', array (
                        'type'=>'textarea',
                        'placeholder' => 'Enter Notes/Comments'
                        //'value'=>$user[0]['User']['first_name']
                    ));?>
                    <small>Job Seekers Will Be Able To View Notes/Comments</small>
                    <?php 
                    if(!empty($info[0]['ApplyJob']['notes'])){
                        echo $this->Form->input('ApplyJob.prev_notes', array (
                            'type'=>'textarea',
                            'value'=>$info[0]['ApplyJob']['notes'],
                            'readonly'=>'readonly'
                        ));
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
            <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end();?>    

<script language="JavaScript">
    $(document).ready( function() {
        $(".chosen-select").chosen();
    });
</script>    