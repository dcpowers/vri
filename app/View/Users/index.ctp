    <?php
    #pr($users);
    #exit;
    $var = ($currentLetter == 'All') ? 'false' : 'true' ;
    $in = ($currentLetter == 'All') ? null : 'in' ; 
    ?>
    <div class="account index bg-white">
        <div class="dashhead">
            <div class="dashhead-titles">
                <h6 class="dashhead-subtitle">List Of Employees</h6>
                <h3 class="dashhead-title"><i class="fa fa-users fa-fw"></i>Employees: <?=$title?></h3>
            </div>
            <div class="dashhead-toolbar">
                <?php echo $this->element( 'Users/search' );?>
            </div>
        </div>
        <div class="flextable">
            <div class="flextable-item">
                <?php echo $this->element( 'Users/menu' );?>                
            </div>
            <div class="flextable-item">
                <?php echo $this->element( 'Users/status_filter' );?>
            </div>
            <div class="flextable-item">
                <?php echo $this->element( 'Users/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
            </div>
        </div>
        
        <div id="employeeList">
            <div class="collapse <?=$in?>" id="collapseExample" aria-expanded="<?=$var?>">
                <div class="flextable well">
                    <?php echo $this->element( 'Users/flex_table' );?>
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
                            <th class="col-sm-3">Employee</th>
                            <?php
                            if(AuthComponent::user('Role.permission_level') >= 50){
                                ?>
                                <th class="col-sm-3">Account(s)</th>
                                <?php
                            }
                            ?>
                            <th class="col-sm-3">Department(s)</th>
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
                                <?php
                                if(AuthComponent::user('Role.permission_level') >= 50){
                                    ?>        
                                    <td>
                                        <ul class="list-unstyled">
                                            <?php
                                            foreach($user['AccountUser'] as $acct){
                                                if(!empty($acct['Account'])){
                                                    ?>
                                                    <li>
                                                        <?php 
                                                        echo $this->Html->link(
                                                            $acct['Account']['name'],
                                                            array('controller'=>'Accounts', 'action'=>'view', $acct['Account']['id']),
                                                            array('escape'=>false)
                                                        );
                                                        ?>
                                                        </li>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <li><span class="label label-danger">N/A</span></li>
                                                    <?php
                                                }
                                            }      
                                            ?>
                                        </ul>
                                    </td>
                                    <?php
                                }
                                ?>
                                
                                <td>
                                    <ul class="list-unstyled">
                                        <?php
                                        foreach($user['DepartmentUser'] as $dept){
                                            if(!empty($dept['Department'])){
                                                ?>
                                                <li><?=$dept['Department']['name']?></li>
                                                <?php
                                            }else{
                                                ?>
                                                <li><span class="label label-danger">N/A</span></li>
                                                <?php    
                                            }
                                        }
                                        ?>
                                    </ul>
                                </td>
                                
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