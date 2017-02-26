<?php
	$safety = $this->requestAction('/BingoGame/safety/');
	#pr($safety);
?>
<div class="row" >
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-calendar"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Last Accident</span>
                <span class="info-box-number"><small><?=$safety['accident_days']?></small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-trophy"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Last Award (<?=$safety['amount']?>)</span>
                <span class="info-box-number"><?=$safety['winner']?> <br /><small><?=$safety['date']?></small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow">
				<?=$safety['ball']?>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">
					Bingo Ball [
					<?php
	                echo $this->Html->link(
	                	'See All',
	                    array('controller'=>'BingoGame', 'action'=>'drawn', $safety['currentGame']),
	                    array('escape'=>false, 'data-toggle'=>'modal', 'data-target'=>'#myModal')
	                );
	                ?>
					 ]
				</span>
                <span class="info-box-number">
					<small><?=$safety['ballDate']?></small>
					<br /><small><?php echo $this->Number->currency($safety['current_amount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));?></small>
				</span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-dollar"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Money Given</span>
                <span class="info-box-number">
					<?php echo $this->Number->currency($safety['totalAmount'], false, $options=array('before'=>'$', 'zero'=>'$0.00'));  ?>
				</span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
</div>
<?php

unset($safety);
?>