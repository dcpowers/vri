<?php
    $url = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'paid'));
    $monthlyUrl = $this->Html->url(array('plugin'=>false, 'controller'=>'Awards', 'action' => 'index'));
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
            <h6 class="dashhead-subtitle">List Of Awards:</h6>
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
			<?php #echo $this->element( 'Awards/status_filter', ['month'=>$month, 'year'=>$year, 'months'=>$months, 'years'=>$years] );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-3">
			<div class="well">
				<?php echo $this->element( 'Awards/menu', ['month'=>date('m', strtotime($monthName)), 'year'=>$year, 'id'=>$id] );?>
			</div>
		</div>
    	<div class="col-md-9">
    		<div id="LoadingDiv" style="display:none;">
                <?php echo $this->Html->image('ajax-loader-red.gif'); ?>
            </div>
    		<div id="monthlyData">
	    		<?php
				if($editable == 1){
					echo $this->Form->create('Awards', array(
				    	'url'=>array('controller'=>'Awards', 'action'=>'process', $year.'-'.$month.'-'.$id),
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
				<h2><?=$monthName?> <?=$year?></h2>
				<span><small><i class="fa fa-asterisk text-danger" style="font-size: 8px;"></i> Denotes An Inactive Employee</small></span>
				<hr style="border: 1px solid #00A65A;" />
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
							
							$showActive = ($v['User']['active'] == 2 || $v['User']['active'] == 0) ? '<i class="fa fa-asterisk text-danger" style="font-size: 8px;"></i>' : null ;	
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
								<td><?=$v['User']['first_name']?> <?=$v['User']['last_name']?> <?=$showActive?></td>
								<td><?=$amount?></td>
								<td>
									<div id="<?=$v['User']['award_id']?>">
										<?php
										if(!empty($v['User']['paid_date'])) {
											echo $v['User']['paid_date'];
										} else {
											if($v['User']['is_approved'] == 1){
												echo $this->Html->link(
													'Paid',
													'#',
													array('escape'=>false, 'id'=>$v['User']['award_id'], 'class'=>'link')
												);
											} else if($v['User']['is_verified'] == 1) {
												echo 'Waiting Approval';
											} else{
												echo null;
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
				
			</div>
		</div>
		
	</div>
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
<script type="text/javascript">
    jQuery(document).ready( function($) {
        
        $('.monthly').on('click', function () {
			var id = $(this).attr("id");
			
			$.ajax({
				type: 'POST',
                url:'<?=$monthlyUrl?>/index/' + id + '.json',
                cache: false,
                dataType: "html",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    $('#LoadingDiv').show();
                    $('#monthlyData').empty();
                },
                complete: function(){
                    $('#LoadingDiv').hide();
                },
                success: function(response) {
                    $('#monthlyData').html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }

            });

            return false;

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
                    $('#' + id).empty();
                },
                complete: function(){
                    
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
