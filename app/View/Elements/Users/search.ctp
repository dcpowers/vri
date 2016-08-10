    <div class="dashhead-toolbar-item item">
        <?php 
        echo $this->Form->create('User', array(
            'url' => array('plugin'=>false, 'controller' => 'Users', 'action'=>'index', false),
            'role'=>'form',
            'class'=>'form-inline',
            'inputDefaults' => array(
                'label'=>false,
                'div'=>false,
                'class'=>'form-control',
                'error'=>false
            )
        ));
            
        ?>
        <div class="form-group">
            <div class="input-group">
                <?php
                echo $this->Form->input( 'Search.q', array(
                    'placeholder'=>'Enter Name...',
                ));
                ?>
                <div class="input-group-btn">
                    <?php 
                    echo $this->Form->input('<i class="fa fa-search"></i>', array(
                        'type'=>'button', 
                        'class'=>'btn btn-primary'
                    ));
                    ?>
                </div>
            </div> 
        </div>
        <?php echo $this->Form->end(); ?>    
    </div>