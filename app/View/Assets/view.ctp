<?php
    #pr($asset);
    #exit; 
?>
<div class="asset view bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">View/Edit Asset: <?=$asset['Asset']['asset']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Assets</h3>
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
    echo $this->Form->create('Asset', array(
        'url'=>array('controller'=>'Assets', 'action'=>'edit'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
                    
    echo $this->Form->hidden('id', array('value'=>$this->request->data['Asset']['id'])); 
    ?> 
    <h3 class="page-header pull-left">Asset Information:</h3>
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
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="control-label">Asset:</label>
                <?php 
                echo $this->Form->input( 'asset', array(
                    'type'=>'text',
                    'disabled'=>true, 
                    'class'=>'assetInputs form-control'
                )); 
                ?>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="abr" class="control-label">Tag:</label>
                <?php 
                echo $this->Form->input( 'tag_number', array(
                    'disabled'=>true, 
                    'class'=>'assetInputs form-control'
                )); 
                ?>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="abr" class="control-label">Status:</label>
                <?php 
                echo $this->Form->input('is_active', array(
                    'options'=>$status,
                    'class'=>'form-control assetInputs', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Status.....',
                    'disabled'=>true,
                )); 
                ?>
            </div>
        </div>
    </div>
    
    <div class="row" style="clear: both;">
        <div class="col-md-4">
            <div class="form-group">
                <label for="address" class="control-label">Asset Type:</label>
                <?php 
                echo $this->Form->input( 'asset_type_id', array(
                    'options'=>$assetTypeList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'data-placeholder'=>'Select An Asset Type.....',
                    'disabled'=>true,
                )); 
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="supervisor" class="control-label">Manufacturer:</label>
                <?php 
                echo $this->Form->input( 'manufacturer_id', array(
                    'options'=>$manufacturerList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Manufacturer.....',
                    'disabled'=>true, 
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="manager" class="control-label">Vendor:</label>
                <?php 
                echo $this->Form->input('vendor_id', array(
                    'options'=>$vendorList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Vendor.....',
                    'disabled'=>true, 
                ));
                ?>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="regional_admin" class="control-label">Modal:</label>
                <?php 
                echo $this->Form->input('model', array(
                    'class'=>'form-control assetInputs', 
                    'disabled'=>true,
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="department" class="control-label">Version:</label>
                <?php 
                echo $this->Form->input('version', array(
                    'class'=>'form-control assetInputs', 
                    'disabled'=>true, 
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="department" class="control-label">Purchase Date:</label>
                    <?php 
                    echo $this->Form->input('purchase_date', array(
                        'type' => 'text',
                        'class'=>'form-control assetInputs datetimepicker',
                        'disabled'=>true
                    )); 
                    ?>
                
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="status" class="control-label">Account:</label>
                <?php 
                echo $this->Form->input('account_id', array(
                    'options'=>$accountList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'multiple'=>false,
                    'data-placeholder'=>'Select An Account.....',
                    'disabled'=>true, 
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="SprocketDB" class="control-label">Assigned To:</label>
                <?php 
                echo $this->Form->input('user_id', array(
                    'options'=>$userList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'multiple'=>false,
                    'data-placeholder'=>'Select A User.....',
                    'disabled'=>true, 
                )); 
                ?>
            </div>
        </div>
    </div>
        
    <?php echo $this->Form->end();?> 
</div> 
<?php
    if(!empty($asset['Asset']['purchase_date'])){
        $splitDate = explode('-', $asset['Asset']['purchase_date']);
        $m = $splitDate[1];
        $d = $splitDate[2];
        $y = $splitDate[0];
    }else{
        $m = date('m', strtotime('now'));
        $d = date('d', strtotime('now'));
        $y = date('Y', strtotime('now'));
    }
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true,
        });
        
        $('.datetimepicker').datetimepicker({
            'format': 'YYYY-MM-DD',
            'defaultDate': moment("<?=$y?>-<?=$m?>-<?=$d?>", "YYYY-MM-DD"),
            'showTodayButton': true,
            'icons': {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                close: "fa fa-trash",
                today: 'glyphicon glyphicon-screenshot',
            }
        });
        
        $(".btn-edit").click(function(){
            $('.assetInputs').prop('disabled', false);
            $('.assetInputs').prop('disabled', false).trigger("chosen:updated");
            
            $(".btn-edit-set").hide();
            $(".btn-cancel-set").show();
            
        });
        
        $(".btn-cancel").click(function(){
            $('.assetInputs').prop('disabled', true);
            $('.assetInputs').prop('disabled', true).trigger("chosen:updated");
            
            $(".btn-edit-set").show();
            $(".btn-cancel-set").hide();
        });
        
        $('#UserFirstName').editable({
            type: 'text',
            name: 'name'
        });
    });
</script>