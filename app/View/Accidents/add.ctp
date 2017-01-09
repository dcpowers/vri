<div class="account index bg-white">
    <div class="dashhead">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">New Accident:</h6>
            <h3 class="dashhead-title"><i class="fa fa-home fa-fw"></i> Accidents</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Accounts/menu' );?>
        </div>
    </div>
    <?php
    echo $this->Form->create('Accident', array(
        'url'=>array('controller'=>'Accidents', 'action'=>'edit'),
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
    <div class="box box-info" style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
        <div class="box-header with-border">
            <h3 class="box-title"><?=$this->request->data['Account']['name']?></h3>
        </div>
        <div class="box-body">
            <div class="row">
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
                            'class'=>'chzn-select-status form-control',
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
        <div class="box-footer">
            <?php
            echo $this->Html->link(
                'Cancel',
                array('controller'=>'Accounts', 'action'=>'view', $this->request->data['Account']['id']),
                array('escape'=>false, 'class'=>'btn btn-default')
            );
            ?>

            <?php
            echo $this->Form->button('Save', array(
                'type'=>'submit',
                'class'=>'btn btn-primary'
            ));
            ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
    jQuery(document).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $(".chzn-select-status").chosen({
            allow_single_deselect: false
        });
    });
</script>