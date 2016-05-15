<div class="dashhead-toolbar-item">
    <?php 
    echo $this->Html->link(
        '<i class="fa fa-plus"></i> Add New Employee', 
        array('controller'=>'Users', 'action'=>'add' ),
        array('escape'=>false, 'class'=>'btn btn-primary') 
    );
    ?>
</div>

<span class="dashhead-toolbar-divider hidden-xs"></span>

<div class="dashhead-toolbar-item">
    <?php 
    echo $this->Form->create('User', array(
        'url' => array('controller' => 'Users', 'action'=>'search'),
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
                    'class'=>'btn btn-info'
                ));
                ?>
            </div>
        </div> 
    </div>
    <?php echo $this->Form->end(); ?>    
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".chzn-select").chosen({ width: '100%' });
    });
</script>