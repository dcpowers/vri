<?php 
echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobs', 'action'=>'add', 'member'=>true), 
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('group_id', array('value'=>$group_id));
?>
<div class="container">
    <h2 class="title"><i class="fa fa-list-ul"></i> <span class="text">New Job Title</span></h2>
    <hr class="solidOrange" />
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="name">Title:</label>
                <?php 
                echo $this->Form->input('name', array (
                    'type'=>'text',
                    'placeholder' => 'Job Name'
                ));
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="soc">SOC Code:</label>
                <?php 
                echo $this->Form->input('soc_code', array (
                    'type'=>'text',
                    'placeholder' => 'SOC Code'
                ));?>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label" for="description">Description:</label>
        <?php 
        echo $this->Form->input('description', array (
            'type'=>'textarea',
            'id'=>'description',
            
        ));
        ?>
    </div>
    
    <?php 
    echo $this->Html->link( 
        '<i class="fa fa-times"></i> Cancel', 
        array('controller'=>'jobs', 'action'=>'index', 'member'=>true ), 
        array('escape'=>false, 'class'=>'btn btn-default btn-sm')  
    ); 
    ?>
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm')); ?>
    <hr/>
</div> 
<?php echo $this->Form->end();?>    