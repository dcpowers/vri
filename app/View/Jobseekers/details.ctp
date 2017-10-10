<?php
    #pr($applicants);
    
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
    
    .totals{
        margin-top: 120px;
        font-size: 3em;
    }
    
</style>
<?php  //pr($applicants); ?>
<div style="margin-top: -20px">
    <div class="headerDiv">
        
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="col-md-12 effect3 thumbnail headerContent">
                    <?php
                    $name = '../'.$applicants['DetailUser']['uploadDir'].'/'.$applicants['DetailUser']['img'];
                    $image = (!empty($applicants['DetailUser']['img'])) ? $name : 'noImage.jpg' ;
                                        
                    echo $this->Html->image($image, array('class'=>'img-thumbnail '));
                    
                    ?>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <?php
                            echo $this->Html->link(
                                'Invite To Apply',
                                array('controller'=>'jobseekers', 'action'=>'invite', 'member'=>true, $applicants['User']['id'], $jobPosting['JobPosting']['id'], $tp['total']),
                                array('escape'=>false)
                            );
                            ?>
                        </li>
                        <li>
                            <?php
                            echo $this->Html->link(
                                'Not A Fit',
                                array('controller'=>'jobseekers', 'action'=>'exempt', 'member'=>true, $applicants['User']['id'], $jobPosting['JobPosting']['id'], $tp['total']),
                                array('escape'=>false)
                            );
                            ?>
                        </li>
                    </ul>
                </div>
                
                    
                <address class="spacer">
                    <?=$applicants['DetailUser']['address']?><br />
                    <?=$applicants['DetailUser']['city']?>,
                    <?=$applicants['DetailUser']['State']['state_name']?>
                    <?=$applicants['DetailUser']['zip']?><br />
                    <abbr title="Phone">P:</abbr>&nbsp; <?=$applicants['DetailUser']['phone']?><br />
                    <abbr title="Mobile">M:</abbr>&nbsp; <?=$applicants['DetailUser']['mobile']?><br />
                    <a href="mailto:<?=$applicants['User']['username']?>"><?=$applicants['User']['username']?></a>
                </address>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12 headerContent">
                        <h2 class="title">
                            <?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?>
                        </h2>
                    </div>
                    
                    <div class="col-md-12">
                        <div role="tabbable-panel">
                            <div class="tabbable-line tabs-below">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#hiring" aria-controls="job" role="tab" data-toggle="tab">FIT Report</a></li>
                                    <li role="presentation"><a href="#documents" aria-controls="testing" role="tab" data-toggle="tab">User Documents</a></li>
                                </ul>
                                
                                <!-- Tab panes -->
                                <div class="tab-content">
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
                                                    array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>'iWRaCIP_hiring', $applicants['AssignedTest'][0]['id'], $jobPosting['JobPosting']['id'] ),
                                                    array('escape'=>false,)
                                                );
                                                ?> 
                                                ]
                                            
                                        </h3>
                                                
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th style="width: 20%">Categories</th>
                                                    <th style="width: 20%"><?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?></th>
                                                    <th style="width: 25%">
                                                        Talent Pattern 
                                                        <small>( <?=$jobPosting['JobTalentpattern']['name']?> )</small>
                                                    </th>
                                                    <th style="width: 15%">Variation</th>
                                                    <th style="width: 20%">Pattern Match</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr>
                                                    <td>Realistic</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['realistic']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['realistic']?></td>
                                                    <td><?=$tp['rel_diff']?></td>
                                                    <td rowspan="6"><span class="totals"><?=$tp['total_cat1']?>%</span></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Investigative</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['investigative']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['investigative']?></td>
                                                    <td><?=$tp['inv_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Conventional</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['conventional']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['conventional']?></td>
                                                    <td><?=$tp['con_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Social</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['social']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['social']?></td>
                                                    <td><?=$tp['soc_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Enterprising</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['enterprising']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['enterprising']?></td>
                                                    <td><?=$tp['ent_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Artistic</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['artistic']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['artistic']?></td>
                                                    <td><?=$tp['art_diff']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                                
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                                <tr class="tr-heading">
                                                    <th style="width: 20%">Categories</th>
                                                    <th style="width: 20%"><?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?></th>
                                                    <th style="width: 25%">
                                                        Talent Pattern 
                                                        <small>( <?=$jobPosting['JobTalentpattern']['name']?> )</small>
                                                    </th>
                                                    <th style="width: 15%">Variation</th>
                                                    <th style="width: 20%">Pattern Match</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr>
                                                    <td>Competitor</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['competitor']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['competitor']?></td>
                                                    <td><?=$tp['com_diff']?></td>
                                                    <td rowspan="4"><span class="totals"><?=$tp['total_cat2']?>%</span></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Communicator</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['communicator']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['communicator']?></td>
                                                    <td><?=$tp['comm_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Cooperator</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['cooperator']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['cooperator']?></td>
                                                    <td><?=$tp['coo_diff']?></td>
                                                </tr>
                                                            
                                                <tr>
                                                    <td>Coordinator</td>
                                                    <td><?=$applicants['TalentpatternUser'][0]['coordinator']?></td>
                                                    <td><?=$jobPosting['JobTalentpattern']['coordinator']?></td>
                                                    <td><?=$tp['coor_diff']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End: Hiring -->
                                    
                                    <!-- Begin: Documents -->
                                    <div role="tabpanel" class="tab-pane in" id="documents">
                                        <h3>User Files</h3>
                                        <ul class="list-unstyled">
                                            <?php
                                            foreach($applicants['FileUser'] as $file){
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>