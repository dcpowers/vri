<?php
    $data = $this->requestAction('/Accidents/getDashboard/');
    #pr($data);
    if(!empty($data)){
		?>
		<div class="box box-success">
            <div class="box-header">
        		<h3 class="box-title">Accident Reports</h3>
			</div>
			<div class="box-body">
				<ul class="list-unstyled">
        			<?php
					foreach($data as $v){
						?>
						<li>
							<?php
							echo $this->Html->link(
	            				$v['AccidentFile']['name'] .': '. $v['Accident']['first_name'] .' '. $v['Accident']['last_name'],
	                			array('controller'=>'AccidentStatements', 'action'=>'index', $v['AccidentFile']['statement_id'], $v['AccidentFile']['accident_id']),
	                			array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
							);
							?>
						</li>
						<?php
					}
				    ?>
				</ul>
			</div>
		    <div class="box-footer" style="border-bottom: 1px solid #C0C0C0;"></div>
		</div>
		<?php
	}
?>

