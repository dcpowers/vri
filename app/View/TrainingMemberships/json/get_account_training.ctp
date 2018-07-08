<table class="table table-striped table-condensed" id="assetsTable">
    <thead>
        <tr class="tr-heading">
            <th class="col-md-4">Training</th>
            <th>Required</th>
            <th>Corp Required</th>
            <th class="col-md-2">Renewal Length</th>
            <th class="col-md-4">Required For</th>
        </tr>
    </thead>

    <tbody>
        <?php
        #pr($trainings);
        #exit;
        foreach($trainings as $title=>$trn){
            #pr($trn);
            #exit;
            $required = ($trn[0]['TrainingMembership']['is_required'] ==1) ? '<i class="fa fa-check-circle fa-lg" aria-hidden="true" style="color: #00A65A" ></i>' : '<i class="fa fa-times-circle fa-lg" aria-hidden="true" style="color: #DD4B39"></i>' ;
            $maditory = ($trn[0]['TrainingMembership']['is_manditory'] ==1) ? '<i class="fa fa-check-circle fa-lg" aria-hidden="true" style="color: #00A65A" ></i>' : '<i class="fa fa-times-circle fa-lg" aria-hidden="true" style="color: #DD4B39"></i>' ;

            foreach($trn as $record){
                if(!empty($record['Department'])){
                    $requiredFor['Departments'][] = $record['Department']['name'];
                }

                if(!empty($record['RequiredUser'])){
                    $requiredFor['Users'][] = $record['RequiredUser']['first_name'] .' '.$record['RequiredUser']['last_name'];
                }
            }

            ?>
            <tr>
                <td>
                    <?php
                    echo $this->Html->link(
                        $trn[0]['Training']['name'],
                        array('controller'=>'Trainings', 'action'=>'acctDetails', $trn[0]['Training']['id'], $trn[0]['TrainingMembership']['account_id'] ),
                        array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myLgModal')
                    );
                    ?>
                </td>

                <td class="text-center"><?=$required?></td>

                <td class="text-center"><?=$maditory?></td>

                <td><?=$trn[0]['TrainingMembership']['renewal']?> Mo(s)</td>

                <td>
                    <?php
                    if(!empty($requiredFor)){
                        foreach($requiredFor as $key=>$val){
                            ?>
                            <ul>
                                <li><?=$key?>
                                    <ul>
                                        <?php
                                        foreach($val as $item){
                                            ?>
                                            <li><?=$item?></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                            <?php
                        }
                    }else if($trn[0]['TrainingMembership']['is_required'] ==1){
                        ?>
                        <ul>
                            <li>Everyone</li>
                        </ul>
                        <?php
                    }else{
                        ?>
                        <ul>
                            <li>--</li>
                        </ul>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
            unset($requiredFor);
        }
        ?>
    </tbody>
</table>