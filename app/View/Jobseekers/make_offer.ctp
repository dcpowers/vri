<?php
$range = array('Yearly'=>'Yearly', 'Monthly' => 'Monthly', 'Weekly'=>'Weekly', 'Hourly'=>'Hourly', 'Project'=>'Project'); 
$classification = array('Contract'=>'Contract', 'Temporary'=>'Temporary', 'Part-Time'=>'Part-Time', 'Full-Time'=>'Full-Time');

echo $this->Form->create('Job', array(
    'url' => array('controller'=>'jobseekers', 'action'=>'make_offer', 'member'=>true), 
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        //'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('ApplyJob.id', array('value'=>$id));

?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$title?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <p>We are excited to offer you a position with <?=$info[0]['JobPosting']['Group']['name']?>. The details of the offer are listed below...</p>
            <dl>
              <dt>Company Name:</dt>
              <dd><?=$info[0]['JobPosting']['Group']['name']?></dd>
            </dl>
            
            <dl>
                <dt>Pay:</dt>
                <dd>
                    <?php
                    echo $this->Form->input('ApplyJob.salary', array (
                        'type'=>'text',
                        'placeholder' => 'Enter A Salary/Wage.',
                        'label'=>false,
                        'div'=>false,
                    ));
                    ?>
                </dd>
            </dl>
            
            <dl>
                <dt>Frequency of Pay:</dt>
                <dd>
                    <?php 
                    echo $this->Chosen->select('ApplyJob.range', 
                        $range,
                        array('multiple' => false)
                    );
                    ?>
                </dd>
            </dl>
            <dl>
                <dt>Job Classification:</dt>
                <dd>
                    <?php 
                    echo $this->Chosen->select('ApplyJob.classification', 
                        $classification,
                        array('multiple' => false )
                    );
                    ?>
                </dd>
            </dl>
            <dl>
                <dt>Job Title:</dt>
                <dd><?=$info[0]['ApplyJob']['job_name']?></dd>
            </dl>
            
            <?php
            
            echo $this->Form->hidden('ApplyJob.company', array('value'=>$info[0]['JobPosting']['Group']['name']));
            echo $this->Form->hidden('ApplyJob.position', array('value'=>$info[0]['ApplyJob']['job_name']));
            echo $this->Form->hidden('ApplyJob.url', array('value'=>'<a href=http://www.iworkzone.com/jobviews/employment/'.$info[0]['ApplyJob']['id'].'/'.$pin_number.'>Click Here To Accept or Decline Job Offer</a>'));
            echo $this->Form->hidden('ApplyJob.prev_notes', array('value'=>$info[0]['ApplyJob']['notes']));
            ?>
        </div>
    </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
            <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
        </div>
    </div>
</div>

<script language="JavaScript">
    $(document).ready( function() {
        $(".chosen-select").chosen();
    });
</script>