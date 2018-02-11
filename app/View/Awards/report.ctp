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
            <?php #echo $this->element( 'Awards/menu' );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Awards/status_filter', ['month'=>$month, 'year'=>$year, 'months'=>$months, 'years'=>$years] );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>

	<?php
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
    
    #echo $this->Form->hidden('button', array('value'=>''));
	#pr($results);
	#exit;
	$c = 0;
	foreach($results as $title=>$items) {
		$amounts = $this->Number->currency(array_sum(Hash::extract($items, '{n}.award_amount')), false, $options=array('before'=>'$', 'zero'=>'$0.00'));
		$ctitle = preg_replace('/\s+/', '', $title);
		$in = null;
		?>
		<div class="panel panel-default">
  			<div class="panel-heading" id="heading<?=$ctitle?>">
                <h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$ctitle?>" aria-expanded="false" aria-controls="<?=$ctitle?>">
						<div class="pull-right">
							<span> <?=$amounts?></span>
						</div>
						<?=$title?> <i class="fa fa-caret-down fa-fw"></i>
					</a>
				</h4>
  			</div>
			<div id="<?=$ctitle?>" class="panel-collapse collapse <?=$in?>" role="tabpanel" aria-labelledby="heading<?=$ctitle?>">
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
	        			
	  					foreach($items as $dept=>$v) {
	  						#pr($v);
	  						#exit;
	  						if(is_null($v['award_id'])){
	  							echo $this->Form->hidden($c.'.verified_by', array('value'=>AuthComponent::user('id')));
								echo $this->Form->hidden($c.'.verified_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
				            	echo $this->Form->hidden($c.'.date', array('value'=>$end));
								echo $this->Form->hidden($c.'.account_id', array('value'=>$v['acct_id']));
								echo $this->Form->hidden($c.'.department_id', array('value'=>$v['dept_id']));
								echo $this->Form->hidden($c.'.award_type_id', array('value'=>1));
								echo $this->Form->hidden($c.'.user_id', array('value'=>$v['id']));
								echo $this->Form->hidden($c.'.amount', array('value'=>$v['award_amount']));
							} else {
								echo $this->Form->hidden($c.'.id', array('value'=>$v['award_id']));
							}
							
							if($v['is_approved'] == 0){
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
								<td><?=$v['first_name']?> <?=$v['last_name']?></td>
								<td><?=$v['award_amount']?></td>
								<td><?=$v['paid_date']?></td>
								<td><?=$v['approved_date']?></td>
								<td><?=$v['approved_by']?></td>
								<td><?=$v['verified_date']?></td>
								<td><?=$v['verified_by']?></td>
								<td><?=$v['award_type']?></td>
							</tr>
							<?php
							$c++;
						}
						?>
					</tbody>
				</table>	
			</div>
		</div>
		<?php
	}

	
	if($editable == 1){
	    echo $this->Form->button('Approve', array(
	    	'type'=>'submit',
	        'class'=>'btn btn-primary pull-left trigger',
	        'id'=>'approve'
	    ));
	    
	    echo $this->Form->end(); 
	}
	?>
	<div class="clearfix"></div>
</div>


<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
        
       
    });
</script>