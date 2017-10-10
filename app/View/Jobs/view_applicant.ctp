<?php
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
    
</style>
<?php  //pr($item);  pr($settings);?>
<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title"><?=$item['User']['first_name']?> <?=$item['User']['last_name']?></div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="col-md-3">
                <div class="col-md-12">
                    <?php
                    $name = '../'.$item['User']['DetailUser']['uploadDir'].'/'.$item['User']['DetailUser']['img'];
                    $image = (!empty($item['User']['DetailUser']['img'])) ? $name : 'noImage.jpg' ;
                                    
                    echo $this->Html->image(
                        $image,
                        array('class'=>'img-thumbnail', 'style'=>'width: 180px; height: 180px;')
                    );
                    
                    ?>
                    <div class="btn-group dropdown" style="padding: 15px 0px">
                        <button type="button" class="btn btn-default"><?=$settings['applicant_status'][$item['ApplyJob']['status']]['name']?></button>
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <?php
                            foreach($settings['applicant_status'] as $key=>$settingsitem){
                                //pr($item);
                                $class = ($key == $item['ApplyJob']['status']) ? 'active' : NULL ;
                                ?>
                                <li role="presentation" class="<?=$class?> <?=$settingsitem['class']?>">
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
                    
                    <address>
                        <?=$item['User']['DetailUser']['address']?><br />
                        <?=$item['User']['DetailUser']['city']?>
                        <?=$item['User']['DetailUser']['state']?>,
                        <?=$item['User']['DetailUser']['zip']?><br />
                        <abbr title="Phone">P:</abbr>&nbsp; <?=$item['User']['DetailUser']['phone']?><br />
                        <abbr title="Mobile">M:</abbr>&nbsp; <?=$item['User']['DetailUser']['mobile']?><br />
                        <a href="mailto:<?=$item['User']['username']?>"><?=$item['User']['username']?></a>
                    </address>
                    <hr />
                    <h5>User Files</h5>
                    <ul class="list-unstyled">
                        <?php
                        foreach($item['User']['FileUser'] as $file){
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
                        <?=$item['User']['first_name']?> <?=$item['User']['last_name']?>
                    </h2>
                    <hr />
                    <h3>
                        Overall Match: <?=$tp['total']?>% 
                        [ 
                            <?php
                            //pr($item);
                            //pr($jobPosting);
                            echo $this->Html->link(
                                'View Full Hiring Report',
                                array('member'=>false, 'plugin'=>'report', 'controller'=>'report', 'action'=>'iWRaCIP_hiring', $item['User']['AssignedTest'][0]['id'], $item['ApplyJob']['job_posting_id'] ),
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
                                <th style="width: 20%">
                                    Talent Pattern 
                                    <small>( <?=$item['JobPosting']['JobTalentpattern']['name']?> )</small>
                                </th>
                                <th style="width: 20%">Variation</th>
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
                                <th style="width: 20%">
                                    Talent Pattern 
                                    <small>( <?=$item['JobPosting']['JobTalentpattern']['name']?> )</small>
                                </th>
                                <th style="width: 20%">Variation</th>
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
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button('<i class="fa fa-times"></i> Close', array('class'=>'btn btn-default', 'data-dismiss'=>'modal')); ?>
        </div>
    </div>
</div>                    