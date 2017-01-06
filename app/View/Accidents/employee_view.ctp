<?php
                foreach($employees as $department=>$employee){
                    ?>
                    <div class="hr-divider">
                        <h3 class="hr-divider-content hr-divider-heading">
                            <?=$department?>
                        </h3>
                    </div>
                    <table class="table table-striped table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-2">Name</th>
                                <th class="col-sm-2">Username</th>
                                <th class="col-sm-2">E-Mail</th>
                                <th class="col-sm-2">Role</th>
                                <th class="col-sm-2">Supervisor</th>
                                <th class="col-sm-2">Status</th>
                            </tr>
                        </thead>
                                
                        <tbody>
                            <?php
                            foreach($employee as $user){
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        echo $this->Html->link(
                                            $user['first_name'].' '.$user['last_name'],
                                            array('controller'=>'Users', 'action'=>'view', $user['id']),
                                            array('escape'=>false)
                                        );
                                        ?>
                                    </td>
                                            
                                    <td><?=$user['username']?></td>
                                            
                                    <td><?=$user['email']?></td>
                                            
                                    <td><?=$user['Role']['name']?></td>
                                    <td>
                                        <?php
                                        if(array_key_exists('first_name', $user['Supervisor'])){
                                            echo $user['Supervisor']['first_name'].' '.$user['Supervisor']['last_name'];
                                        }else{
                                            echo '--';
                                        }
                                        ?>
                                    </td>
                                    <td><span class="<?=$user['Status']['color']?>"><?=$user['Status']['name']?></span></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>