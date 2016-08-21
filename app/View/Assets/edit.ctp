<?php
    #pr($asset);
    #exit; 
?>
<div class="asset view bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">Edit Asset: <?=$asset['Asset']['asset']?></h6>
            <h3 class="dashhead-title"><i class="fa fa-car fa-fw"></i> Assets</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php echo $this->element( 'Assets/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Assets/menu' );?>                
        </div>
    </div>
    
    <?php 
    echo $this->Form->create('Asset', array(
        'url'=>array('controller'=>'Assets', 'action'=>'edit', $this->request->data['Asset']['id']),
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
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="control-label">Asset:</label>
                <?php 
                echo $this->Form->input( 'asset', array(
                    'type'=>'text',
                )); 
                ?>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="abr" class="control-label">Tag:</label>
                <?php 
                echo $this->Form->input( 'tag_number', array(
                    'type'=>'text',
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
                )); 
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="address" class="control-label">Asset Type:</label>
                <?php 
                echo $this->Form->input( 'asset_type_id', array(
                    'options'=>$assetTypeList,
                    'class'=>'chzn-select form-control assetInputs', 
                    'empty' => true,
                    'data-placeholder'=>'Select An Asset Type.....',
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
                    'type'=>'text',
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="department" class="control-label">Version:</label>
                <?php 
                echo $this->Form->input('version', array(
                    'type'=>'text',
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
                )); 
                ?>
            </div>
        </div>
    </div>
    <?php 
    echo $this->Html->link(
        'Cancel',
        array('controller'=>'Assets', 'action'=>'view', $id),
        array('escape'=>false, 'class'=>'btn btn-default')
    ); 
    ?>
    <?php 
    echo $this->Form->button('Save', array(
        'type'=>'submit', 
        'class'=>'btn btn-primary'
    )); 
    ?>    
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
    <?php
    $groupRequest_url = $this->Html->url(array('plugin'=>false, 'controller'=>'Assets', 'action' => 'userList'));
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
        
        $('#AssetAccountId').change(function() {
            $.ajax({
                type: 'post',
                url: '<?=$groupRequest_url?>/' + $(this).val() + '.json',
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                },
                success: function(response) {
                    //console.log(response);
                    $('#AssetUserId').html(response);
                    $('#AssetUserId' ).val('').trigger( 'chosen:updated' );
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function(){
                    //$('#overlay').remove();
                },
            });
        });
    });
</script>