<?php
    #pr($assets);
    #exit; 
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Assets</h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> All Assets</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'accounts/dashhead_toolbar' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item flextable-primary">
            <?php    
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add Asset', 
                array('plugin'=>false, 'controller'=>'Assets', 'action'=>'add'),
                array('escape'=>false, 'class'=>'btn btn-primary btn-outline btn-sm', 'data-toggle'=>'modal','data-target'=>'#myLgModal'  ) 
            );
            ?>
        </div>
    </div>
    
    <?php echo $this->element( 'Assets/search_bar' );?>    
    
    <div class="collapse <?=$in?>" id="collapseExample" aria-expanded="<?=$var?>">
            <div class="flextable">
                <?php echo $this->element( 'users/flex_table' );?>            
            </div>
        </div>
    
    <?php
    foreach($assets as $title=>$data){
        ?>
        <div class="hr-divider">
            <h3 class="hr-divider-content hr-divider-heading">
                <?=$title?>
            </h3>
        </div>
        <table class="table table-striped" id="assetsTable">
            <thead>
                <tr class="tr-heading">
                    <th class="col-md-2">
                        <?php echo $this->Paginator->sort('Asset.asset', 'Asset');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.asset_type_id'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                    
                    <th class="col-md-1">
                        <?php echo $this->Paginator->sort('Asset.tag_number', 'Tag');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.tag_number'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                    
                    <th class="col-md-2">
                        <?php echo $this->Paginator->sort('Asset.manufacturer_id', 'Manufacturer');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.manufacturer_id'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                            
                    <th class="col-md-2">
                        <?php echo $this->Paginator->sort('Asset.model', 'Model');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.model'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                    
                    <!--<th>
                        <?php echo $this->Paginator->sort('Asset.serial_number', 'Serial');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.serial_number'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>-->
                    
                    <th class="col-md-3">
                        <?php echo $this->Paginator->sort('Asset.account_id', 'Account');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.account_id'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                    
                    <th class="col-md-2">
                        <?php echo $this->Paginator->sort('Asset.user_id', 'Assigned To');?>  
                        <?php if ($this->Paginator->sortKey() == 'Asset.user_id'): ?>
                            <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                        <?php else: ?>
                            <i class='fa fa-sort'></i>
                        <?php endif; ?>
                    </th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                foreach($data as $asset){
                    ?>
                    <tr>
                        <td>
                            <?php 
                            echo $this->Html->link(
                                $asset['Asset']['asset'],
                                array('controller'=>'Assets', 'action'=>'view', $asset['Asset']['id']),
                                array('escape'=>false)
                            );
                            ?> 
                        </td>
                                
                        <td><?=$asset['Asset']['tag_number']?></td>
                                
                        <td><?=$asset['Manufacturer']['name']?></td>
                                
                        <td><?=$asset['Asset']['model']?></td>
                                
                        <!--<td><?=$asset['Asset']['serial_number']?></td>-->
                        
                        <td><?=$asset['Account']['name']?></td>
                        
                        <td><?=$asset['AssignedTo']['first_name']?> <?=$asset['AssignedTo']['last_name']?></td>
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
    
    <div class="clearfix"></div>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
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