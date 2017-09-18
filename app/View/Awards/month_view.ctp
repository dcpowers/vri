<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Award Report: <small>'.$month.', '.$year.'</small>'); ?></h2>
</div>

<div class="modal-body">
    <table class="table table-striped table-hover" id="accountsTable">
		<thead>
	    	<tr class="tr-heading">
                <th class="col-md-3">User</th>
				<th class="col-md-3">Date</th>
	            <th class="col-md-3">Type</th>
	            <th class="col-md-3">Amount</th>
	        </tr>
	    </thead>

	    <tbody>
			<?php
			foreach($awards as $r){
				#pr($r);
				#exit;
				$name = $r['User']['first_name'].' '.$r['User']['last_name'];

				?>
				<tr>
					<td><?=$name?></td>
					<td><?php echo date('F d, Y', strtotime($r['Award']['date']));?></td>
					<td><?=$r['Type']['award']?></td>
					<td><?php echo $this->Number->currency($r['Award']['amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));?></td>
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
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

    ?>
</div>
<?php echo $this->Form->end();?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
    });
</script>