<div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-search"></i> <span class="break"></span>Search Filters</h3>
            </div>
            <div class="panel-body">
                <ul class="pagination pagination-sm">
                    <li>
                        <?php
                        echo $this->Html->link( 
                            'Active', 
                            array('controller'=>'Assets', 'action'=>'index', 'all', 1), 
                            array( 'escape'=>false, 'class'=>'btn-group btn btn-default')
                        );
                        ?>
                    </li>
                    
                    <li>
                        <?php
                        echo $this->Html->link( 
                            'Inactive', 
                            array('controller'=>'Assets', 'action'=>'index', 'all', 2), 
                            array( 'escape'=>false, 'class'=>'btn-group btn btn-default') 
                        );
                        ?>
                    </li>
                    
                    <li>
                        <?php
                        echo $this->Html->link( 
                            'Clear Filters',
                            array('controller'=>'Assets', 'action'=>'index'), 
                            array( 'escape'=>false, 'class'=>'btn-group btn btn-default')
                        );
                        ?>
                    </li>
                </ul>
                
                <?php 
                echo $this->Form->create('Asset', array(
                    'url'=>array('controller'=>'Assets', 'action' => 'index'),
                    'role'=>'form',
                    #'class'=>'form-horizontal',
                    'inputDefaults' => array(
                        'label'=>false,
                        'div'=>false,
                        'class'=>'form-control',
                        'error'=>false
                    )
                ));
                ?>
                <div class="form-group">
                    <label class="control-label" for="area">Types:</label>
                    <?php
                    echo $this->Form->input( 'Asset.asset_type_id', array(
                        'options'=>$types,
                        'class'=>'chzn-select form-control',
                        'empty'=>true,
                        'multiple'=>true
                    ));
                    ?>
                </div>
                
                <div class="form-group">
                    <label class="control-label" for="area">Manufacturer:</label>
                    <?php
                    echo $this->Form->input( 'Asset.manufacturer_id', array(
                        'options'=>$manufactures,
                        'class'=>'chzn-select form-control',
                        'empty'=>true,
                        'multiple'=>true
                    ));
                    ?>
                </div>
                
                <div class="form-group">
                    <label class="control-label" for="area">Accounts:</label>
                    <?php
                    echo $this->Form->input( 'Asset.account_id', array(
                        'options'=>$accounts,
                        'class'=>'chzn-select form-control',
                        'empty'=>true,
                        'multiple'=>true
                    ));
                    ?>
                </div>
                
                <div class="form-group">
                    <label class="control-label" for="area">Assigned To:</label>
                    <?php
                    echo $this->Form->input( 'Asset.user_id', array(
                        'options'=>$assignedTo,
                        'class'=>'chzn-select form-control',
                        'empty'=>true,
                        'multiple'=>true
                    ));
                    ?>
                </div>
                
                <div class="form-group">
                    <label class="control-label" for="area">Order By:</label>
                    <?php
                    echo $this->Form->input( 'Search.orderBy', array(
                        'options'=>array('type'=>'Asset Type', 'manufacturer'=>'Manufacturer', 'account'=>'Account', 'assignedTo'=>'Assigned To' ),
                        'class'=>'chzn-select form-control',
                        'empty'=>false,
                        'multiple'=>false
                    ));
                    ?>
                </div>
                <?php echo $this->Form->end(__('Submit')); ?>
            </div>
        </div>