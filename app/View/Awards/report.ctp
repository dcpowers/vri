<?php
    #pr($accidents);
    $i = 0;
    $items = array();
    
    foreach($results as $account => $v){
		$menu[$i]['acct'] = $account;
		$menu[$i]['count'] = count($v);
		$menu[$i]['amount'] = $this->Number->currency(array_sum(Hash::extract($v, '{n}.award_amount')), false, $options=array('before'=>'$', 'zero'=>'$0.00'));
		$menu[$i]['id'] = Hash::extract($v, '{n}.acct_id');
		$items = array_merge($items, $v);
		
		$i++;
	}
	#pr($items);
	#exit;
	#exit;
?>
<style type="text/css">
    #LoadingDiv{
        margin:0px 0px 0px 0px;
        position: relative;
        min-height: 100%;
        height: 100vh;
        z-index:9999;
        padding-top: 200px;
        padding-left: 45%;
        width: 100%;
        clear:none;
        background-color: #fff;
  		opacity: 0.5;
        /*background:url(/img/transbg.png);
        background-color:#666666;
        border:1px solid #000000;*/
    }
</style>
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
	
	<div class="row">
		<div class="col-md-3">
			<div class="well">
			<table class="table table-striped" id="accountsTable">
				<thead>
					<tr>
						<th>Account</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($menu as $b){
						?>
						<tr>
							<td>
								<?php
								echo $this->Html->link(
									$b['acct'].' ( '. $b['count']. ' )',
			                		'#',
			                		array('escape'=>false, 'id'=>''.$year.'-'.$month.'-'.$b['id'][0].'', 'class'=>'monthly')
			            		);
			            		?>
			            	</td>
							<td><?=$b['amount']?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			</div>
		</div>
		<div class="col-md-9">
			<div id="LoadingDiv" style="display:none;">
                <?php echo $this->Html->image('ajax-loader-red.gif'); ?>
            </div>
			<div id="results">
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
		    $c=0;
		    ?>
		    <h2>All Users</h2>
			<table class="table table-striped" id="accountsTable">
			    <thead>
			       	<tr class="tr-heading">
			       		<th></th>
			           	<th>Employee</th>
			           	<th>Acct</th>
			           	<th>Dept</th>
			           	<th>Amount</th>
			            <th>Paid Date</th>
			            <th>Approved Date</th>
			            <th>Approved By</th>
			            <th>Verified Date</th>
			            <th>Verified By</th>
			            <th>Type</th>
						
					</tr>
			    </thead>
			    <tbody>
        			<?php
        			#pr($items);
        			#exit;
  					foreach($items as $dept=>$v) {
  						
  						if(!is_null($v['award_id'])){
  							echo $this->Form->hidden($c.'.id', array('value'=>$v['award_id']));
						} else {
							echo $this->Form->hidden($c.'.date', array('value'=>$end));
							echo $this->Form->hidden($c.'.account_id', array('value'=>$v['acct_id']));
							echo $this->Form->hidden($c.'.department_id', array('value'=>$v['dept_id']));
							echo $this->Form->hidden($c.'.award_type_id', array('value'=>1));
							echo $this->Form->hidden($c.'.user_id', array('value'=>$v['id']));
							echo $this->Form->hidden($c.'.amount', array('value'=>$v['award_amount']));	
						}
						
						if($v['is_verified'] == 0){
							echo $this->Form->hidden($c.'.verified_by', array('value'=>AuthComponent::user('id')));
							echo $this->Form->hidden($c.'.verified_date', array('value'=>date('Y-m-d h:i:s',strtotime('now'))));
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
							<td><?=$v['acct_name']?></td>
							<td><?=$v['dept_name']?></td>
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
			</div>
		</div>
	</div>
</div>
<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'paid'));
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