<?php 

echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobs', 'action'=>'edit', 'admin'=>true), 
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('id', array('value'=>$id));
echo $this->Form->hidden('group_id', array('value'=>$jobs['Job']['group_id']));
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
                <label class="col-sm-4 control-label" for="name">Job Name:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('name', array (
                        'type'=>'text',
                        'placeholder' => 'Job Name',
                        'value'=>$jobs['Job']['name']
                    ));?>
                </div>
             </div>
             
             <div class="form-group">
                <label class="col-sm-4 control-label" for="description">Job Description:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('description', array (
                        'type'=>'textarea',
                        'placeholder' => 'Job Description',
                        'value'=>$jobs['Job']['description']
                    ));?>
                </div>
             </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="salary">Salary Range:</label>
                <div class="col-sm-8">
                    <?php echo $this->Form->input('salary_range', array (
                        'type'=>'text',
                        'placeholder' => 'Enter A Salary Range. Can be numbers or text.',
                        'value'=>$jobs['Job']['salary_range']
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
        $(".chosen-select").chosen();
    });
</script>  