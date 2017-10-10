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
    
</style>
<?php  //pr($applicants); ?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="col-md-3">
                <div class="col-md-12">
                    <?php
                    $name = '../'.$applicants['DetailUser']['uploadDir'].'/'.$applicants['DetailUser']['img'];
                    $image = (!empty($applicants['DetailUser']['img'])) ? $name : 'noImage.jpg' ;
                                    
                    echo $this->Html->image(
                        $image,
                        array('class'=>'img-thumbnail', 'style'=>'width: 180px; height: 180px;')
                    );
                                    
                    ?>
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-envelope-o"></i><span class="text">Invite To Apply</span>',
                        array('controller'=>'jobs', 'action'=>'invite', 'member'=>true, $applicants['User']['id'], $jobPosting[0]['JobPosting']['id'], $tp['total']),
                        array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
                    );
                    ?>
                    
                    <address>
                        <?=$applicants['DetailUser']['address']?><br />
                        <?=$applicants['DetailUser']['city']?>
                        <?=$applicants['DetailUser']['state']?>,
                        <?=$applicants['DetailUser']['zip']?><br />
                        <abbr title="Phone">P:</abbr>&nbsp; <?=$applicants['DetailUser']['phone']?><br />
                        <abbr title="Mobile">M:</abbr>&nbsp; <?=$applicants['DetailUser']['mobile']?><br />
                        <a href="mailto:<?=$applicants['User']['username']?>"><?=$applicants['User']['username']?></a>
                    </address>
                    <hr />
                    <h5>User Files</h5>
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
            
            <div class="col-md-9">
                <div class="col-md-12">
                    <h2>
                        <?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?>
                    </h2>
                    <hr />
                    <h3>
                        Overall Match: <?=$tp['total']?>% 
                        [ 
                            <?php
                            //pr($applicants);
                            //pr($jobPosting);
                            echo $this->Html->link(
                                'View Full Hiring Report',
                                array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>'iWRaCIP_hiring', $applicants['AssignedTest'][0]['id'], $jobPosting[0]['JobPosting']['id'] ),
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
                                <th style="width: 20%">
                                    Talent Pattern 
                                    <small>( <?=$jobPosting[0]['JobTalentpattern']['name']?> )</small>
                                </th>
                                <th style="width: 20%">Variation</th>
                                <th style="width: 20%">Pattern Match</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <td>Realistic</td>
                                <td><?=$applicants['TalentpatternUser'][0]['realistic']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['realistic']?></td>
                                <td><?=$tp['rel_diff']?></td>
                                <td rowspan="6"><span class="totals"><?=$tp['total_cat1']?>%</span></td>
                            </tr>
                                        
                            <tr>
                                <td>Investigative</td>
                                <td><?=$applicants['TalentpatternUser'][0]['investigative']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['investigative']?></td>
                                <td><?=$tp['inv_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Conventional</td>
                                <td><?=$applicants['TalentpatternUser'][0]['conventional']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['conventional']?></td>
                                <td><?=$tp['con_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Social</td>
                                <td><?=$applicants['TalentpatternUser'][0]['social']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['social']?></td>
                                <td><?=$tp['soc_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Enterprising</td>
                                <td><?=$applicants['TalentpatternUser'][0]['enterprising']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['enterprising']?></td>
                                <td><?=$tp['ent_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Artistic</td>
                                <td><?=$applicants['TalentpatternUser'][0]['artistic']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['artistic']?></td>
                                <td><?=$tp['art_diff']?></td>
                            </tr>
                        </tbody>
                    </table>
                            
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr class="tr-heading">
                                <th style="width: 20%">Categories</th>
                                <th style="width: 20%"><?=$applicants['User']['first_name']?> <?=$applicants['User']['last_name']?></th>
                                <th style="width: 20%">
                                    Talent Pattern 
                                    <small>( <?=$jobPosting[0]['JobTalentpattern']['name']?> )</small>
                                </th>
                                <th style="width: 20%">Variation</th>
                                <th style="width: 20%">Pattern Match</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <td>Competitor</td>
                                <td><?=$applicants['TalentpatternUser'][0]['competitor']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['competitor']?></td>
                                <td><?=$tp['com_diff']?></td>
                                <td rowspan="4"><span class="totals"><?=$tp['total_cat2']?>%</span></td>
                            </tr>
                                        
                            <tr>
                                <td>Communicator</td>
                                <td><?=$applicants['TalentpatternUser'][0]['communicator']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['communicator']?></td>
                                <td><?=$tp['comm_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Cooperator</td>
                                <td><?=$applicants['TalentpatternUser'][0]['cooperator']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['cooperator']?></td>
                                <td><?=$tp['coo_diff']?></td>
                            </tr>
                                        
                            <tr>
                                <td>Coordinator</td>
                                <td><?=$applicants['TalentpatternUser'][0]['coordinator']?></td>
                                <td><?=$jobPosting[0]['JobTalentpattern']['coordinator']?></td>
                                <td><?=$tp['coor_diff']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i><span class="text">Close</span>', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
        </div>
    </div>
</div>