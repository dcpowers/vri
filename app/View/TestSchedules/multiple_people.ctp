<?php
echo $this->Form->create('TestSchedule', array(
    'url' => array('controller'=>'TestSchedules', 'action'=>'MultiplePeople', 'member'=>true, $test_id), 
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('test_id', array('value'=>$test_id));
?>

<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div>            <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="form-group">
                <label class="control-label" for="name">Name:</label>
                    <?php echo $this->Form->input('name', array (
                        'type'=>'text',
                        'data-placeholder'=>'Create A Name',
                    ));?>
                    
                    <small>Example Name: John Doe 6 month review</small>
                
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="name">Role:</label>
                            <?php echo $this->Form->input('0.AssignedTest.test_role_id', array (
                                'options'=>$roleRequired,
                                'multiple'=>false,
                                'disabled'=>true,
                                'empty'=>false,
                                'data-placeholder'=>'Select Role',
                                'value'=>5
                            ));
                            echo $this->Form->hidden('0.AssignedTest.test_role_id', array('value'=>5));
                            ?>
                        
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="name">Role:</label>
                            <?php echo $this->Form->input('1.AssignedTest.test_role_id', array (
                                'options'=>$roleRequired,
                                'multiple'=>false,
                                'disabled'=>true,
                                'empty'=>false,
                                'data-placeholder'=>'Select Role',
                                'value'=>2
                            ));
                            echo $this->Form->hidden('1.AssignedTest.test_role_id', array('value'=>2));
                            ?>
                        
                    </div>
                    <?php
                    for($i=2;$i<=7;$i++){
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="name">Role:</label>
                                <?php echo $this->Form->input($i.'.AssignedTest.test_role_id', array (
                                    'options'=>$roles,
                                    'multiple'=>false,
                                    'empty'=>true,
                                    'data-placeholder'=>'Select Role'
                                ));?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="name">User:</label>
                        <?php echo $this->Form->input('0.AssignedTest.user_id', array (
                            'options'=>$users,
                            'multiple'=>false,
                            'empty'=>false,
                            'data-placeholder'=>'Select User'
                        ));?>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="name">User:</label>
                        <?php echo $this->Form->input('1.AssignedTest.user_id', array (
                            'options'=>$users,
                            'multiple'=>false,
                            'empty'=>false,
                            'data-placeholder'=>'Select User'
                        ));?>
                    </div>    
                    <?php
                    for($j=2;$j<=7;$j++){
                        ?>
                        <div class="form-group">
                            <label class="control-label" for="name">User:</label>
                                <?php echo $this->Form->input($j.'.AssignedTest.user_id', array (
                                    'options'=>$users,
                                    'multiple'=>false,
                                    'empty'=>true,
                                    'data-placeholder'=>'Select User'
                                ));?>
                            
                        </div>
                        <?php
                    }
                    ?>
                </div>
            
        </div>
    </div>
</div><!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
            <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end();?>    

<script type="text/javascript">
    jQuery(window).ready( function($) {
    });
</script> 