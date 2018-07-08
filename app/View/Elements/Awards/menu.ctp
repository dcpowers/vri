<?php
$awards = $this->requestAction('/Awards/getMenu/'.$id);

foreach($awards as $title=>$a){
	?>
	<div class="hr-divider">
        <h3 class="hr-divider-content hr-divider-heading">
            <?=$title?>
        </h3>
    </div>
	<table class="table table-striped" id="accountsTable">
   		<thead>
   			<tr class="tr-heading">
	   			<th class="col-md-4">Month</th>
	   			<th class="col-md-3 text-center">Verified</th>
	   			<th class="col-md-3 text-center">Paid</th>
	   			<th class="col-md-3 text-center">Print</th>
			</tr>
	    </thead>

    	<tbody>
    		<?php
			foreach($a as $month=>$v){
				#pr($v);
				$m = date('m', strtotime($month));
				?>
				<tr>
					<td>
						<?php
						echo $this->Html->link(
							$month,
			                '#',
			                array('escape'=>false, 'id'=>''.$title.'-'.$m.'-'.$id.'', 'class'=>'monthly')
			            );
						?>	
					</td>
					<td class="text-center"><?=$v['verified']?></td>
					<td class="text-center"><?=$v['paid']?></td>
					<td class="text-center">
						<?php
						echo $this->Html->link(
							'<i class="fa fa-print fa-fw"></i>',
						    array('controller'=>'Awards', 'action'=>'index', 'ext'=>'pdf', $title.'-'.$m.'-'.$id  ),
						    array('escape'=>false)
						);
						?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}
?>