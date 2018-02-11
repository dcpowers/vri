<?php
    #pr($accidents);
    #exit;
?>

<div class="account index bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Awards: <?=$months[$month]?> <?=$years[$year]?></h6>
            <h3 class="dashhead-title"><i class="fa fa-trophy fa-fw"></i>Awards</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php
			if(AuthComponent::user('Role.permission_level') >= 60){
				echo $this->Html->link(
                	'Admin Report',
                    array('controller'=>'Awards', 'action'=>'report'),
                    array('escape'=>false, 'class'=>'btn btn-primary' )
                );
			}
			?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php #echo $this->element( 'Awards/menu', ['month'=>$month, 'year'=>$year] );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Awards/status_filter', ['month'=>$month, 'year'=>$year, 'months'=>$months, 'years'=>$years] );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>
	<?php
	if($editable == 1){
		echo $this->Form->create('Awards', array(
	    	'url'=>array('controller'=>'Awards', 'action'=>'process'),
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
	}
	?>
	<table class="table table-striped" id="accountsTable">
	   	<thead>
	   		<tr class="tr-heading">
	   			<th></th>
	  			<th class="col-md-2">Employee</th>
	   			<th class="col-md-2">Amount</th>
	   			<th class="col-md-2">Paid Date</th>
				<th class="col-md-2">Verified Date</th>
				<th class="col-md-2">Verified By</th>
				<th class="col-md-2">Type</th>
			</tr>
	    </thead>

	    <tbody>
			<?php
			$c = 0;
			foreach($results as $title=>$v){
				$amount = $this->Number->currency($v['User']['award_amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));
				
				if($editable == 1){
					echo $this->Form->hidden($c.'.verified_by', array('value'=>AuthComponent::user('id')));
					echo $this->Form->hidden($c.'.verified_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
	            	echo $this->Form->hidden($c.'.date', array('value'=>$end));
					echo $this->Form->hidden($c.'.account_id', array('value'=>$v['User']['account_id']));
					echo $this->Form->hidden($c.'.department_id', array('value'=>$v['User']['dept_id']));
					echo $this->Form->hidden($c.'.award_type_id', array('value'=>1));
					echo $this->Form->hidden($c.'.user_id', array('value'=>$v['User']['id']));
					echo $this->Form->hidden($c.'.amount', array('value'=>$v['User']['award_amount']));
				}
					
				?>
				<tr>
					<td>
						<?php
						if($editable == 1 && $v['User']['is_verified'] == 0){
							?>
							<div class="form-group" >
	                        	<label class="sr-only control-label">Eligible Bingo:</label>
	                            <div class="checkbox">
				                	<label> <?php echo $this->Form->checkbox($c.'.verify', array('checked'=>true)); ?></label>
								</div>
	                        </div>
	                        <?php
	                    }
	                    ?>
					</td>
					<td><?=$v['User']['first_name']?> <?=$v['User']['last_name']?></td>
					<td><?=$amount?></td>
					<td>
						<div id="<?=$v['User']['award_id']?>">
							<?php
							if(!empty($v['User']['paid_date'])) {
								echo $v['User']['paid_date'];
							} else {
								if($v['User']['is_verified'] == 1){
									echo $this->Html->link(
										'Paid',
										'#',
										array('escape'=>false, 'id'=>$v['User']['award_id'], 'class'=>'link')
									);
								}
							}
							?>
						</div>
					</td>
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
	    echo $this->Form->button('Verify', array(
	    	'type'=>'submit',
	        'class'=>'btn btn-primary pull-left'
	    ));
	    
	    echo $this->Form->end(); 
	}
	?>
		
</div>

	
<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'paid'));
?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
        	allow_single_deselect: false,
			width: '100%',
			disable_search: true
		});

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