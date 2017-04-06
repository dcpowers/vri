<div class="groups supervisors form">
    <h3><?php echo __('Edit Department Supervisors'); ?></h3>
    <?php echo $this->Form->create('Group'); ?>
        <fieldset>
            <?php //pr( $this->request->data ); ?>
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Supervisor</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach( $this->request->data as $key=>$group ) { ?>
                <tr>
                <td>
                    <?php echo $group['Group']['name'];?>
                    <?php echo $this->Form->input($key.'.id', array('type'=>'hidden', 'value'=>$group['Group']['id']));?>
                </td>
                <td><?php echo $this->Form->input(
                    $key.'.supervisor_id',
                    array('label'=>false, 'div'=>false, 'selected'=>$group['Group']['supervisor_id'])
                );?></td>
                </tr>
            <?php }?>
            </tbody>
            </table>
        </fieldset>
        <div class="submit">
            <?php echo $this->Form->submit('Submit', array('div'=>false,'class'=>'btn btn-primary') ); ?>
            <?php echo $this->Html->link( __('Cancel'), array('action'=>'index' ), array('class'=>'btn') );?>
        </div>
    <?php echo $this->Form->end();?>
</div>