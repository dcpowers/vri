<?php
    #pr($this->request->data);
    #$var = ($this->request->data['Asset']['active'] == false) ? 'false' : 'true' ;
    #$in = ($this->request->data['Asset']['active'] == false) ? null : 'in' ;  
?>
<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle"><?php echo ucwords(strtolower($item)); ?></h6>
            <h3 class="dashhead-title"><i class="fa fa-cogs fa-fw"></i> Asset Settings</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Assets/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php 
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> Add New '.  $model,
                array('controller'=>'Assets', 'action'=>'manage', $item, 'add'),
                array( 'escape'=>false, 'class'=>'btn btn-success btn-sm' )
            );
            ?>                
        </div>
        <div class="flextable-item">
            <?php #echo $this->element( 'Assets/status_filter' );?>
        </div>
        <div class="flextable-item">
            <?php echo $this->element( 'Assets/settings');?>
            <?php #echo $this->element( 'Assets/search_filter', array('in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy) );?>
        </div>
    </div>
    
    
    <div class="collapse <?=$in?>" id="collapseExample" aria-expanded="<?=$var?>">
        <div class="flextable well">
            <?php #echo $this->element( 'Assets/flex_table' );?>            
        </div>
    </div>
    <?php
    switch($action){
        case "edit":
        case "add":
            echo $this->Form->create($model, array(
                'url'=>array('controller'=>'Assets', 'action'=>'manage', $item, $action),
                #'class'=>'form-horizontal',
                'role'=>'form',
                'inputDefaults'=>array(
                    'label'=>false,
                    'div'=>false,
                    'class'=>'form-control',
                    'error'=>false
                )
            ));
            
            echo $this->Form->hidden('id'); 
            ?>
            <div class="form-group">
                <label for="name" class="control-label"><?=$model?> Name:</label>
                <?php 
                echo $this->Form->input($model.'.name', array(
                    'type'=>'text',
                ));
                ?>
            </div>
            <?php 
            echo $this->Html->link(
                'Cancel',
                array('controller'=>'Assets', 'action'=>'manage', $item),
                array('escape'=>false, 'class'=>'btn btn-default')
            ); 
            ?>
            <?php 
            echo $this->Form->button('Save', array(
                'type'=>'submit', 
                'class'=>'btn btn-primary'
            )); 
            ?>
            <?php echo $this->Form->end(); ?>
            <?php
            break;
                    
        case "delete":
            break;
                    
        default;
            ?>
            <table class="table table-striped" id="assetsTable">
                <thead>
                    <tr class="tr-heading">
                        <th>
                            <?php echo $this->Paginator->sort(ucwords(strtolower($item)).'.name', 'Name');?>  
                            <?php if ($this->Paginator->sortKey() == ucwords(strtolower($item)).'.name'): ?>
                                <i class='fa fa-sort-alpha-<?php echo $this->Paginator->sortDir() === 'asc' ? 'asc' : 'desc'; ?>'></i>
                            <?php else: ?>
                                <i class='fa fa-sort'></i>
                            <?php endif; ?>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php
                    foreach($data as $asset){
                        $name = (!empty($asset[ucwords(strtolower($item))]['name'])) ? $asset[ucwords(strtolower($item))]['name'] : '--' ;
                        ?>
                        <tr>
                            <td><?=$name?></td>
                            
                            <td>
                                <?php 
                                echo $this->Html->link(
                                    '<i class="fa fa-fw fa-pencil"></i> Edit',
                                    array('controller'=>'Assets', 'action'=>'manage', $item, 'edit', $asset[ucwords(strtolower($item))]['id']),
                                    array('escape'=>false, 'class'=>'btn btn-primary btn-sm')
                                );
                                ?> 
                                
                                <?php 
                                echo $this->Html->link(
                                    '<i class="fa fa-fw fa-trash"></i> Delete',
                                    array('controller'=>'Assets', 'action'=>'manage', $item, 'delete', $asset[ucwords(strtolower($item))]['id']),
                                    array('escape'=>false, 'class'=>'btn btn-danger btn-sm'),
                                    'Are You Sure?'
                                );
                                ?> 
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php echo $this->element( 'paginate' );?>
            <?php
            break;
    }
    ?>
    
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