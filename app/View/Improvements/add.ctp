<?php
    echo $this->Form->create('Improvement', array(
    'url' => array('controller'=>'Improvements', 'action'=>'add'), 
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
?>

<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Suggest Improvement: '); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="control-label" for="name">Idea/Suggestion/Improvements:</label>
        <?php 
        echo $this->Form->input('idea', array (
            'type'=>'textarea',
            'placeholder' => 'Idea/Suggestion/Improvements'
        ));
        ?>
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