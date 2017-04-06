<?php 
$trn_instructor = 'No';

$role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
    
if(in_array('SuperAdmin', $role_names) or in_array('Training Instructor', $role_names)or in_array('Safety Supervisor', $role_names))
{ 
    $trn_instructor = 'Yes';
}

?>
<div class="row two-column with-right-sidebar">
    <div class="obts index span9">
        <h2>
            <?php echo __('HSE Required/Recurring Training');?>
                <?php if ( empty( $this->request->data ) ) { ?>
                    <?php echo $this->Html->link(
                        '<i class="icon-search"></i>',
                        '#', 
                        array('class'=>'btn btn-mini btn-show-search', 'escape'=>false, 'style'=>'margin-left:5px;top:-2px;position:relative')
                    ); ?>
                <?php } ?>
    
        </h2>
        <?php if ( empty( $this->request->data ) ) { $formClass = 'hidden'; } else { $formClass = '';} ?>
        <?php echo $this->Form->create('Training', array(
            'class'=>'form-inline trn-search-form ' . $formClass,
            'url' => array_merge(array('action' => 'index'), $this->params['pass'])
        )); ?>
            <?php echo $this->Form->input('q', array('div'=>false, 'label'=>false, 'placeholder'=>'Search all Training')); ?>
            <?php echo $this->Form->input('Search', array('type'=>'button', 'div'=>false, 'label'=>false, 'class'=>'btn')); ?>
            <?php echo $this->Html->link( '<i class="icon-remove"></i>', array('action'=>'index'), array('escape'=>false,'style'=>'top: 3px;position: relative;' ) ); ?>
        <?php echo $this->Form->end(); ?>
        
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th style="width: 60%">Training</th>
                    <th style="width: 20%">Expires Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $Training as $train ) { ?>
                    <tr>
                        <td>
                            <?php 
                            echo $this->Html->link( 
                                    'TRN-' . str_pad( $train['Training']['id'],4, 0, STR_PAD_LEFT ) .' '.ucwords(strtolower($train['Training']['title'])) , 
                                    array('action'=>'view', $train['Training']['id'])
                            );
                            ?>
                            
                            <?php if ( !empty( $train['Training']['is_retired'] ) ) { ?>
                                <span class="label label-important">Retired</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            if(!empty($train['TrainingRecord'][0]['expires'])) {
                                $expires_date_formatted = date( APP_DATE_FORMAT, strtotime($train['TrainingRecord'][0]['expires'] ) );
                            } else {
                                $expires_date_formatted = "--";
                            }
                            
                            echo $expires_date_formatted;
                            ?>
                        </td>
                        <td>
                           <?php
                           
                           if($train['Training']['is_photos'] == '0' &&  $train['Training']['is_video'] == '0' && empty($train['Training']['description']) )
                           {
                               echo $this->Html->link( 
                                       __('Coming Soon'), 
                                       array('controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                       array('class'=>'btn btn-mini btn-primary','escape'=>false, 'style'=>'width: 85px')
                               ); 
                           } else {
                               if ( empty( $train['TrainingRecord'][0]['id'] ) ) {
                                    echo $this->Html->link( 
                                        'Begin', 
                                        array('controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                        array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                    );
                                } else {
                                    if ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_inprogress'] == 1 ) {
                                        echo $this->Html->link( 
                                            __('In Progress'), 
                                            array('controller'=>'trainings', 'action'=>'view', $train['TrainingRecord'][0]['training_id'] ),
                                            array('class'=>'btn btn-mini btn-info','escape'=>false, 'style'=>'width: 85px')
                                        );
                                        
                                        echo $this->Html->link( 
                                            ' <i class="icon-white icon-remove"></i>', 
                                            array('controller'=>'training_records', 'action'=>'cancel', $train['TrainingRecord'][0]['id'] ),
                                            array('title'=>__('Cancel'),'class'=>'btn btn-mini btn-danger','escape'=>false),
                                            sprintf('Cancel Training?')
                                        );
                                        
                                    } elseif ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_expiring'] == 1 ) {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-warning','escape'=>false, 'style'=>'width: 85px')
                                        );  
                                    } elseif ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_expired'] == 1 ) {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                        );
                                    } else {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-success','escape'=>false, 'style'=>'width: 85px')
                                        ); 
                                    } 
                                }
                           }
                           ?>
                        </td>
                    </tr>
                <?php } ?>
                
            </tbody>
        </table>
    </div>
    <?php echo $this->element( 'sidebar' );?>
    <div class="obts index span9">
        <a name="non-required"></a> 
        <h2> HSE Non-Required/Recurring Training</h2>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th style="width: 60%">Title</th>
                    <th style="width: 20%">Expires Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $Nrtraining as $nrtrain ) { ?>
                    <tr>
                        <td>
                            <?php 
                            echo $this->Html->link( 
                                    'TRN-' . str_pad( $nrtrain['Training']['id'],4, 0, STR_PAD_LEFT ) .' '.ucwords(strtolower($nrtrain['Training']['title'])) , 
                                    array('action'=>'view', $nrtrain['Training']['id'])
                            );
                            ?>
                            
                            <?php if ( !empty( $train['Training']['is_retired'] ) ) { ?>
                                <span class="label label-important">Retired</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            if(!empty($nrtrain['TrainingRecord'][0]['expires'])) {
                                echo date( APP_DATE_FORMAT, strtotime($nrtrain['TrainingRecord'][0]['expires'] ) );
                            } else {
                                echo "--";
                            }
                            ?>
                        </td>
                        <td>
                           <?php
                           
                           if($nrtrain['Training']['is_photos'] == '0' &&  $nrtrain['Training']['is_video'] == '0' && empty($nrtrain['Training']['description']) )
                           {
                               echo $this->Html->link(
                                       __('Coming Soon'), 
                                       array('controller'=>'trainings', 'action'=>'view', $nrtrain['Training']['id'] ),
                                       array('class'=>'btn btn-mini btn-primary','escape'=>false, 'style'=>'width: 85px')
                                    ); 
                           }
                           else
                           {
                               if ( empty( $nrtrain['TrainingRecord'][0]['id'] ) ) {
                                    echo $this->Html->link( 
                                        'Begin', 
                                        array('controller'=>'trainings', 'action'=>'view', $nrtrain['Training']['id'] ),
                                        array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                    );
                                } else {
                                    if ( !empty( $nrtrain['TrainingRecord'][0]['id'] ) AND $nrtrain['TrainingRecord'][0]['is_inprogress'] == 1 ) {
                                        echo $this->Html->link( 
                                            __('In Progress'), 
                                            array('controller'=>'trainings', 'action'=>'view', $nrtrain['TrainingRecord'][0]['training_id'] ),
                                            array('class'=>'btn btn-mini btn-info','escape'=>false, 'style'=>'width: 85px')
                                        );
                                        
                                        echo $this->Html->link( 
                                            ' <i class="icon-white icon-remove"></i>', 
                                            array('controller'=>'training_records', 'action'=>'cancel', $nrtrain['TrainingRecord'][0]['id'] ),
                                            array('title'=>__('Cancel'),'class'=>'btn btn-mini btn-danger','escape'=>false),
                                            sprintf('Cancel Training?')
                                        );
                                        
                                    } elseif ( !empty( $nrtrain['TrainingRecord'][0]['id'] ) AND $nrtrain['TrainingRecord'][0]['is_expiring'] == 1 ) {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $nrtrain['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-warning','escape'=>false, 'style'=>'width: 85px')
                                        );  
                                    } elseif ( !empty( $nrtrain['TrainingRecord'][0]['id'] ) AND $nrtrain['TrainingRecord'][0]['is_expired'] == 1 ) {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $nrtrain['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                        );
                                    } else {
                                        echo $this->Html->link( 
                                            '<i class="icon-white icon-repeat"></i> Renew', 
                                            array('controller'=>'trainings', 'action'=>'view', $nrtrain['Training']['id'] ),
                                            array('class'=>'btn btn-mini btn-success','escape'=>false, 'style'=>'width: 85px')
                                        ); 
                                    } 
                                }
                           }
                           ?>
                        </td>
                    </tr>
                <?php } ?>
                
            </tbody>
        </table>
    </div>
    
</div>
<script type="text/javascript">
jQuery(window).ready( function($) {
    $('.btn-show-search').on('click', function() {
        $('.trn-search-form').show(); 
        $('.trn-search-form input[type=text]').focus(); 
        $(this).hide();
        return false;
    });
});
</script>