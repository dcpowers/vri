<?php 
    $user = AuthComponent::user();
    $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
    $group_ops =array('is_inspectable'=>'Does Inspections','is_training'=>'Has HSE Training', 'is_timecard'=>'Uses Timecards', 'is_service_request'=>'Does Service Requests', 'is_mail' => 'Is a Mailing List' );
    
    $selection_super[] = $this->request->data['Group']['supervisor_id'];
    
    $selection_opts = array_keys($this->request->data['Group'], "1");
    //pr($group_ops);
    //pr($selection_opts);
    
?>
<div class="admin groups index row-fluid">
    <div class="span12">
        <h1><?php echo __('Edit Group'); ?> <small><?php echo $this->request->data['Group']['name']; ?></small></h1>
        <?php 
        echo $this->Form->create('Group', array('class'=>'form-horizontal'));
        echo $this->Form->input('id');
        $cur_opts = array_flip($group_ops);
        
        foreach ($cur_opts as $app){
            echo $this->Form->hidden('group_options][]',array('value'=>$app));
        }
        ?>
        
        <div class="row-fluid">
            <div class="span4">
                <label>Group Name</label>
                <?php echo $this->Form->input('name', array('label'=>false, 'data-placeholder' => 'Group Name')); ?> 
            </div>
            <div class="span4">
                <label>Supervisor:</label>  
                <?php echo $this->Form->input( 'supervisor_id', array('options'=>$supervisor, 'label'=>false, 'div'=>false, 'class'=>'chzn-select', 'selected'=>$selection_super,'empty' => ' ', 'data-placeholder'=>'Choose an Associate.....' ) );?>
            </div>
            
            <div class="span4">
                <label>Available Apps:</label>
                <?php echo $this->Form->input( 'group_opts', array(
                    'options'=>$group_ops, 
                    'label'=>false, 
                    'div'=>false, 
                    'class'=>'chzn-select', 
                    'empty' => ' ',
                    'multiple'=>true, 
                    'data-placeholder'=>'Select Apps.....', 
                    'selected'=>$selection_opts
                ) );?>      
            </div>
        </div>
        
        <div class="submit">
            <?php echo $this->Form->button('Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary')); ?>
            <?php echo $this->Html->link( __('Cancel'), array('controller'=>'associates', 'action'=>'index' ), array('class'=>'btn')  ); ?>
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

    