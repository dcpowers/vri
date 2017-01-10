<?php
    $data = $this->requestAction('/Improvements/getDashboard/');

    $new = $data['new'];
    $newCount = count($new);

    $accepted = $data['accepted'];
    $acceptedCount = count($accepted);

    $completed = $data['completed'];
    $completedCount = count($completed);

    unset($data);
?>

<div class="box box-info" style="border-left: 1px solid #00C0EF; border-right: 1px solid #00C0EF;">
    <div class="box-header">
        <h3 class="box-title">Suggestions/Improvements</h3>
        <div class="box-tools pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> <span>Add</span>',
                array('controller'=>'Improvements', 'action'=>'add'),
                array('escape'=>false,'data-toggle'=>'modal', 'data-target'=>'#myModal', )
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
                        'Accepted <span class="badge bg-blue">'. $acceptedCount .'</span>',
                        '#accept',
                        array('escape'=>false, 'aria-controls'=>'accept', 'role'=>'tab', 'data-toggle'=>'tab')
                    );
                    ?>
                </li>

                <li role="presentation">
                    <?php
                    echo $this->Html->link(
                        'Completed <span class="badge bg-blue">'. $completedCount .'</span>',
                        '#complete',
                        array('escape'=>false, 'aria-controls'=>'complete', 'role'=>'tab', 'data-toggle'=>'tab')
                    );
                    ?>
                </li>

                <li role="presentation">
                    <?php
                    echo $this->Html->link(
                        'New <span class="badge bg-blue">'. $newCount .'</span>',
                        '#new',
                        array('escape'=>false, 'aria-controls'=>'new', 'role'=>'tab', 'data-toggle'=>'tab')
                    );
                    ?>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="accept">
                    <ol style="margin-top: 15px;">
                        <?php
                        foreach($accepted as $idea){
                            ?>
                            <li><?=$idea['Improvement']['idea']?></li>
                            <?php
                        }
                        ?>
                    </ol>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="complete">
                    <ul style="margin-top: 15px;">
                        <?php
                        foreach($completed as $val){
                            ?>
                            <li><?=$val['Improvement']['idea']?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="new">
                    <ul style="margin-top: 15px;">
                        <?php
                        foreach($new as $idea){
                            ?>
                            <li><?=$idea['Improvement']['idea']?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer" style="border-bottom: 1px solid #00A65A;"></div>
</div>

