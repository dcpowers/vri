<div class="inspections index">
	<h2><?php echo __('OBT Dashboard');?></h2>

    <?php if ( !empty($expiring_signoffs) ) { ?>
        <h3 class="compact">
            <?php echo __('Expiring In 30 Days');?>
        </h3>
        <?php echo $this->element(
            '../Obts/_obtsignoff_expiring_table', 
            array('signoffs'=> $expiring_signoffs, 'empty_message'=>'no completed OBTs found' )  
        );
    } ?>
    
    <?php if ( !empty($in_progress_signoffs) ) { ?>
        <h3 class="compact">
            <?php echo __('In Progress');?>
            <small>OBTs, I need to complete.</small>
        </h3>
        <?php echo $this->element(
            '../Obts/_obtsignoff_table',
            array('signoffs'=> $in_progress_signoffs, 'empty_message'=>'no OBT signoffs in progress' )
        );
    } ?>
    
    <?php if ( !empty($completed_signoffs) ) { ?>
        <h3 class="compact">
            <?php echo __('Completed OBTs');?>
            <small>
                <?php echo $this->Html->link(__('View All') , array('controller'=>'obt_signoffs', 'action'=>'byAssociate', AuthComponent::user('id') ), array('class'=>'btn btn-mini'))?>
            </small>
        </h3>
        <?php echo $this->element(
            '../Obts/_obtsignoff_table', 
            array('signoffs'=> $completed_signoffs, 'empty_message'=>'no completed OBTs found' )  
        );
    } ?>
  
</div>
<div class="submit">
    <?php echo $this->Html->link(__('Request OBT Signoff'), array('controller' => 'obt_signoffs', 'action' => 'request' ), array('class'=>'btn btn-primary') ); ?>
    <?php echo $this->Html->link(__('OBT List'), '../../obts/obts-toc/', array('class'=>'btn') ); ?>
</div>