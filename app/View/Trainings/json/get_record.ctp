<table class="table table-striped table-condensed" id="assetsTable">
	<thead>
		<tr class="tr-heading">
			<th class="col-md-6">Training</th>
			<th>Status</th>
			<th>Expires Date</th>
			<th class="text-center">Required</th>
			<th class="text-center">Actions</th>
		</tr>
	</thead>

	<tbody>
		<?php
        #pr($records);

		foreach($records as $t){
			#pr($records[$training['Training']['id']]);
			$status = null;
            #pr($records[$training['Training']['id']]);
            $status = 'Current';
            $label = 'label label-success';
			$showRest = 1;

            if($t['TrainingRecord']['in_progress'] == 1){
                $status = 'In Progress';
                $label = 'label label-primary';
			}

            if($t['TrainingRecord']['expired'] == 1){
                $status = 'Expired';
                $label = 'label label-danger';
			}

            if($t['TrainingRecord']['expiring'] == 1){
                $status = 'Expiring';
                $label = 'label label-warning';
			}

            if($t['TrainingRecord']['no_record'] == 1){
                $status = 'No Record Found';
                $label = 'label label-danger';
				$showRest = 0;
            }

            $expires = (!empty($t['TrainingRecord']['expires_on'])) ? date('F d, Y', strtotime($t['TrainingRecord']['expires_on'])) : '--' ;
            $required = ($t['TrainingRecord']['is_required'] == 1) ? '<i class="fa fa-check-circle-o text-success fa-2x" aria-hidden="true"></i>' : '<i class="fa fa-times-circle-o text-danger fa-2x" aria-hidden="true"></i>' ;
            ?>
			<tr>
				<td>
			    	<?php
			        echo $this->Html->link(
			        	$t['TrainingRecord']['name'],
			            '#',
			            array('escape'=>false)
			        );
			        ?>
			    </td>

				<td><span class="<?=$label?>"><?=$status?></span></td>

				<td><?=$expires?></td>

				<td class="text-center"><?=$required?></td>

			</tr>
			<?php
		}

		if(empty($records)){
			?>
			<tr>
				<td colspan="5" class="text-center">No Records Found</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php
$trnRecord = $this->Html->url(array('plugin'=>false, 'controller'=>'Trainings', 'action' => 'deleteRecord'));
?>
<script type="text/javascript">
	jQuery(document).ready( function($) {
    	$('.trnRecord').on('click', function () {
			var Id = $(this).attr("data-value");
			var div = $(this).parents('div:eq(0)').attr('id');

            $.ajax({
            	type: 'post',
                url: '<?=$trnRecord?>/' + Id + '/' + div + '/<?=$user_id?>' + '.json',
                dataType: "html",
                beforeSend: function(xhr) {
                	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                },
                success: function(response) {
                	console.log(response);
                    $('#' + div).html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                	console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function(){
                	$('#overlay').remove();
                },
            });
		});
    });
</script>