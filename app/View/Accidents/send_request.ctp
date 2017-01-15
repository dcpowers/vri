<?php
    echo $this->Form->create('Accident', array(
    'url' => array('controller'=>'Accidents', 'action'=>'sendRequest', $accident_id, $statement_id),
    'role'=>'form',
    'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Request Statement:'); ?></h2>
</div>

<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="name">Employee:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('user_id', array (
                'options'=>$userList,
                'class'=>'form-select chzn-select',
				'multiple'=>true
            ));
            ?>
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
<?php echo $this->Form->end();?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });

        $('.datepicker').datetimepicker({
                'format': 'MM/DD/YYYY',
                'showTodayButton': true,
                'icons': {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    close: "fa fa-trash",

                }
            });
    });
</script>