<div class="row two-column with-right-sidebar">
    <div class="obts form edit span9">
        <h2><?php echo __('Edit Training'); ?> <small>ID:<?php echo $this->request->data['Training']['id_formatted']; ?></small></h2>

        <div class="tabbable">
            <div class="tab-content">
                <div class="tab-pane active" id="contents">
                    <?php echo $this->Form->create('Training', array()); ?>
                    <?php echo $this->Form->input('id'); ?>

                    <fieldset>
                        <?php echo $this->Form->input('title' ); ?>
                        <?php echo $this->Form->input('description', array('class'=>'autosize','type' => 'textarea', 'escape' => false)); ?>
                        <?php echo $this->Form->input('is_required', array('class'=>'autosize')); ?>
                        <?php echo $this->Form->input('is_recurring', array('class'=>'autosize')); ?>
                        <?php echo $this->Form->input('author_id', array('selected'=>AuthComponent::user('id') ) ); ?>
                        <?php $next_revision_number = $this->request->data['Training']['revision'] + 1; ?>
                        <?php echo $this->Form->input(
                                'revision', array(
                                    'label'=>__('Revision Number'),
                                    /* Disabled while in Development 'value'=>$next_revision_number, */
                                    'after'=>'<span class="hint">Previous Revision: ' .  $this->request->data['Training']['revision'].'</span>'
                                ) 
                            ); ?>
                    </fieldset>
                    <h2>Quiz 
                        <?php echo $this->Html->link( 
                                '<i class="icon-plus"></i> ' . __('Add Question'), 
                                array( 'controller'=>'training_quiz', 'action'=>'add' ),
                                array('class'=>'addItemButton btn btn-mini', 'escape'=>false, 'rel'=>$this->request->data['Training']['id'] ) 
                            ); ?>
                    </h2>
                    <?php foreach( $this->request->data['TrainingQuiz'] as $record_info ) { ?>
                        <fieldset class="questionFieldset question<?php echo $record_info['quiz_order'];?>">
                            <legend>
                                <div class="areaEditButtons">

                                    <?php echo $this->Html->link( '<i class="icon-edit"></i>', array('controller'=>'trainingQuiz', 'action'=>'edit', $record_info['id'] ) , array('class'=>'editAreaButton btn btn-mini', 'escape'=>false ) ); ?>
                                    <?php echo $this->Html->link( 
                                            '<i class="icon-minus-sign icon-white"></i>', 
                                            array('controller'=>'training_quiz', 'action'=>'delete', $record_info['id'] ), 
                                            array('class'=>'btn btn-mini btn-area-delete btn-danger', 'escape'=>false )
                                        ); ?>
                                    <?php echo $this->Html->link( '<i class="icon-chevron-up"></i>', '#', array('class'=>'btn btn-mini btn-area-moveup', 'escape'=>false ) ); ?>
                                    <?php echo $this->Html->link( '<i class="icon-chevron-down"></i>', '#', array('class'=>'btn btn-mini btn-area-movedown', 'escape'=>false ) ); ?>
                                </div>

                                <?php echo $record_info['question']; ?>
                            </legend>
                        </fieldset>
                        <?php } ?>
                    <div class="submit">
                        <?php echo $this->Form->button(__('Submit'), array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
                        <?php echo $this->Html->link( __('Cancel'), array('controller'=>'trainings', 'action'=>'index' ), array('class'=>'btn')  ); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>    

                </div>
            </div>
        </div>
    </div>

    <?php echo $this->element( 'sidebar' );?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('div.modal').modal({show:false});

        //Show the Add AreaModal
        $('a.addAreaButton').click( function() {
            $('#AjaxModal').load( $(this).attr('href') );
            $('#AjaxModal').modal('show');
            return false;
        });

        //Show the Edit AreaModal
        $('.btn-area-edit').live('click', function( ) {
            $('#AjaxModal').load( $(this).attr('href') );
            $('#AjaxModal').modal('show');
            return false;     
        });

        //Show the Add ItemModal
        $('a.addItemButton').live( 'click', function() {

            areaEl = $(this).closest('.questionFieldset');  
            lastAreaItemEl = areaEl.find('.question:last');  

            var add_question_url = '<?php echo Router::url( array('plugin'=>'training', 'controller'=>'trainings', 'action'=>'add_question') );?>';
            $.ajax({
                url: add_question_url,
                type: "post",
                dataType: "json",
                data: { InspectionItem: { inspection_area_id: $(this).attr('rel') }},
                success: function( objResponse ) {
                    $.get( '#/null_invalid!!!!/' +  objResponse.data.id , function(data){ 
                        $(data).insertAfter( lastAreaItemEl );
                        areaEl.find('.question.empty-item').remove();
                    });
                },
                error: function( objResponse ) {
                    alert('Error Creating Item');
                }

            });

            return false;     

        });


    });
</script>
