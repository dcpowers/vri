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
	foreach($results as $title=>$items){
		?>
		<div class="hr-divider">
        	<h3 class="hr-divider-content hr-divider-heading">
				<?=$title?>
            </h3>
        </div>
		<table class="table table-striped" id="accountsTable">
	    	<thead>
	        	<tr class="tr-heading">
	            	<th class="col-md-2">Employee</th>
	                <th class="col-md-2">Date</th>
	                <th class="col-md-2">Verified Date</th>
	                <th class="col-md-2">Amount</th>
	                <th class="col-md-2">Type</th>
					<th class="col-md-2">Verified By</th>
				</tr>
	        </thead>
            <tbody>
				<?php
				foreach($items as $v){
					if(isset($v['award']['error'])){
						?>
						<tr><td colspan="6" class="danger"><?=$v['award']['error']?></td></tr>
						<?php
						break;
					}
            		?>

					<tr>
						<td><?=$v['award']['user']?></td>
						<td><?=$v['award']['monthYear']?></td>
						<td><?=$v['award']['ver_date']?></td>
						<td><?=$v['award']['amount']?></td>
						<td><?=$v['award']['type']?></td>
						<td><?=$v['award']['ver_by']?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	}

	?>

</div>


<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
    });
</script>