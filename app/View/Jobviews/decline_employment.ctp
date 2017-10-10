<?php 
echo $this->Form->create('JobOffer', array(
    'url' => array('controller'=>'jobviews', 'action'=>'declineEmployment', 'member'=>true), 
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
echo $this->Form->hidden('JobOffer.id', array('value'=>$jobOfferId));


?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div> <!-- Modal Header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="name">Feedback/Comments:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('notes', array (
                        'type'=>'textarea',
                        'placeholder' => 'Please provide feedback'
                        //'value'=>$user[0]['User']['first_name']
                    ));?>
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
        var currentValue = $('#currentValue');

        $(".chosen-select").chosen();
    });
</script>   