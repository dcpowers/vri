<?php
    #pr($my_notes);
    #pr($item);
    #exit;
?>
<style type="text/css">
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    .label-as-badge {
        border-radius: 3em;
    }
    
    .border-yellow{
        border: 1px #FFFF00 solid;
    }
    
    #myModal.modal-dialog{
        width: 900px !important;
    }
    .totals{
        margin-top: 120px;
        font-size: 3em;
    }
    .tab-paneborder {

    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    border-radius: 0px 0px 5px 5px;
    padding: 10px;
}

.nav-tabsborder {
    margin-bottom: 0;
}
</style>

<div style="margin-top: -20px">
    <div class="headerDiv">
        
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="effect3 thumbnail headerContent">
                    <?php
                    $name = '../'.$item['User']['DetailUser']['uploadDir'].'/'.$item['User']['DetailUser']['img'];
                    $image = (!empty($item['User']['DetailUser']['img'])) ? $name : 'noImage.jpg' ;
                                        
                    echo $this->Html->image($image, array('class'=>'img-thumbnail img-responsive'));
                    
                    ?>
                </div>
                <?php
                if($item['JobPosting']['posted_by'] == AuthComponent::user('id')){
                    ?>
                    <div class="btn-group dropdown" style="padding: 15px 0px">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Status<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            foreach($settings['applicant_status'] as $key=>$settingsitem){
                                //pr($item);
                                $class = ($key == $item['ApplyJob']['status']) ? 'active' : NULL ;
                                ?>
                                <li class="<?=$class?> <?=$settingsitem['class']?>">
                                    <?php
                                    echo $this->Html->link(
                                        $settingsitem['name'],
                                        array('controller'=>$settingsitem['controller'], 'action'=>$settingsitem['action'], 'member'=>true, $item['ApplyJob']['id'], $key, $item['User']['pin_number']),
                                        array(
                                            'escape'=>false, 
                                            'data-dismiss'=>'modal',
                                            'aria-hidden'=>'true',
                                            'data-toggle'=>'modal', 
                                            'data-target'=>'#myModal',
                                        )
                                    );
                                    ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                
                ?>    
                
                <address class="spacer">
                    <?=$item['User']['DetailUser']['address']?><br />
                    <?=$item['User']['DetailUser']['city']?>
                    <?=$item['User']['DetailUser']['State']['state_name']?>,
                    <?=$item['User']['DetailUser']['zip']?><br />
                    <abbr title="Phone">P:</abbr>&nbsp; <?=$item['User']['DetailUser']['phone']?><br />
                    <abbr title="Mobile">M:</abbr>&nbsp; <?=$item['User']['DetailUser']['mobile']?><br />
                    <a href="mailto:<?=$item['User']['username']?>"><?=$item['User']['username']?></a>
                </address>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12 headerContent">
                        <h2 class="title">
                            <?=$item['User']['first_name']?> <?=$item['User']['last_name']?>
                        </h2>
                    </div>
                    
                    <div class="col-md-12">
                        <div role="tabbable-panel">
                            <div class="tabbable-line tabs-below">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#hiring" aria-controls="job" role="tab" data-toggle="tab">FIT Report</a></li>
                                    <li role="presentation"><a href="#applicant" aria-controls="applicant" role="tab" data-toggle="tab">User Details</a></li>
                                    <?php 
                                    if($item['JobPosting']['posted_by'] == AuthComponent::user('id') ){
                                        ?>
                                        <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Collaborater Ratings</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <?php echo $this->Session->flash('applicant') ?>
                                    <!-- Begin: Job Tab -->
                                    <div role="tabpanel" class="tab-pane active fade in"  id="hiring">
                                        <h3>
                                            Overall Match: <?=$tp['total']?>% 
                                            [ 
                                                <?php
                                                //pr($applicants);
                                                //pr($jobPosting);
                                                echo $this->Html->link(
                                                    'View Full Hiring Report',
                                                    array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>'iWRaCIP_hiring', $item['User']['AssignedTest'][0]['id'], $item['JobPosting']['id'] ),
                                                    array('escape'=>false,)
                                                );
                                                ?> 
                                                ]
                                            
                                        </h3>
                                                
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th style="width: 20%">Categories</th>
                                                    <th style="width: 20%"><?=$item['User']['first_name']?> <?=$item['User']['last_name']?></th>
                                                    <th style="width: 25%">
                                                        Talent Pattern 
                                                        <small>( <?=$item['JobPosting']['JobTalentpattern']['name']?> )</small>
                                                    </th>
                                                    <th style="width: 15%">Variation</th>
                                                    <th style="width: 20%">Pattern Match</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr>
                                                    <td>Realistic</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['realistic']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['realistic']?></td>
                                                    <td><?=$tp['rel_diff']?></td>
                                                    <td rowspan="6"><span class="totals"><?=$tp['total_cat1']?>%</span></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Investigative</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['investigative']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['investigative']?></td>
                                                    <td><?=$tp['inv_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Conventional</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['conventional']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['conventional']?></td>
                                                    <td><?=$tp['con_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Social</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['social']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['social']?></td>
                                                    <td><?=$tp['soc_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Enterprising</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['enterprising']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['enterprising']?></td>
                                                    <td><?=$tp['ent_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Artistic</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['artistic']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['artistic']?></td>
                                                    <td><?=$tp['art_diff']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                                
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th style="width: 20%">Categories</th>
                                                    <th style="width: 20%"><?=$item['User']['first_name']?> <?=$item['User']['last_name']?></th>
                                                    <th style="width: 25%">
                                                        Talent Pattern 
                                                        <small>( <?=$item['JobPosting']['JobTalentpattern']['name']?> )</small>
                                                    </th>
                                                    <th style="width: 15%">Variation</th>
                                                    <th style="width: 20%">Pattern Match</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr>
                                                    <td>Competitor</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['competitor']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['competitor']?></td>
                                                    <td><?=$tp['com_diff']?></td>
                                                    <td rowspan="4"><span class="totals"><?=$tp['total_cat2']?>%</span></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Communicator</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['communicator']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['communicator']?></td>
                                                    <td><?=$tp['comm_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Cooperator</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['cooperator']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['cooperator']?></td>
                                                    <td><?=$tp['coo_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Coordinator</td>
                                                    <td><?=$item['User']['TalentpatternUser'][0]['coordinator']?></td>
                                                    <td><?=$item['JobPosting']['JobTalentpattern']['coordinator']?></td>
                                                    <td><?=$tp['coor_diff']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <?php
                                        if(!empty($item['JobPosting']['JobQuestion'])){
                                            ?>
                                            <h3 class="spacer">Screening Questions</h3>
                                            <div>
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                        <tr class="tr-heading">
                                                            <th style="width: 55%">Question</th>
                                                            <th style="width: 20%">Desired Answer</th>
                                                            <th style="width: 15%">Answer</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <?php
                                                        foreach($item['JobPosting']['JobQuestion']['JobQuestionDetail'] as $q){
                                                            $answer = (!empty($q['JobQuestionAnswer'][0]['answer'])) ? $settings['yesNoInt'][$q['JobQuestionAnswer'][0]['answer']] : 'No Answer' ;
                                                            ?>
                                                            <tr>
                                                                <td><?=$q['question']?></td>
                                                                <td><?=$settings['questionOpt'][$q['option']]?></td>
                                                                <td><?=$answer?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <!-- End: Hiring -->
                                    
                                    <!-- Begin: Applicant Info -->
                                    <div role="tabpanel" class="tab-pane in" id="applicant">
                                        <div role="tabpanel" class="clearfix">
                                            <ul class="nav nav-tabs nav-tabsborder" role="tablist">
                                                <li role="presentation" class="active"><a href="#applicant#history" aria-controls="history" role="tab" data-toggle="tab">Work History</a></li>
                                                <li role="presentation"><a href="#applicant#education" aria-controls="education" role="tab" data-toggle="tab">Education</a></li>
                                                <li role="presentation"><a href="#applicant#reference" aria-controls="reference" role="tab" data-toggle="tab">References</a></li>
                                                <li role="presentation"><a href="#applicant#resume" aria-controls="resume" role="tab" data-toggle="tab">Resume</a></li>
                                            </ul>
                                            
                                            <div class="tab-content" style="border-width: 0px;">
                                                <div role="tabpanel" class="tab-pane tab-paneborder fade in active" id="history">
                                                    <h3>Work History</h3>
                                                    <?php
                                                    foreach($item['User']['UserWorkHistory'] as $file){
                                                        #pr($file);
                                                        ?>
                                                        <div class="list-group">
                                                            <div class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="employerName">Company</label>
                                                                            <p class="form-control-static"><?=$file['company']?></p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="employerAddress">Address</label>
                                                                            <p class="form-control-static"><?=$file['address']?></p>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="supervisor">Supervisor Name</label>
                                                                            <p class="form-control-static"><?=$file['supervisor']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">    
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="email">E-Mail</label>
                                                                            <p class="form-control-static"><?=$file['email']?></p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="payStart">Starting Pay</label>
                                                                            <p class="form-control-static"><?=$file['salaryStart']?></p>
                                                                        </div>    
                                                                    </div>
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="payEnd">Ending Pay</label>
                                                                            <p class="form-control-static"><?=$file['salaryEnd']?></p>
                                                                        </div>    
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="phone">Phone Number:</label>
                                                                            <p class="form-control-static"><?=$file['phone']?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="dateFrom">Start Date:</label>
                                                                            <p class="form-control-static"><?=$file['startDate']?></p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="dateFrom">End Date:</label>
                                                                            <p class="form-control-static"><?=$file['endDate']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-xs-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="position">Job Title</label>
                                                                            <p class="form-control-static"><?=$file['jobTitle']?></p>
                                                                        </div>    
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-8">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="leaveReason">Reason For Leaving</label>
                                                                            <p class="form-control-static"><?=$file['leaveReason']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div role="tabpanel" class="tab-pane tab-paneborder fade" id="education">
                                                    <h3>Education</h3>
                                                    <?php
                                                    foreach($item['User']['UserEducation'] as $file){
                                                        #pr($file);
                                                        ?>
                                                        <div class="list-group">
                                                            <div class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="school">School</label>
                                                                            <p class="form-control-static"><?=$file['school']?></p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="grad">Graduate</label>
                                                                            <p class="form-control-static"><?=$file['grad']?></p>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="degree">Degree</label>
                                                                            <p class="form-control-static"><?=$file['degree']?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="major">Major</label>
                                                                            <p class="form-control-static"><?=$file['major']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                </div>
                                                
                                                <div role="tabpanel" class="tab-pane tab-paneborder fade" id="reference">
                                                    <h3>Personal References</h3>
                                                    <?php
                                                    foreach($item['User']['UserReference'] as $file){
                                                        #pr($file);
                                                        ?>
                                                        <div class="list-group">
                                                            <div class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="name">Name:</label>
                                                                            <p class="form-control-static">
                                                                            <?php
                                                                            if(empty($file['ReferenceResponce'])){
                                                                                echo $file['name'];
                                                                            }else{
                                                                                echo $this->Html->link( 
                                                                                    $file['name'], 
                                                                                    array('controller'=>'Jobseekers','action'=>'view_reference','member'=>true, $file['id']), 
                                                                                    array('escape'=>false,'data-toggle'=>'modal', 'data-target'=>'#myModal',) 
                                                                                );
                                                                            }
                                                                            ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="relation">Relation:</label>
                                                                            <p class="form-control-static"><?=$file['relation']?></p>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="phone">Phone:</label>
                                                                            <p class="form-control-static"><?=$file['phone']?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-3">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="email">E-Mail</label>
                                                                            <p class="form-control-static"><?=$file['email']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="clearfix"></div>
                                                </div>
                                                
                                                <div role="tabpanel" class="tab-pane tab-paneborder fade" id="resume">
                                                    <h3>Resume</h3>
                                                    <ul>
                                                        <?php
                                                        foreach($item['User']['UserFile'] as $file){
                                                            ?>
                                                            <li>
                                                                <?php
                                                                echo $this->Html->link(
                                                                    $file['name'],
                                                                    '/'. $file['file'],
                                                                    array('escape'=>false, 'target' => '_blank')
                                                                );
                                                                ?>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End: Applicant Info -->
                                    
                                    <!-- Begin: comments -->
                                    <div role="tabpanel" class="tab-pane in" id="comments">
                                        <?php 
                                        if($item['JobPosting']['posted_by'] == AuthComponent::user('id') ){
                                            echo $this->element( 'jobseekers/_collaborater_notes', array('apply_job_id' => $item['ApplyJob']['id'], 'job_posting_id' => $item['JobPosting']['id']) );
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <?php
                echo $this->Form->create('CollaboraterNote', array(
                        'url' => array('controller'=>'jobseekers', 'action'=>'add_rating_collaborater', 'member'=>true), 
                        'role'=>'form',
                        'class'=>'form-horizontal',
                        'inputDefaults' => array(
                            'label' => false,
                            'div' => false,
                            'class'=>'form-control',
                            'error'=>false
                        )
                ));
                #pr($item);
                echo $this->Form->hidden('user_id', array('value'=>AuthComponent::user('id')));
                echo $this->Form->hidden('apply_job_id', array('value'=>$item['ApplyJob']['id']));
                
                ?>
                <div class="form-group">
                    <label class="control-label" for="notes">Rating:</label>
                    <div class="rating " style="cursor: pointer; padding:0px; margin:0px;">
                    </div>
                </div>
                    
                <div class="form-group">
                    <label class="control-label" for="notes">Notes:</label>
                    <?php
                    $value = (!empty($my_notes)) ? $my_notes['notes'] : null ;
                    $disabled = (!empty($my_notes)) ? true : false ;
                    
                    echo $this->Form->input('notes', array (
                        'type'=>'textarea',
                        'value'=>$value,
                        'disabled'=>$disabled
                    ));
                    ?>
                </div>
                
                <div class="form-group">
                    <?php
                    if(empty($my_notes)){
                        echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-xs')); 
                    }
                    ?>
                </div>
                <?php echo $this->Form->end();?>
                
                
            </div>
        </div>
    </div>
</div>
<?php
    if(empty($my_notes)){
        $score = 0;
        $readOnly = 'false'; 
    }else{
        $score = $my_notes['rating'];
        $readOnly = 'true'; 
    }
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $('.rating').raty({
            'cancel' : true,
            'half': true,
            'halfShow' : true,
            'path': '/img/stars/',
            'scoreName': 'CollaboraterNote[rating]',
            'score': <?=$score?>,
            'readOnly': <?=$readOnly?>
        });
        
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
 });
</script>            