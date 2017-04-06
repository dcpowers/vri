<?php $training =  $this->requestAction( array('plugin'=>'training', 'controller'=>'trainings', 'action'=>'index' ) ); ?>
    <h3 class="compact"><?php echo __('My HSE Training');?> <small>( Expired/Expiring/In Progress)</small></h3>
    <?php if ( !empty($training ) ) { ?>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th style="width: 300px;"><?php echo __('Training');?></th>
                    <th style="width: 120px;"><?php echo __('Expires');?></th>
                    <th style="width: 40px"><?php echo __('');?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $training as $train): ?>
                <tr>
                    <td>
                        <?php echo $this->Html->link( 
                            'TRN-' . str_pad( $train['Training']['id'],4, 0, STR_PAD_LEFT ) .' '.ucwords(strtolower($train['Training']['title'])) , 
                            array('plugin'=>'training', 'controller'=>'trainings', 'action'=>'view', $train['Training']['id'])
                        ); ?>
                        
                        <?php if ( !empty( $train['Training']['is_retired'] ) ) { ?>
                            <span class="label label-important">Retired</span>
                        <?php } ?>
                    </td>
                    
                    <td>
                        <?php if(!empty($train['TrainingRecord'][0]['expires'])) {
                            $expires_date_formatted = date( APP_DATE_FORMAT, strtotime($train['TrainingRecord'][0]['expires'] ) );
                        } else {
                            $expires_date_formatted = "--";
                        }
                            
                        echo $expires_date_formatted;
                        ?>
                    </td>
                    
                    <td>
                        <?php if($train['Training']['is_photos'] == '0' &&  $train['Training']['is_video'] == '0' && empty($train['Training']['description']) ) {
                            echo $this->Html->link( 
                                __('Coming Soon'), 
                                array('plugin'=>'training', 'controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                array('class'=>'btn btn-mini btn-primary','escape'=>false, 'style'=>'width: 85px')
                            ); 
                        } else {
                            if ( empty( $train['TrainingRecord'][0]['id'] ) ) {
                                echo $this->Html->link( 
                                    'Begin', 
                                    array('plugin'=>'training','controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                    array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                );
                            } else {
                                if ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_inprogress'] == 1 ) {
                                    echo $this->Html->link( 
                                        __('In Progress'), 
                                        array('plugin'=>'training','controller'=>'trainings', 'action'=>'view', $train['TrainingRecord'][0]['training_id'] ),
                                        array('class'=>'btn btn-mini btn-info','escape'=>false, 'style'=>'width: 85px')
                                    );
                                        
                                    echo $this->Html->link( 
                                        ' <i class="icon-white icon-remove"></i>', 
                                        array('plugin'=>'training','controller'=>'training_records', 'action'=>'cancel', $train['TrainingRecord'][0]['id'] ),
                                        array('title'=>__('Cancel'),'class'=>'btn btn-mini btn-danger','escape'=>false),
                                        sprintf('Cancel Training?')
                                    );
                                        
                                } elseif ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_expiring'] == 1 ) {
                                    echo $this->Html->link( 
                                        '<i class="icon-white icon-repeat"></i> Renew', 
                                        array('plugin'=>'training','controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                        array('class'=>'btn btn-mini btn-warning','escape'=>false, 'style'=>'width: 85px')
                                    );  
                                } elseif ( !empty( $train['TrainingRecord'][0]['id'] ) AND $train['TrainingRecord'][0]['is_expired'] == 1 ) {
                                    echo $this->Html->link( 
                                        '<i class="icon-white icon-repeat"></i> Renew', 
                                        array('plugin'=>'training','controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                        array('class'=>'btn btn-mini btn-danger','escape'=>false, 'style'=>'width: 85px')
                                    );
                                } else {
                                    echo $this->Html->link( 
                                        '<i class="icon-white icon-repeat"></i> Renew', 
                                        array('plugin'=>'training','controller'=>'trainings', 'action'=>'view', $train['Training']['id'] ),
                                        array('class'=>'btn btn-mini btn-success','escape'=>false, 'style'=>'width: 85px')
                                    ); 
                                } 
                            }
                        }?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { echo "<p>All Training Is Upto Date</p>"; } ?>
