<?php
	if( AuthComponent::user('Role.permission_level') >= 30){
    	$dashboardData = $this->requestAction('/BingoGame/getDashboard/');
	    #pr($data);
	    ?>
		<div class="box box-default" style="border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE;">
	    	<div class="box-header">
        		<h3 class="box-title">Bingo</h3>
				<div class="box-tools pull-right">
            <?php
            echo $this->Html->link(
                '<i class="fa fa-plus fa-fw"></i> <span>New Game</span>',
                array('controller'=>'BingoGame', 'action'=>'add'),
                array('escape'=>false)
            );
            ?>
        </div>
			</div>
			<div class="box-body">
            	<table class="table table-striped table-condensed" id="accountsTable">
	            	<thead>
	                	<tr class="tr-heading">
	                    	<th class="col-sm-2">Start Date</th>
	                        <th class="col-sm-2">End Date</th>
	                        <th class="col-sm-2">Winner</th>
	                        <th class="col-sm-1">Amount</th>
	                        <th class="col-sm-2">Last Drawn Date</th>
							<th class="col-sm-3"></th>
	                    </tr>
	                </thead>

	                <tbody>
	                	<?php
						if(!empty($dashboardData)){
	                        foreach($dashboardData as $v){
								$start = (!empty($v['BingoGame']['start_date'])) ? CakeTime::format($v['BingoGame']['start_date'], '%b %e, %Y') : null ;
								$end = (!empty($v['BingoGame']['end_date'])) ? CakeTime::format($v['BingoGame']['end_date'], '%b %e, %Y') : null ;
								$winner = (!empty($v['Winner']['first_name'])) ? $v['Winner']['first_name'].' '.$v['Winner']['last_name']  : null ;
								$amount = (!empty($v['Winner']['first_name'])) ? '$'. $v['BingoGame']['amount']  : null ;
								$amount = (is_null($amount)) ? '$'.$v['BingoGame']['amount'] : $amount ;
								$ballDate = (!empty($v['BingoGameBall'][0]['Ball']['ball'])) ? CakeTime::format($v['BingoGameBall'][0]['date'], '%b %e, %Y') : 'No Balls Drawn' ;
								?>
								<tr>
									<td><?=$start?></td>
									<td><?=$end?></td>
									<td><?=$winner?></td>
									<td><?=$amount?></td>
									<td>
										<?php
						                echo $this->Html->link(
	                						$ballDate,
						                    array('controller'=>'BingoGame', 'action'=>'drawn', $v['BingoGame']['id']),
						                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
						                );
										?>
						            </td>

									<td>
										<?php
										#pr($end);
										if(is_null($end) || empty($end)){
											?>
											<ul class="list-inline">
												<li>
													<?php
									                echo $this->Html->link(
	                									'Drawn New Ball',
									                    array('controller'=>'BingoGame', 'action'=>'newdrawn', $v['BingoGame']['id']),
									                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
									                );
													?>
												</li>
												<li>
													<?php
													echo $this->Html->link(
	                									'Bingo',
									                    array('controller'=>'BingoGame', 'action'=>'bingo', $v['BingoGame']['id']),
									                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
									                );
													?>
												</li>
											</ul>
											<?php
										}
										?>
						            </td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="box-footer" style="border-bottom: 1px solid #D2D6DE;"></div>
		</div>
		<?php
	}
?>

