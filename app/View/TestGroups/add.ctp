<?php
    for($i=0;$i<=10;$i++){
        $creditsArray[$i] = $i;
    }

    #$settings['scheduleType']['Single'] = 'Assessment';
    #$settings['scheduleType']['Group'] = 'Survey';
    $settings['scheduleType']['MultiplePeople'] = 'Evaluation';

	echo $this->Form->create('TestGroup', array(
        'url' => array('controller'=>'TestGroups', 'action'=>'add'),
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
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h2>Create New Test</h2>
</div>

<div class="modal-body">

    <div class="form-group">
        <label class="control-label" for="name">Title/Name:</label>
        <?php
        echo $this->Form->input('name', array (
            'type'=>'text',
            'placeholder' => 'Name',
            'id'=>'name',
            'class'=>'form-control'
            ));
        ?>
    </div>

    <div class="form-group">
        <label class="control-label" for="name">Status:</label>
        <?php
        echo $this->Form->input('is_active', array (
            'type'=>'select',
            'options'=>$settings['options'],
            'placeholder' => 'Status',
            'id'=>'status',
        ));
        ?>
    </div>

    <div class="form-group">
        <label class="control-label" for="name">Test Type:</label>
        <?php
        echo $this->Form->input('schedule_type', array (
            'type'=>'select',
            'options'=>$settings['scheduleType'],
            'placeholder' => 'Schedule Type',
            'id'=>'schedule_type',
        ));
        ?>
    </div>
</div>

<div class="modal-footer">
	<?php
	echo $this->Form->button('Save',
		array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')
	);
	?>

	<?php
	echo $this->Html->link( __('Cancel'),
		array('controller'=>'TestGroups', 'action'=>'index'), array('class'=>'btn')
	);
	?>
</div>

<?php echo $this->Form->end(); ?>

<script language="JavaScript">
    jQuery(document).ready( function($) {

    });
</script>
