<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Award Report: <small>'.$month.', '.$year.'</small>'); ?></h2>
</div>

<div class="modal-body">
    <table class="table table-striped table-hover" id="accountsTable">
		<thead>
	    	<tr class="tr-heading">
	   			<th class="col-md-3">Employee</th>
	   			<th class="col-md-1">Amount</th>
	   			<th class="col-md-2">Paid Date</th>
				<th class="col-md-2">Verified Date</th>
				<th class="col-md-2">Verified By</th>
				<th class="col-md-2">Type</th>
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
					<td><?php echo $this->Number->currency($r['Award']['amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));?></td>
					<td>
						<div id="<?=$r['Award']['id']?>">
							<?php
							if(!empty($r['Award']['paid_date'])) {
								echo date('M d, Y', strtotime($r['Award']['paid_date']));
							} else {
								echo $this->Html->link(
									'Paid',
									'#',
									array('escape'=>false, 'id'=>$r['Award']['id'], 'class'=>'link')
								);
							}
							?>
						</div>
					</td>
					<td><?php echo date('M d, Y', strtotime($r['Award']['verified_date']));?></td>
					<td><?=$r['CreatedBy']['first_name']?> <?=$r['CreatedBy']['last_name']?></td>
					<td><?=$r['Type']['award']?></td>
					
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
<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'paid'));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $('.link').on('click', function () {
			var id = $(this).attr("id");
			$.ajax({
				type: 'POST',
                url:'<?=$url?>/' + id + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#' + id).empty();
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                success: function(response) {
                    $('#' + id).html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }

            });

            return false;

        });
    });
</script>