<?php
    echo $this->Form->create('Training', array(
        'url' => array('controller'=>'Trainings', 'action'=>'editAccount', $this->request->data['Training']['training_id']), 
        'role'=>'form',
        'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
    
    echo $this->Form->hidden('training_id', array('value'=>$this->request->data['Training']['training_id']));
    echo $this->Form->hidden('account_id', array('value'=>$this->request->data['Training']['account_id']));

?>

<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit Training Settings: '); ?></h2>
</div>

<div class="modal-body">
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
                'value'=>$this->request->data['Training']['renewal'],
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
                'value'=>$this->request->data['Training']['department_id'],
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
                'value'=>$this->request->data['Training']['user_id'],
                'multiple'=>true,
                'class'=>'form-select chzn-select',
                'data-placeholder'=>'Select User(s)'
            ));
            ?>
            <small>Leave Empty If Training Is For Everyone Or You Have Selected Department(s) Above</small>
        </div>    
    </div>
</div>

<div class="modal-footer">
    <?php 
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Cancel',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    ); 
    ?>
    
    <?php 
    echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    ); 
    ?>
</div>
<?php echo $this->Form->end();?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>