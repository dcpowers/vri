<?php 
    echo $this->Form->create('Asset', array(
        'url'=>array('controller'=>'Assets', 'action' => 'index'),
        'role'=>'form',
        #'class'=>'form-inline',
        'inputDefaults' => array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
    ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="area">Asset Types:</label>
                <?php
                echo $this->Form->input( 'Asset.asset_type_id', array(
                    'options'=>$types,
                    'class'=>'chzn-select form-control col-sm-3',
                    'empty'=>true,
                    'multiple'=>true,
                    'data-placeholder'=>'Select Type(s)'
                ));
                ?>
            </div>
        </div>
    
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="area">Manufacturer:</label>
                <?php
                echo $this->Form->input( 'Asset.manufacturer_id', array(
                    'options'=>$manufactures,
                    'class'=>'chzn-select form-control',
                    'empty'=>true,
                    'multiple'=>true,
                    'data-placeholder'=>'Select Manufacturer(s)'
                ));
                ?>
            </div>
        </div>
    
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="area">Accounts:</label>
                <?php
                echo $this->Form->input( 'Asset.account_id', array(
                    'options'=>$accounts,
                    'class'=>'chzn-select form-control',
                    'empty'=>true,
                    'multiple'=>true,
                    'data-placeholder'=>'Select Account(s)'
                ));
                ?>
            </div>
        </div>
    
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label" for="area">Assigned To:</label>
                <?php
                echo $this->Form->input( 'Asset.user_id', array(
                    'options'=>$assignedTo,
                    'class'=>'chzn-select form-control',
                    'empty'=>true,
                    'multiple'=>true,
                    'data-placeholder'=>'Assets Are Assigned To'
                ));
                ?>
            </div>
        </div>
    </div>
    
    <?php echo $this->Form->end(__('Submit')); ?>