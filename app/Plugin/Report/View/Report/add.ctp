<div class="row-fluid two-column with-right-sidebar">
    <div class="span12">
        <h1>Add New Training</h1>
        <div class="row-fluid">
            <div class="training form add span9">
                <?php echo $this->Form->create('Training'); ?>  
                <?php echo $this->Form->input('id'); ?>
                <fieldset>
                    <?php echo $this->Form->input('title' ); ?>
                    <?php echo $this->Form->input('description', array('class'=>'autosize')); ?>
                    <?php echo $this->Form->input('is_required', array('class'=>'autosize')); ?>
                    <?php echo $this->Form->input('is_recurring', array('class'=>'autosize')); ?>
                    <?php echo $this->Form->input('author_id', array('selected'=>AuthComponent::user('id') ) ); ?>
                    <?php echo $this->Form->input('revision', array('label'=>__('Revision Number'), 'value'=>1 ) ); ?>
                    <?php echo $this->Form->file('TrainingFile.file_name', array('label'=>__('Training File')) ); ?> 
                </fieldset>
                <div class="submit">
                    <?php echo $this->Form->button('Submit', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                    <?php echo $this->Html->link( __('Cancel'), array('controller'=>'trainings', 'action'=>'index' ), array('class'=>'btn')  ); ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <?php echo $this->element( 'sidebar' );?>
        </div>
    </div>
</div>