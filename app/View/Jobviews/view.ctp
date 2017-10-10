<?php
    #pr($postings);
    #unset($postings[0]['JobQuestion']);
    #exit;
?>
<div class="container">
    <?php
    $name = '../groupFiles/'.$postings['Job']['Group']['pin_number'].'/'.$postings['Job']['Group']['logo'];
    $image = (!empty($postings['Job']['Group']['logo'])) ? $name : 'noImage.jpg' ;
                    
    echo $this->Html->image($name, array('class'=>'img-responsive img-thumbnail'));
    ?>
    <h1><?=$postings['Group']['welcome_title']?></h1>
    <?=$postings['Group']['welcome_notes']?>
    <hr class="solidOrange" />
    <div class="row spacer">                
        <div class="col-md-3">
            <h2><?=$postings['Job']['name']?></h2>
            
            <b>Location:</b> <?=$postings['Group']['city']?>, <?=$postings['Group']['State']['state_name']?><br />
            <b>Posted:</b> <?php echo date( APP_DATE_FORMAT,strtotime($postings['JobPosting']['created'])); ?><br />
            <b>Salary:</b> <?=$postings['JobPosting']['salary']?>/ <?=$postings['SalaryType']['name']?><br />
            <b>Talent Match:</b> <?=$postings['JobPosting']['match']?>%
            
            <div class="spacer">
                <?php
                //pr($postings[0]);
                if(!is_null($postings['JobPosting']['job_question_id'])){
                    echo $this->Html->link(
                        'Click Here To Apply',
                        array('controller'=>'jobviews', 'action'=>'apply', $postings['JobPosting']['id'], $postings['JobPosting']['match']),
                        array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-block')
                    );
                }else{
                    echo $this->Html->link(
                        'Click Here To Apply',
                        array('controller'=>'jobviews', 'action'=>'applyNow', $postings['JobPosting']['id'], $postings['JobPosting']['match']),
                        array('escape'=>false, 'class'=>'btn btn-primary ')
                    );
                }
                ?>
            </div>
        </div>
        <div class="col-md-9">
            
            <h3>Job Description:</h3>
            <hr />
            <?=$postings['JobPosting']['description']?>
            <?php
            if(!empty($postings['JobPosting']['requirements'])){
                ?>
                <h3 class="spacer">Job Requirements:</h3>
                <hr />
                <?=$postings['JobPosting']['requirements']?>
                <?php
            }
            ?>
            
            <div class="spacer">
            <?php
            //pr($postings[0]);
            if(!is_null($postings['JobPosting']['job_question_id'])){
                echo $this->Html->link(
                    'Click Here To Apply',
                    array('controller'=>'jobviews', 'action'=>'apply', $postings['JobPosting']['id'], $postings['JobPosting']['match']),
                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal', 'class'=>'btn btn-primary btn-block')
                );
            }else{
                echo $this->Html->link(
                    'Click Here To Apply',
                    array('controller'=>'jobviews', 'action'=>'applyNow', $postings['JobPosting']['id'], $postings['JobPosting']['match']),
                    array('escape'=>false, 'class'=>'btn btn-primary btn-block')
                );
            }
            ?>
            </div>
        </div>
    </div>
</div>
    
    
    
            
                                <div class="col-xs-6">
                                    
                                </div>
                            </div>
                            <hr />
                            
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="widget widget-no-header">
                        <div class="widget-content">
                            <h2>About <?=$postings['Job']['Group']['name']?> </h2>
                            <h3><?=$postings['Job']['Group']['welcome_title']?></h3>
                            <?=$postings['Job']['Group']['welcome_notes']?>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="widget widget-no-header">
                <div class="widget-content">
                    <h2><?=$postings['Job']['name']?></h2>
                    <hr />
                    <?php echo nl2br($postings['Job']['description']); ?>
                </div>
            </div>
        </div>        
    </div>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
    });
</script> 