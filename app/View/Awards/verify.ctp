<?php
    #pr($accidents);
    #exit;
?>
<style type="text/css">
	.hr-divider:before{
        background-color: #00A65A;
	}
</style>
<div class="account index bg-white">
    <div class="dashhead" style="border-bottom: 2px solid #00A65A;">
        <div class="dashhead-titles">
            <h6 class="dashhead-subtitle">List Of Awards: <?=$months[$month]?> <?=$years[$year]?></h6>
            <h3 class="dashhead-title"><i class="fa fa-trophy fa-fw"></i>Awards</h3>
        </div>
        <div class="dashhead-toolbar">
            <?php #echo $this->element( 'Accounts/search' );?>
        </div>
    </div>
    <div class="flextable">
        <div class="flextable-item">
            <?php echo $this->element( 'Awards/menu' );?>
        </div>
        <div class="flextable-item">
			<?php echo $this->element( 'Awards/status_filter', ['month'=>$month, 'year'=>$year, 'months'=>$months, 'years'=>$years] );?>
            <?php #echo $this->element( 'Accidents/search_filter', ['in'=>$in, 'var'=>$var, 'viewBy'=>$viewBy] );?>
        </div>
    </div>

	<?php
    foreach($results as $title=>$v){
		$count = count($v['Awards']);
		$in = null;
		if($count >= 1){
			if($v['User']['is_paid'] == 1){
				$labelType = "label-success";
			}else if($v['User']['is_verified'] == 1){
				$labelType = "label-warning";
				$in = 'in';
			}else{
				$labelType = "label-danger";
				$in = 'in';
			}
		}else{
			$labelType = "label-default";
		}
        ?>
		<div class="panel panel-default">
  			<div class="panel-heading" id="heading<?=$v['User']['id']?>">
                <h4 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$v['User']['id']?>" aria-expanded="false" aria-controls="<?=$v['User']['id']?>">
						<div class="pull-right">
							<span class="label label-as-badge <?=$labelType?>"><?=$count?></span>
						</div>
						<i class="fa fa-user fa-fw"></i><?=$v['User']['first_name']?> <?=$v['User']['last_name']?> <i class="fa fa-caret-down fa-fw"></i>
					</a>
				</h4>
  			</div>
			<div id="<?=$v['User']['id']?>" class="panel-collapse collapse <?=$in?>" role="tabpanel" aria-labelledby="heading<?=$v['User']['id']?>">

				<table class="table table-striped" id="accountsTable">
	        		<thead>
	            		<tr class="tr-heading">
	                		<th class="">Date</th>
	                		<th class="col-md-2">Paid Date</th>
	                		<th class="col-md-2">Amount</th>
	                		<th class="col-md-2">Type</th>
							<th class="col-md-2">Verified By</th>
							<th class="col-md-2"></th>
						</tr>
	        		</thead>

	        		<tbody>
						<?php
						if(isset($v['Awards'])){
							foreach($v['Awards'] as $r){
								$paid = (!empty($r['Award']['paid_date'])) ? date('F d, Y', strtotime($r['Award']['paid_date'])) : null;
								$ver_by = (!empty($r['CreatedBy']['first_name'])) ? $r['CreatedBy']['first_name'].' '.$r['CreatedBy']['last_name'] : null;
								?>
	                			<tr>
									<td><?php echo date('F d, Y', strtotime($r['Award']['date'])); ?></td>
									<td><?=$paid?></td>
									<td><?php echo $this->Number->currency($r['Award']['amount']); ?></td>
									<td><?=$r['Type']['award']?></td>
									<td><?=$ver_by?></td>
	                    			<td>
										<ul class="list-inline">
											<li>
												<?php
												echo $this->Html->link(
						                    		'<i class="fa fa-fw fa-unlock"></i>',
							                        array('controller'=>'Accidents', 'action'=>'open', $r['Award']['id']),
							                        array('escape'=>false)
							                    );
												?>
											</li>
										</ul>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>

		</div>
		<?php
	}
	?>

</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
        	allow_single_deselect: false,
			width: '100%',
			disable_search: true
		});
     });
</script>