<div class="modal-header">
    <div class="bootstrap-dialog-header">
        <div class="bootstrap-dialog-close-button" style="display: block;">
            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
    </div>
</div>            <!-- /modal-header -->

<div class="modal-body">
    <div class="bootstrap-dialog-body">
        <div class="bootstrap-dialog-message">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-calendar fa-fw"></i><span class="text">Blind</span>',
                array('controller'=>'TestSchedules', 'action'=>'Blind', $test_id),
                array('escape' => false, 'data-dismiss'=>'modal', 'data-toggle'=>'modal', 'data-target'=>'#myModalSm', 'class'=>'btn btn-primary')
            );
            ?>

            <?php
            echo $this->Html->link(
                '<i class="fa fa-calendar fa-fw"></i><span class="text">Schedule</span>',
                array('controller'=>'TestSchedules', 'action'=>'Group', $test_id),
                array('escape' => false, 'data-dismiss'=>'modal', 'data-toggle'=>'modal', 'data-target'=>'#myModalBig', 'class'=>'btn btn-primary')
            );
            ?>
        </div>
    </div>
</div><!-- /modal-body -->

<script type="text/javascript">
    jQuery(window).ready( function($) {



    });
</script>