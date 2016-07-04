<?php
    #pr($users);
    #exit; 
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Employees</h6>
            <h3 class="dashhead-title"><i class="fa fa-users fa-fw"></i> Employees</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'users/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php 
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add New Employee', 
                array('controller'=>'Users', 'action'=>'add' ),
                array('escape'=>false, 'class'=>'btn btn-primary btn-sm btn-outline') 
            );
            ?>
                    
            <div class="btn-group pull-right">
                        <?php 
                        echo $this->Form->button(
                            '<i class="fa fa-eye fa-fw"></i> Sort By <i class="fa fa-fw fa-caret-down"></i>', 
                            array(
                                'type'=>'button', 
                                'escape'=>false, 
                                'class'=>'btn btn-primary btn-sm btn-outline dropdown-toggle',
                                'data-toggle'=>'dropdown', 
                                'aria-haspopup'=>'true', 
                                'aria-expanded'=>'false'          
                            ) 
                        );
                        ?>
                        <ul class="dropdown-menu">
                            <li>
                                <?php 
                                echo $this->Html->link(
                                    'Sort By Account',
                                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'account'),
                                    array('escape'=>false)
                                );
                                ?>
                            </li>
                            <li>
                                <?php 
                                echo $this->Html->link(
                                    'Sort By Department',
                                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'department'),
                                    array('escape'=>false)
                                );
                                ?>
                            </li>
                            <li>
                                <?php 
                                echo $this->Html->link(
                                    'Sort By Role',
                                    array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'role'),
                                    array('escape'=>false)
                                );
                                ?>
                            </li>
                        </ul>
                    </div>
            <?php 
            $var = ($status == 'All' && $currentLetter == 'All') ? 'false' : 'true' ;
            $in = ($status == 'All' && $currentLetter == 'All') ? null : 'in' ;
                        
            echo $this->Form->button(
                '<i class="fa fa-search fa-fw"></i> Search Filter <i class="fa fa-fw fa-caret-down"></i>', 
                array(
                    'type'=>'button', 
                    'escape'=>false, 
                    'class'=>'btn btn-primary btn-sm btn-outline pull-right',
                    'data-toggle'=>'collapse', 
                    'data-target'=>'#collapseExample', 
                    'aria-expanded'=>$var, 
                    'aria-controls'=>'collapseExample',
                    'style'=>'margin-right: 5px;'
                ) 
            );
            ?>
            
        </div>
    </div>
    
    <div id="employeeList">
        <div class="collapse <?=$in?>" id="collapseExample" aria-expanded="<?=$var?>">
            <div class="flextable">
                <?php echo $this->element( 'users/flex_table' );?>            
            </div>
        </div>
        <?php
        foreach($users as $title=>$item){
            ?>
            <div class="hr-divider">
                <h3 class="hr-divider-content hr-divider-heading">
                    <?=$title?>
                </h3>
            </div>
            <table class="table table-striped" id="accountsTable">
                <thead>
                    <tr class="tr-heading">
                        <th class="col-sm-4">Employee</th>
                        <th class="col-sm-3">Account</th>
                        <th class="col-sm-2">Department</th>
                        <th class="col-sm-2">Role</th>
                        <th class="col-sm-1 text-center">Status</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    foreach($item as $user){
                        $emp_name = $user['User']['first_name'] .' '. $user['User']['last_name'];
                        
                        ?>
                        <tr>
                            <td>
                                <?php 
                                echo $this->Html->link(
                                    $emp_name,
                                    array('controller'=>'Users', 'action'=>'view', $user['User']['id']),
                                    array('escape'=>false)
                                );
                                ?> 
                            </td>
                                    
                            <td><?=$user['Account']['name']?></td>
                            
                            <td><?=$user['Department']['name']?></td>
                            
                            <td><?=$user['Role']['name']?></td>
                                    
                            <td class="text-center">
                                <span class="<?=$user['Status']['color']?> label-as-badge"><i class="fa <?=$user['Status']['icon']?>"></i></span>
                            </td>
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
    <?php echo $this->element( 'paginate' );?>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModalBig").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $(".modal-wide").on("show.bs.modal", function() {
          var height = $(window).height() - 200;
          $(this).find(".modal-body").css("max-height", height);
        });
     });
</script>