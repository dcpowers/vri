    <div class="dashhead-toolbar-item item">
        <?php
        switch($this->request->params['action']){
            case 'library':
                echo $this->Form->create('Training', array(
                    'url' => array('plugin'=>false, 'controller' => 'Trainings', 'action'=>'library', false),
                    'role'=>'form',
                    'class'=>'form-inline',
                    'inputDefaults' => array(
                        'label'=>false,
                        'div'=>false,
                        'class'=>'form-control',
                        'error'=>false
                    )
                ));
                break;
        
            default:
                echo $this->Form->create('Training', array(
                    'url' => array('plugin'=>false, 'controller' => 'Trainings', 'action'=>'index', false),
                    'role'=>'form',
                    'class'=>'form-inline',
                    'inputDefaults' => array(
                        'label'=>false,
                        'div'=>false,
                        'class'=>'form-control',
                        'error'=>false
                    )
                ));
            break;
        }
        ?>
        <div class="form-group">
            <div class="input-group">
                <?php
                echo $this->Form->input( 'Search.q', array(
                    'placeholder'=>'Enter Training...',
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