<div class="groups membership form">
    <h2><?php echo __('Edit Department Membership'); ?></h2>
    
    <div class="alert alert-error">
        <strong>Depreciated:</strong> This page no longer works, to add an associate to a group 
        you must edit the <?php echo $this->Html->link('group',array('controller'=>'groups','action'=>'index'));?>
        or the <?php echo $this->Html->link('associate',array('controller'=>'associates','action'=>'index'));?>.
    </div>
    <?php echo $this->Form->create('Associate'); ?>
        <?php foreach( $this->request->data as $key=>$group ) { ?>
            <fieldset>
                <legend><?php echo $group['Group']['name'];?></legend>
                <?php //echo $this->Form->input($key.'.id', array('type'=>'hidden', 'value'=>$group['Group']['id']));?>
                <?php /* echo $this->Form->input(
                    $key.'.supervisor_id',
                    array( 'selected'=>$group['Group']['supervisor_id'])
                ); */?>
                <table class="table table-striped table-condensed">
                <tbody>
                <?php $keyCounter = 0;?>
                <?php foreach( $group['Associate'] as $associateKey=>$associate ) { ?>
                    <tr>
                        <td>
                            <?php echo $this->Form->input( $keyCounter.'.id', array('type'=>'hidden', 'value'=>$associate['id'] ) );?>
                            <?php echo $associate['userid'];?>
                        </td>
                        <td>
                            <?php echo $associate['first_name'];?>
                            <?php echo $associate['last_name'];?>
                        </td>
                        <td>
                            <?php echo $this->Form->input( 
                                $keyCounter.'.group_id',
                                array('selected'=>$associate['group_id'], 'div'=>false, 'label'=>false, 'disabled'=>'disabled', 'style'=>'display:none')
                            );?>
                            <?php echo $this->Html->link('Change Department', '#', array('class'=>'btn btn-small btn-change-dept') );?>
                        </td>
                    </tr>
                    <?php $keyCounter++;?>
                <?php } ?>
                </tbody>
                </table>
            </fieldset>
        <?php }?>
        <fieldset>
            <legend>Associates with no Department</legend>
                <table class="table table-striped table-condensed">
                <tbody>
                <?php foreach( $orphanAssociates as $associateKey=>$associate ) { ?>
                    <tr>
                        <td>
                            <?php echo $this->Form->input( $keyCounter.'.id', array('type'=>'hidden', 'value'=>$associate['Associate']['id'] )  );?>
                            <?php echo $associate['Associate']['userid'];?>
                        </td>
                        <td>
                            <?php echo $associate['Associate']['first_name'];?>
                            <?php echo $associate['Associate']['last_name'];?>
                        </td>
                        <td>
                            <?php echo $this->Form->input( 
                                $keyCounter.'.group_id',
                                array('selected'=>$associate['Associate']['group_id'], 'div'=>false, 'label'=>false, 'style'=>'display:none')
                            );?>
                            <?php echo $this->Html->link('Change Department', '#', array('class'=>'btn btn-small btn-change-dept') );?>
                        </td>
                    </tr>
                    <?php $keyCounter++;?>
                <?php } ?>
                </tbody>
                </table>
        </fieldset>
        <div class="submit">
            <?php echo $this->Form->submit('Submit', array('div'=>false,'class'=>'btn btn-primary') ); ?>
            <?php echo $this->Html->link( __('Cancel'), array('action'=>'index' ), array('class'=>'btn') );?>
        </div>
    <?php echo $this->Form->end();?>
</div>
<script type=text/javascript>
jQuery(document).ready(function($) {
    $('.btn-change-dept').on('click', function() {
        $(this).prev().show();
        $(this).prev().removeAttr('disabled');
        $(this).hide();
        return false;
    });   
    
});
</script>