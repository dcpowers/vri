<?php
    echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobTalentpatterns', 'action'=>'delete', $id, 'member'=>true), 
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
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title">Confirm Deletion</div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <?=$content?>
            <h4>Current Job Openings:</h4>
            <?php
            if(isset($jobs) && !empty($jobs)){
                ?>
                <ul class="list-unstyled">
                    <?php
                    foreach($jobs as $job){
                        $opened = date( APP_DATE_FORMAT, strtotime($job['JobPosting']['created']));
                        ?>
                        <li>
                            <b>Opened on:</b>&nbsp; <?=$opened?>&nbsp;&nbsp;
                            <b>Status:</b>&nbsp; <?=$settings['job_status'][$job['JobPosting']['status']]?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }else{
                ?>
                <p>There are no job openings that use this talent pattern</p>
                <?php
            }
            ?>    
        </div>
    </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> No! Close This Window', array('class'=>'btn btn-danger', 'data-dismiss'=>'modal')); ?>
            <?php echo $this->Form->button('<i class="fa fa-trash"></i> Yes! Delete', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-success')); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end();?> 