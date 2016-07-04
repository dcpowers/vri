<?php 
    echo $this->Form->create('Account', array(
        'url'=>array('controller'=>'Accounts', 'action'=>'edit'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
                                
    echo $this->Form->hidden('id', array('value'=>$this->request->data['Account']['id'])); 
?>
<div class="modal-header modal-header-primary">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Edit Account: '); ?></h2>
</div>
<div class="modal-body">
    <div class="row" style="clear: both;">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Name:</label>
                <?php 
                echo $this->Form->input( 'name', array()); 
                ?>
            </div>
                                
            <div class="form-group">
                <label for="abr" class="control-label">Abbreviation:</label>
                <?php 
                echo $this->Form->input( 'abr', array()); 
                ?>
            </div>
                                
            <div class="form-group">
                <label for="address" class="control-label">Address:</label>
                <?php 
                echo $this->Form->input( 'address', array()); 
                ?>
            </div>
                                
            <div class="form-group">
                <label for="supervisor" class="control-label">Supervisor:</label>
                <?php 
                echo $this->Form->input( 'manager_id', array(
                    'options'=>$userList,
                    'class'=>'chzn-select form-control', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Supervisor.....',
                ));
                ?>
            </div>
                                
            <div class="form-group">
                <label for="manager" class="control-label">Systems Coordinator:</label>
                <?php 
                echo $this->Form->input('coordinator_id', array(
                    'options'=>$userList,
                    'class'=>'chzn-select form-control', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Systems Coordinator.....',
                ));
                ?>
            </div>
                                
            <div class="form-group">
                <label for="regional_admin" class="control-label">Regional Administrator:</label>
                <?php 
                echo $this->Form->input('regional_admin_id', array(
                    'options'=>$userList['Vanguard Resources'],
                    'class'=>'chzn-select form-control', 
                    'empty' => true,
                    'data-placeholder'=>'Select a Regional Administrator.....',
                ));
                ?>
            </div>
        </div>
                            
        <div class="col-sm-6">
            <div class="form-group">
                <label for="status" class="control-label">Account Status:</label>
                <?php 
                echo $this->Form->input('is_active', array(
                    'options'=>$status,
                    'value'=>$account['Status']['id'],
                    'class'=>'chzn-select form-control', 
                    'empty' => true,
                    'multiple'=>false,
                    'data-placeholder'=>'Select Account Status.....',
                ));
                ?>
            </div>
            
            <div class="form-group">
                <label for="SprocketDB" class="control-label">SprocketDB:</label>
                <?php 
                echo $this->Form->input('SprocketDB', array()); 
                ?>
            </div>
                                
            <div class="form-group">
                <label for="AllPayID" class="control-label">All Pay Id:</label>
                <?php 
                echo $this->Form->input('AllPayID', array()); 
                ?>
            </div>
            
            <div class="form-group">
                <label for="department" class="control-label">Current Department(s):</label>
                <?php 
                echo $this->Form->input('AccountDepartment.department_id', array(
                    'options'=>$departments,
                    'class'=>'chzn-select form-control', 
                    'empty' => true,
                    'multiple'=>true,
                    'data-placeholder'=>'Select Department(s).....',
                ));
                ?>
            </div>
                                
            <div class="form-group">
                <label for="AllPayID" class="control-label">Old Department(s):</label>
                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('EVS', array()); 
                        ?>
                        EVS
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('CE', array()); 
                        ?>
                        CE
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('Food', array()); 
                        ?>
                        Food
                    </label>
                </div>
                                        
                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('POM', array()); 
                        ?>
                        POM
                    </label>
                </div>
                                        
                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('LAU', array()); 
                        ?>
                        LAU
                    </label>
                </div>
                                        
                <div class="checkbox">
                    <label>
                        <?php 
                        echo $this->Form->checkbox('SEC', array()); 
                        ?>
                        SEC
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    <?php echo $this->Form->button('Save', array('type'=>'submit', 'class'=>'btn btn-primary pull-left')); ?>
</div>

<?php echo $this->Form->end(); ?>
    
<script type="text/javascript">
    jQuery(document).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>
