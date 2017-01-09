<?php
    $data = $this->requestAction('/Accidents/getDashboard/');

    #pr($data);
?>

<div class="box box-success" style="border-left: 1px solid #00A65A; border-right: 1px solid #00A65A;">
    <div class="box-body">
		<ul class="list-unstyled">
        	<?php
			foreach($data as $v){
				?>
				<li>
					<?php
					echo $this->Html->link(
	            		$v['title'],
	                	array('controller'=>$v['controller'], 'action'=>$v['action']),
	                	array('escape'=>false)
					);
					?>
				</li>
				<?php
			}
		    ?>
		</ul>
	</div>
    <div class="box-footer" style="border-bottom: 1px solid #00A65A;"></div>
</div>

