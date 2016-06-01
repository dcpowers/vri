<?php
    #pr($settings);
    #exit; 
?>
<div class="account view">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">View/Edit Account: <?=$account['Account']['name']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Accounts</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
        </div>
    </div>
    
    <?php 
    echo $this->Form->create('Account', array(
        'url'=>array('controller'=>'Accounts', 'action'=>'edit'),
        'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
                    
    echo $this->Form->hidden('id', array('value'=>$this->request->data['Account']['id'])); 
    ?> 
    <h3 class="page-header pull-left">Account Information:</h3>
    <ul class="pagination pagination-sm" style="margin: 10px 0px 10px 10px;">
        <li class="btn-edit-set">
            <?php 
            echo $this->Form->button(
                '<i class="fa fa-pencil fa-fw"></i> <span> Edit</span>',
                array('type'=>'button', 'class'=>'btn btn-default btn-sm btn-edit')
            );
            ?>
        </li>
        
        <li class="btn-cancel-set" style="display: none">
            <?php
            echo $this->Form->button(
                '<i class="fa fa-close fa-fw"></i> <span> Cancel</span>',
                array('type'=>'button', 'class'=>'btn btn-default btn-sm btn-cancel')
            );
            ?>
        </li>
        
        <li class="btn-cancel-set" style="display: none">
            <?php
            echo $this->Form->button(
                '<i class="fa fa-save fa-fw"></i> Save', 
                array('type'=>'submit', 'class'=>'btn btn-primary btn-sm')
            );
            ?> 
        </li>
    </ul>
    
    <div class="row" style="clear: both;">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label">Name:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input( 'name', array(
                        'disabled'=>true, 
                        'class'=>'accountInputs form-control'
                    )); 
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="abr" class="col-sm-4 control-label">Abbreviation:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input( 'abr', array(
                        'disabled'=>true, 
                        'class'=>'accountInputs form-control'
                    )); 
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="address" class="col-sm-4 control-label">Address:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input( 'address', array(
                        'disabled'=>true, 
                        'class'=>'accountInputs form-control'
                    )); 
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="supervisor" class="col-sm-4 control-label">Supervisor:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input( 'manager_id', array(
                        'options'=>$userList,
                        'class'=>'chzn-select form-control accountInputs', 
                        'empty' => true,
                        'data-placeholder'=>'Select a Supervisor.....',
                        'disabled'=>true, 
                    ));
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="manager" class="col-sm-4 control-label">Systems Coordinator:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('coordinator_id', array(
                        'options'=>$userList,
                        'class'=>'chzn-select form-control accountInputs', 
                        'empty' => true,
                        'data-placeholder'=>'Select a Systems Coordinator.....',
                        'disabled'=>true, 
                    ));
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="regional_admin" class="col-sm-4 control-label">Regional Administrator:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('regional_admin_id', array(
                        'options'=>$userList['Vanguard Resources'],
                        'class'=>'chzn-select form-control accountInputs', 
                        'empty' => true,
                        'data-placeholder'=>'Select a Regional Administrator.....',
                        'disabled'=>true,
                    ));
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="department" class="col-sm-4 control-label">Department(s):</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('AccountDepartment.department_id', array(
                        'options'=>$departments,
                        'class'=>'chzn-select form-control accountInputs', 
                        'empty' => true,
                        'multiple'=>true,
                        'data-placeholder'=>'Select Department(s).....',
                        'disabled'=>true, 
                    ));
                    ?>
                </div>
            </div>
        </div>
                
        <div class="col-sm-6">
            <div class="form-group">
                <label for="status" class="col-sm-4 control-label">Account Status:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('is_active', array(
                        'options'=>$status,
                        'value'=>$account['Status']['id'],
                        'class'=>'chzn-select form-control accountInputs', 
                        'empty' => true,
                        'multiple'=>false,
                        'data-placeholder'=>'Select Account Status.....',
                        'disabled'=>true, 
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="SprocketDB" class="col-sm-4 control-label">SprocketDB:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('SprocketDB', array(
                        'disabled'=>true, 
                        'class'=>'accountInputs form-control'
                    )); 
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="AllPayID" class="col-sm-4 control-label">All Pay Id:</label>
                <div class="col-sm-8">
                    <?php 
                    echo $this->Form->input('AllPayID', array(
                        'disabled'=>true, 
                        'class'=>'accountInputs form-control'
                    )); 
                    ?>
                </div>
            </div>
                    
            <div class="form-group">
                <label for="AllPayID" class="col-sm-4 control-label">Old Department(s):</label>
                <div class="col-sm-8">
                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('EVS', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            EVS
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('CE', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            CE
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('Food', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            Food
                        </label>
                    </div>
                            
                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('POM', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            POM
                        </label>
                    </div>
                            
                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('LAU', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            LAU
                        </label>
                    </div>
                            
                    <div class="checkbox">
                        <label>
                            <?php 
                            echo $this->Form->checkbox('SEC', array(
                                'disabled'=>true, 
                                'class'=>'accountInputs'
                            )); 
                            ?>
                            SEC
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end();?> 
    <h3 class="page-header">Employees:</h3>
    <div class="row">
        <div class="col-sm-4">
            <ul class="pagination pagination-sm">
                <li class="<?=$deptClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Department',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], $pageStatus, 'department'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li class="<?=$roleClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Role',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], $pageStatus, 'role'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li class="<?=$superClass?>">
                    <?php
                    echo $this->Html->link(
                        'View By Supervisor',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], $pageStatus, 'supervisor'),
                        array('escape'=>false)
                    );
                    ?>
                </li>
            </ul>
        </div>
        <div class="col-sm-8">
            <ul class="pagination pagination-sm">
                <li class="<?=$aStatusClass?>">
                    <?php
                    echo $this->Html->link(
                        'Active',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], 1, $viewBy),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li class="<?=$iStatusClass?>">
                    <?php
                    echo $this->Html->link(
                        'Inactive',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], 2, $viewBy),
                        array('escape'=>false)
                    );
                    ?>
                </li>
                
                <li class="<?=$allStatusClass?>">
                    <?php
                    echo $this->Html->link(
                        'View All',
                        array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id'], 'all', $viewBy),
                        array('escape'=>false)
                    );
                    ?>
                </li>
            </ul>
        </div>
    </div>
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
</div> 

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
        $(".btn-edit").click(function(){
            $('.accountInputs').prop('disabled', false);
            $('.accountInputs').prop('disabled', false).trigger("chosen:updated");
            
            $(".btn-edit-set").hide();
            $(".btn-cancel-set").show();
            
        });
        
        $(".btn-cancel").click(function(){
            $('.accountInputs').prop('disabled', true);
            $('.accountInputs').prop('disabled', true).trigger("chosen:updated");
            
            $(".btn-edit-set").show();
            $(".btn-cancel-set").hide();
        });
        
        $('#UserFirstName').editable({
            type: 'text',
            name: 'name'
        });
    });
</script>