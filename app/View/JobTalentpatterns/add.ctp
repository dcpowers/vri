<?php 
echo $this->Form->create('JobTalentpattern', array(
    'url' => array('controller'=>'jobTalentpatterns', 'action'=>'add', 'member'=>true), 
    'role'=>'form',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control input-lg',
        'error'=>false
    )
));
?>
<div class="container">
    <h2 class="title"><i class="fa fa-list-ul"></i> <span class="text">New Talent Pattern</span></h2>
    <hr class="solidOrange" />
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="name">Pattern Name:</label>
                <?php 
                echo $this->Form->input('name', array (
                    'type'=>'text',
                    'placeholder' => 'Talent Pattern Name'
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="users">Select Users:</label><br />
                <?php
                echo $this->Form->select('users', 
                    array($users),
                    array(
                        'data-placeholder' => 'Select User(s)', 
                        'multiple' => true, 
                        'deselect' => true,
                        'class'=>'multiselect form-control col-md-12'
                    )
                );
                ?>
            </div>
        </div>
    </div>  
    <?php 
    echo $this->Html->link( 
        '<i class="fa fa-times"></i> Cancel', 
        array('controller'=>'jobTalentpatterns', 'action'=>'index', 'member'=>true ), 
        array('escape'=>false, 'class'=>'btn btn-default btn-sm')  
    ); 
    ?>
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm')); ?>
    <hr/>
</div>            
<?php echo $this->Form->end();?>

<script language="JavaScript">
    jQuery(document).ready( function($) {
        $('.multiselect').multiselect({
            enableFiltering: true,
            buttonWidth: '600px',
            nonSelectedText: 'Select Users',
            includeSelectAllOption: true
            
        });
    });
</script>
 