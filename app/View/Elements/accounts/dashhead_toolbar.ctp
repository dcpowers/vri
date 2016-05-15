    <!--<div class="dashhead-toolbar-item">
        <div class="btn-group">
            <?php 
            echo $this->Html->link(
                'Create New Mount <span class="caret"></span>', 
                '#',
                array('class'=>'btn btn-primary dropdown-toggle','escape'=>false, 'data-toggle'=>'dropdown', 'aria-haspopup'=>'true', 'aria-expanded'=>'false' ) 
            );
            ?>
            <ul class="dropdown-menu">
                <li>
                    <?php 
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Create Mount', 
                        array('plugin'=>'mounts', 'controller'=>'mounts', 'action'=>'add' ),
                        array('escape'=>false ) 
                    );
                    ?>
                </li>
                
                <li>
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Create Direct Mount', 
                        array('plugin'=>'mounts', 'controller'=>'mounts', 'action'=>'adddm' ), 
                        array('escape'=>false ) 
                    );
                    ?>
                </li>
                
                <li>
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Create Non Interest Mount', 
                        array('plugin'=>'mounts', 'controller'=>'mounts', 'action'=>'addni' ), 
                        array('escape'=>false ) 
                    );
                    ?>
                </li>
                
                <li>
                    <?php    
                    echo $this->Html->link(
                        '<i class="fa fa-plus"></i> Create Show Mount', 
                        array('plugin'=>'mounts', 'controller'=>'mounts', 'action'=>'addsm' ),
                        array('escape'=>false ) 
                    );
                    ?>
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <span class="dashhead-toolbar-divider hidden-xs"></span>-->
    <div class="dashhead-toolbar-item">
        <?php 
        echo $this->Form->create('Mount', array(
            'url' => array( 'plugin' => 'mounts', 'controller' => 'mounts', 'action'=>'search'),
            'role'=>'form',
            'class'=>'form-inline',
            'inputDefaults' => array(
                'label'=>false,
                'div'=>false,
                'class'=>'form-control',
                'error'=>false
            )
        ));
        
        echo $this->Form->hidden('v', array( 'value' => 'lcomplete' )); 
        echo $this->Form->hidden('s', array( 'value' => 'COMPLETE' )); 
        ?>
        <div class="form-group">
            <div class="input-group">
                <?php
                echo $this->Form->input( 'Search.q', array(
                    'placeholder'=>'TestCode,TestRequest,etc..',
                ));
                ?>
                <div class="input-group-btn">
                    <?php 
                    echo $this->Form->input('<i class="fa fa-search"></i>', array(
                        'type'=>'button', 
                        'class'=>'btn btn-info'
                    ));
                    ?>
                </div>
            </div> 
        </div>
    
        <?php echo $this->Form->end(); ?>    
    </div>
    <!--<span class="dashhead-toolbar-divider hidden-xs"></span>-->

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".chzn-select").chosen({ width: '100%' });
        });
    </script>