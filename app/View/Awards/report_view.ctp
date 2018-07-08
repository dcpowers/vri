
		    <h2><?=$acct_name?>: <small><?=$monthName?> <?=$year?></small></h2>
		    <?php
			echo $this->Form->create('Awards', array(
		    	'url'=>array('controller'=>'Awards', 'action'=>'processReport', $year.'-'.$month.'-'.$id),
		        #'class'=>'form-horizontal',
		        'role'=>'form',
		        'inputDefaults'=>array(
		        	'label' => false,
		            'div' => false,
		            #'between' => '<div class="input-group">',
		            'class'=>'form-control',
		            #'after' => '</div>',
		            'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-block'))
		        )
		    ));
		    
		    ?>
			<table class="table table-striped" id="accountsTable">
			    <thead>
			       	<tr class="tr-heading">
			       		<th></th>
			           	<th class="col-md-2">Employee</th>
			           	<th class="col-md-1">Amount</th>
			            <th class="col-md-1">Paid Date</th>
			            <th class="col-md-1">Approved Date</th>
			            <th class="col-md-1">Approved By</th>
			            <th class="col-md-1">Verified Date</th>
			            <th class="col-md-2">Verified By</th>
			            <th class="col-md-2">Type</th>
						
					</tr>
			    </thead>
			    <tbody>
        			<?php
        			$c=0;
  					foreach($results as $dept=>$v) {
  						if(!is_null($v['User']['award_id'])){
  							echo $this->Form->hidden($c.'.id', array('value'=>$v['User']['award_id']));
						}
						
						if($v['User']['is_verified'] == 0){
							echo $this->Form->hidden($c.'.verified_by', array('value'=>AuthComponent::user('id')));
							echo $this->Form->hidden($c.'.verified_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
						}
						
						if($v['User']['is_approved'] == 0){
							echo $this->Form->hidden($c.'.approved_by', array('value'=>AuthComponent::user('id')));
							echo $this->Form->hidden($c.'.approved_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
						}
  						?>
  						<tr>
  							<td>
  								<div class="form-group" >
		                        	<label class="sr-only control-label">Approve:</label>
		                            <div class="checkbox">
					                	<label> <?php echo $this->Form->checkbox($c.'.verify', array('checked'=>true)); ?></label>
									</div>
		                        </div>
  							</td>
							<td><?=$v['User']['first_name']?> <?=$v['User']['last_name']?></td>
							<td><?=$v['User']['award_amount']?></td>
							<td><?=$v['User']['paid_date']?></td>
							<td><?=$v['User']['approved_date']?></td>
							<td><?=$v['User']['approved_by']?></td>
							<td><?=$v['User']['verified_date']?></td>
							<td><?=$v['User']['verified_by']?></td>
							<td><?=$v['User']['award_type']?></td>
						</tr>
						<?php
						$c++;
					}
					?>
				</tbody>
			</table>	
			<?php
			if($editable == 1){
			    echo $this->Form->button('Approve', array(
			    	'type'=>'submit',
			        'class'=>'btn btn-primary pull-left trigger',
			        'id'=>'approve'
			    ));
			    
			    echo $this->Form->end(); 
			}
			?>
			
<?php
    $monthlyUrl = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'reportView'));
?>
<script type="text/javascript">
    jQuery(document).ready( function($) {
        
        $('.monthly').on('click', function () {
			var id = $(this).attr("id");
			
			$.ajax({
				type: 'POST',
                url:'<?=$monthlyUrl?>/' + id + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#results').empty();
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                success: function(response) {
                    $('#results').html(response);
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