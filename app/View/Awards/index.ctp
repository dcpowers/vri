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
        ?>
		<div class="box box-default">
  			<div class="box-header">
    			<h3 class="box-title"><i class="fa fa-user fa-fw"></i><?=$v['User']['first_name']?> <?=$v['User']['last_name']?></h3>
  			</div>

				<table class="table table-striped" id="accountsTable">
	        		<thead>
	            		<tr class="tr-heading">
	                		<th class="text-center">Date</th>
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
								?>
	                			<tr>
									<td class="text-center"><?php echo date('F d, Y', strtotime($r['Award']['date'])); ?></td>
									<td class="text-center"><?php echo date('F d, Y', strtotime($r['Award']['paid_date'])); ?></td>
									<td class="text-center"><?php echo $this->Number->currency($r['Award']['amount']); ?></td>
									<td class="text-center"><?=$r['Type']['award']?></td>
									<td class="text-center"><?=$r['CreatedBy']['first_name']?> <?=$r['CreatedBy']['last_name']?></td>
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
            <div class="box-footer"></div>
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