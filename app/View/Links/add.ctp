    <?php
    echo $this->Form->create('Link', array(
        'url'=>array('controller'=>'Links', 'action'=>'add'),
        #'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

    ?>
<div class="modal-header modal-header-warning bg-teal">
	<a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
  	<h2><?php echo __('New Quick Link:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
    	<label for="name" class="control-label">name:</label>
        <?php
        echo $this->Form->input('name', array(
        	'type'=>'text',
        ));
        ?>
    </div>

    <div class="form-group">
    	<label for="address" class="control-label">URL:</label>
        <?php
        echo $this->Form->input( 'url', array(
        	'type'=>'text',
        ));
        ?>
    </div>
	<div class="row">
		<div class="col-md-6">
            <div class="form-group">
                <label for="abr" class="control-label">Status:</label>
                <?php
                echo $this->Form->input('is_active', array(
                    'options'=>$status,
                    'class'=>'form-control chzn-select',
                    'empty' => false,
                    'data-placeholder'=>'Select a Status.....',
                ));
                ?>
            </div>
        </div>

		<div class="col-md-6">
            <div class="form-group">
                <label for="abr" class="control-label">Permission Level:</label>
                <?php
				$roles[0] = 'Everyone';

                echo $this->Form->input('permission_level', array(
                    'options'=>$roles,
                    'class'=>'form-control chzn-select',
                    'empty' => false,
					'data-placeholder'=>'Select a Permission Level.....',
                ));
                ?>
            </div>
        </div>
	</div>
</div>

<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

    echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    );

    ?>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    jQuery(document).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: false,
        });
    });
</script>