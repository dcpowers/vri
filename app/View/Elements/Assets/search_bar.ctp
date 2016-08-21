       <div>
            <div>
                
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
                
                
                <?php echo $this->Form->end(__('Submit')); ?>
            </div>
        </div>