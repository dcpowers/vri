<?php
    $user = AuthComponent::user();
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' ); 
?>
<div class="row-fluid two-column with-right-sidebar">
    <div class="span12">
        <h1>All HSE Training
            <?php if ( empty( $this->request->data ) ) { ?>
                <?php echo $this->Html->link(
                    '<i class="icon-search"></i>',
                    '#', 
                    array('class'=>'btn btn-mini btn-show-search', 'escape'=>false, 'style'=>'margin-left:5px;top:-2px;position:relative')
                ); ?>
            <?php } ?>
        </h1>
        <?php if ( empty( $this->request->data ) ) { $formClass = 'hide'; } else { $formClass = '';} ?>
        
        
        <?php echo $this->Form->create('Training', array(
            'class'=>'form-inline trn-search-form ' . $formClass,
            'url' => array_merge(array('action' => 'view_all'), $this->params['pass'])
        )); ?>
            <?php echo $this->Form->input('q', array('div'=>false, 'label'=>false, 'placeholder'=>'Search all Training')); ?>
            <?php echo $this->Form->input('Search', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn')); ?>
            <?php echo $this->Html->link( '<i class="icon-remove"></i>', array('action'=>'index'), array('escape'=>false,'style'=>'top: 3px;position: relative;' ) ); ?>
        <?php echo $this->Form->end(); ?>
        
        <div class="row-fluid">
            <div class="training index span9">
                <table class="table table-striped table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 75%">Title</th>
                            <th>Created Date</th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach( $Training as $train ) {  ?>
                        <tr>
                            <td>
                                <?php echo $this->Html->link( 
                                    'TRN-' . str_pad( $train['Training']['id'],4, 0, STR_PAD_LEFT ) .' '.ucwords(strtolower($train['Training']['title'])) , 
                                    array('action'=>'view', $train['Training']['id'])
                                );
                            
                                if ( !empty( $train['Training']['is_retired'] ) ) { ?>
                                    <span class="label label-important">Retired</span>
                                <?php } ?>
                            </td>
                            
                            <td>
                                <?php echo date( APP_DATE_FORMAT, strtotime($train['Training']['created'] ) ); ?>
                            </td>
                            
                            <td>
                                <?php if(in_array('SuperAdmin', $role_names) or in_array('Safety Supervisor', $role_names)) { 
                                    echo $this->Html->link(
                                        '<i class="icon-trash icon-small"></i>',
                                        array('action'=>'delete', $train['Training']['id'] ), 
                                        array('class'=>'btn btn-link ','escape'=>false ),
                                        sprintf('Are You Sure You Want To Delete Training #'.$train['Training']['id'].'?')
                                    );
                                }?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <p class="summary">
                        <?php echo $this->Paginator->counter(array(
                            'format' => __('Page {:page} of {:pages}, showing {:current} of {:count}.')
                        )); ?>
                    </p>
                    
                    <div class="paging">
                        <?php
                        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
                        echo $this->Paginator->numbers(array('separator' => ''));
                        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $this->element( 'sidebar' );?>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(window).ready( function($) {
    $('.btn-show-search').on('click', function() {
        //$('.trn-search-form').show(); 
        $('.trn-search-form').toggleClass('hide'); 
        $('.trn-search-form input[type=text]').focus(); 
        $(this).hide();
        return false;
    });
});
</script>