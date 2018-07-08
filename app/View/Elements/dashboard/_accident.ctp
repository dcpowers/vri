<?php
    $data = $this->requestAction('/Accidents/getDashboard/');
    #pr($data);
    #exit;
    if(!empty($data)){
    	?>
    	<div class="box box-success">
			
            <div class="box-header">
        		<h3 class="box-title">Accident Reports</h3>
			</div>
			<div class="box-body">
				<?php
				foreach($data as $v){
					?>
					<div class="col-md-3 col-sm-6 col-xs-12">
				        <div class="info-box">
				            <span class="info-box-icon bg-aqua">
				                <i class="fa fa-pencil"></i>
				            </span>
				            <div class="info-box-content">
				                
				                <span class="info-box-number">
				                	<small>
				                	<?php
									echo $this->Html->link(
			            				$v['AccidentFile']['name'] .': '. $v['Accident']['first_name'] .' '. $v['Accident']['last_name'],
			                			array('controller'=>'AccidentStatements', 'action'=>'index', $v['AccidentFile']['id']),
			                			array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
									);
									?>	
									</small>
				                </span>
				            </div><!-- /.info-box-content -->
				        </div><!-- /.info-box -->
				    </div><!-- /.col -->
				    <?php
				}
				?>
			</div>
		</div>
		<?php
	}
	?>