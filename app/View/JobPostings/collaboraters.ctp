<?php
    $this->Html->css('bootstrap-fileupload.min.css', '', array('block' => 'csslib') );
    $this->Html->script('bootstrap-fileupload.js', array('block' => 'scriptsBottom'));
    
    echo $this->Form->create('JobPosting', array(
        'type' => 'file',
        'url' => array('controller'=>'JobPostings', 'action'=>'collaboraters', 'member'=>true, $id), 
        'role'=>'form',
        'class'=>'form-horizontal',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    )); 
    echo $this->Form->hidden( 'id', array( 'value' => $id ) );
    
?>
<style type="text/css">
    .border{ 
        border: 1px #ff0000 solid;
    }
    
    .label-as-badge {
        border-radius: 5em;
    }
    
    .modal.modal-sm .modal-dialog {
        width: 100%;
    }
    
    .modal-sm .modal-body {
        overflow-y: auto;
    }
</style>

<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="bootstrap-dialog-title">Collaboraters</div>
    </div>
</div> <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <div class="row">
                <div class="col-md-12">
                <?php
                echo $this->Form->input('user_id', array(
                    'type'=>'select',
                    'options'=>$userList,
                    'multiple' => true, 
                    'default'=>$active,
                    'name'=>'user_id'
                ));
                ?>
            </div>
            
        </div> 
     </div>
</div>            <!-- /modal-body -->

<div class="modal-footer">
    <div class="bootstrap-dialog-footer">
        <div class="bootstrap-dialog-footer-buttons">  
            <?php echo $this->Form->button(
                '<i class="fa fa-times"></i><span class="text">Close</span>', 
                array('class'=>'btn btn-default', 'data-dismiss'=>'modal')
            ); ?>
            <?php echo $this->Form->button(
                '<i class="fa fa-floppy-o"></i><span class="text">Save</span>', 
                array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')
            ); ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end();?> 
<script language="JavaScript">
    
    jQuery(document).ready( function($) {
        var demo1 = $('select[name="user_id[]"]').bootstrapDualListbox({
            nonSelectedListLabel: 'Add People',
            selectedListLabel: 'Who Can View Applicants',
            
        });
        var container1 = demo1.bootstrapDualListbox('getContainer');
        container1.find('.btn').addClass('btn-xs btn-default');
    //$('select[name="duallistbox[]"]').bootstrapDualListbox();
        
    });
</script>