<?php 
    $user = AuthComponent::user();
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
?>

<div class="span3">
    <div class="box sidebar obtsidebar screen-only">
        <div class="box-header">
            <h2><i class="fa-icon-book"></i><span class="break"></span>HSE Training</h2>
        </div>
        
        <div class="box-content">
            <?php $links = array(
                array(
                    'title'=>__('My Training'),
                    'link'=>array('controller'=>'training_records', 'action'=>'my_training')
                ),
                array(
                    'title'=>__('HSE RequiredTraining'),
                    'link'=>array('controller'=>'trainings', 'action'=>'index')
                ),
                /*array(
                    'title'=>__('HSE Non-Required/ Recurring Training'),
                    'link'=>array('controller'=>'trainings', 'action'=>'index', '#'=>'non-required')
                ),*/
                array(
                    'title'=>__('All HSE Training'),
                    'link'=>array('controller'=>'trainings', 'action'=>'view_all')
                )
            );?>
            
            <ul class="nav nav-pills nav-stacked">
                <?php foreach( $links as $link ) { ?>
                    <li><?php echo $this->Html->link( $link['title'], $link['link'] ); ?></li>
                <?php } ?>
            </ul>     
            
            <?php if(in_array('SuperAdmin', $role_names) or in_array('Safety Supervisor', $role_names)) {   
                $links = array(
                    array(
                        'title'=>__('Add Training'),
                        'link'=>array('controller'=>'trainings', 'action'=>'add')
                    ),
                    array(
                        'title'=>__('Monthly Training Report'),
                        'link'=>array('controller'=>'training_reports', 'action'=>'stats')
                    )
                );?>
                
                <h5>HSE Training Admin</h5>
                <ul class="nav nav-pills nav-stacked">
                    <?php foreach( $links as $link ) { ?>
                        <li><?php echo $this->Html->link( $link['title'], $link['link'] ); ?></li>
                    <?php } ?>
                </ul>
            <?php }  ?>
            
            <h5>HSE Training Reports</h5>
            <?php $links = array(
                array(
                    'title'=>__('Department'),
                    'link'=>array('controller'=>'training_reports', 'action'=>'department')
                ),
                /*array(
                    'title'=>__('Supervisor'),
                    'link'=>array('controller'=>'training_reports', 'action'=>'supervisor')
                ),*/
                array(
                    'title'=>__('Associate'),
                    'link'=>array('controller'=>'training_reports', 'action'=>'associate')
                ),
                /*array(
                    'title'=>__('Expired Training'),
                    'link'=>array('controller'=>'training_reports', 'action'=>'expired')
                )*/
            ); ?>
            
            <ul class="nav nav-pills nav-stacked">
                <?php foreach( $links as $link ) { ?>
                    <li><?php echo $this->Html->link( $link['title'], $link['link'] ); ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>