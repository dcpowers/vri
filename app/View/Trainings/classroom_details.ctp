<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Classroom Details: <small>'. $data['Classroom']['name'].'</small>'); ?></h2>
</div>

<div class="modal-body">
	<h3>Instructor: <small><?=$data['Trainer']?></small></h3>
	<h4>Date: <small><?php echo date('F d, Y', strtotime($data['Classroom']['date'])); ?></small></h4>
	<table class="table table-striped table-condensed" id="trainingTable">
    	<thead>
        	<tr>
            	<th class="col-sm-6">User</th>
                <th class="col-sm-6 text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
        	<?php
            foreach($data['User'] as $v){
				?>
                <tr>
                	<td><?=$v['first_name']?> <?=$v['last_name']?></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close Window',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
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