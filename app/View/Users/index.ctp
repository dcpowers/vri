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
            <ul class="pagination pagination-sm">
                <?php
                foreach($letters as $key=>$letter){
                    if(in_array($letter, $activeLetters) || $key == 0){
                        $class = ($currentLetter == $letter) ? 'active' : null ;
                        
                        ?>
                        <li class="<?=$class?>">
                            <?php
                            echo $this->Html->link(
                                $letter, 
                                array('controller'=>'Users','action'=>'index', $letter, $status, $viewBy),
                                array( 'escape'=>false) 
                            );
                            ?>
                        </li>
                        <?php
                    }else{
                        ?>
                        <li class="disabled ">
                            <span>
                                <span aria-hidden="true"><?=$letter?></span>
                            </span>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        
            <ul class="pagination pagination-sm">
                <?php
                $activeclass = ($status == 1) ? 'active' : null ;
                $inactiveclass = ($status == 2) ? 'active' : null ;
                ?>
                <li class="<?=$activeclass?>">
                    <?php
                    echo $this->Html->link( 
                        'Active', 
                        array('controller'=>'Users', 'action'=>'index', $currentLetter, 1, $viewBy), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
                
                <li class="<?=$inactiveclass?>">
                    <?php
                    echo $this->Html->link( 
                        'Inactive', 
                        array('controller'=>'Users', 'action'=>'index', $currentLetter, 2, $viewBy), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
                    
                <li>
                    <?php
                    echo $this->Html->link( 
                        'Clear Filters',
                        array('controller'=>'Users', 'action'=>'index'), 
                        array( 'escape'=>false) 
                    );
                    ?>
                </li>
            </ul>
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
                    <th class="col-sm-3">
                        <?php 
                        echo $this->Html->link(
                            'View By Account',
                            array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'account'),
                            array('escape'=>false)
                        );
                        ?>
                    </th>
                    
                    <th class="col-sm-2">
                        <?php 
                        echo $this->Html->link(
                            'View By Department',
                            array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'department'),
                            array('escape'=>false)
                        );
                        ?>
                    </th>
                    <th class="col-sm-2">
                        <?php 
                        echo $this->Html->link(
                            'View By Role',
                            array('controller'=>'Users', 'action'=>'index', $currentLetter, $status, 'role'),
                            array('escape'=>false)
                        );
                        ?>
                    </th>
                    
                    <th class="col-sm-1">Status</th>
                    
                    <th class="col-sm-1">&nbsp;</th>
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
                                
                        <td><span class="<?=$user['Status']['color']?>"><?=$user['Status']['name']?></span></td>
                        
                        <td>
                            <?php
                            echo $this->Html->link(
                                '<i class="fa fa-eye fa-fw"></i> <span> View</span>',
                                array('controller'=>'Users', 'action'=>'view', $user['User']['id']),
                                array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
                            );
                            ?>
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