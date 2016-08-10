<?php
    #pr($settings);
    #exit; 
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Accounts</h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Accounts</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Accounts/menu' );?>                
        </div>
        <div class="flextable-item">
            <?php echo $this->element( 'Accounts/status_filter' );?>
        </div>
        <div class="flextable-item">                    
            <?php #echo $this->element( 'Accounts/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>

    <table class="table table-striped" id="accountsTable">
        <thead>
            <tr class="tr-heading">
                <th>
                            <?php echo $this->Paginator->sort('Account.name', 'Account Name');?>  
                            <?php if ($this->Paginator->sortKey() == 'Account.name'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                            <?php endif; ?>
                </th>
                
                <th>
                            <?php echo $this->Paginator->sort('Manager.first_name', 'General Manager');?>  
                            <?php if ($this->Paginator->sortKey() == 'Manager.first_name'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                            <?php endif; ?>
                </th>
                
                <th>
                            <?php echo $this->Paginator->sort('Coordinator.first_name', 'Systems Coordinator');?>  
                            <?php if ($this->Paginator->sortKey() == 'Coordinator.first_name'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                           <?php endif; ?>
                </th>
                        
                <th>
                            <?php echo $this->Paginator->sort('RegionalAdmin.first_name', 'Regional Administrator');?>  
                            <?php if ($this->Paginator->sortKey() == 'RegionalAdmin.first_name'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                            <?php endif; ?>
                </th>
                
                <th class="text-center">
                            <?php echo $this->Paginator->sort('Account.is_active', 'Status');?>  
                            <?php if ($this->Paginator->sortKey() == 'Account.is_active'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                            <?php endif; ?>
                </th>
                
                <th class="text-center">Active Employee Count</th>
            </tr>
        </thead>
        
        <tbody>
            <?php
            foreach($accounts as $account){
                $manager_name = $account['Manager']['first_name'] .' '. $account['Manager']['last_name'];
                $coordinator_name = $account['Coordinator']['first_name'] .' '. $account['Coordinator']['last_name'];
                $regional_admin_name = $account['RegionalAdmin']['first_name'] .' '. $account['RegionalAdmin']['last_name'];
                ?>
                <tr>
                    <td>
                        <?php 
                        echo $this->Html->link(
                            $account['Account']['name'].' ( '.$account['Account']['abr'].' )',
                            array('controller'=>'Accounts', 'action'=>'view', $account['Account']['id']),
                            array('escape'=>false)
                        );
                        ?> 
                    </td>
                            
                    <td><?=$manager_name?></td>
                            
                    <td><?=$coordinator_name?></td>
                            
                    <td><?=$regional_admin_name?></td>
                            
                    <td class="text-center">
                        <span class="<?=$account['Status']['color']?> label-as-badge"><i class="fa <?=$account['Status']['icon']?>"></i></span>
                    </td>
                    
                    <td class="text-center"><?php echo count($account['User']); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
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