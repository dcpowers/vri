<?php
    
?>

<div class="box box-warning">
    <div class="box-header">
        <h3 class="box-title">My Training</h3>
        <div class="box-tools pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> <span>Add</span>',
                array('controller'=>'Improvements', 'action'=>'add'),
                array('escape'=>false)
            );
            
            if(AuthComponent::user('Role.permission_level') >= 60){
                echo $this->Html->link(
                    '<i class="fa fa-wrench fa-fw"></i> <span>Manage</span>',
                    array('controller'=>'Improvements', 'action'=>'index'),
                    array('escape'=>false)
                );
                
            }
            ?>
        </div>
    </div>
    <div class="box-body">
        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <?php
                    echo $this->Html->link(
                        'Expiring/Expired <span class="badge bg-orange">0</span>',
                        '#accept',
                        array('escape'=>false, 'aria-controls'=>'expiring', 'role'=>'tab', 'data-toggle'=>'tab')
                    );
                    ?>
                </li>
                
                <li role="presentation">
                    <?php
                    echo $this->Html->link(
                        'Current Training <span class="badge bg-orange">0</span>',
                        '#completed',
                        array('escape'=>false, 'aria-controls'=>'current', 'role'=>'tab', 'data-toggle'=>'tab')
                    );
                    ?>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="expiring">
                    
                </div>
                
                <div role="tabpanel" class="tab-pane fade" id="completed">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>