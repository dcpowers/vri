<div class="admin groups index row-fluid">
    <div class="span12">
        <h1><?php echo __('Add New Group'); ?></h1>
        <?php 
            echo $this->Form->create('Group', array('class'=>'form-horizontal'));
            echo $this->Form->input('id');
        ?>
        <div class="row-fluid">
            <div class="span4">
                <label>Group Name</label>
                <?php echo $this->Form->input('name', array('label'=>false, 'data-placeholder' => 'Group Name')); ?> 
            </div>
            
        </div>    
        <div class="submit">
            <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
            <?php echo $this->Html->link( __('Cancel'), array('controller'=>'groups', 'action'=>'index' ), array('class'=>'btn')  ); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script language="JavaScript">
    jQuery(document).ready( function($) {
        $(".chzn-select").chosen();
        $(".cleditor").cleditor();
        $('#mySwitch').on('switch-change', function (e, data) {
            var $el = $(data.el)
            , value = data.value;
            
            console.log(e, $el, value);
        });
    });
</script>

    