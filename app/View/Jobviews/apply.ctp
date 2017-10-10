<?php 
echo $this->Form->create('JobQuestionAnswer', array(
    'url' => array('controller'=>'jobviews', 'action'=>'apply', $id, $match), 
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
            <p>Please answer the following questions, then click save.</p>
            <?php
            
            foreach($questions as $item){
                
                ?>
                <div class="form-group">
                    <label class="col-md-5"><?=$item['question']?></label>
                    <div class="col-md-7">
                        <?php echo $this->Form->hidden('answer.'.$item['id'], array('value'=>'')); ?>
                        <label class="fancy-radio">
                            <input type="radio" name="data[answer][<?=$item['id']?>]" value="1">
                            <span><i></i>Yes</span>
                        </label>
                            
                        <label class="fancy-radio">
                            <input type="radio" name="data[answer][<?=$item['id']?>]" value="2">
                            <span><i></i>No</span>
                        </label>
                    </div>
                </div>
                <?php
            }
            ?>
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