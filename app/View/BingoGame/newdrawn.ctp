<?php
    echo $this->Form->create('BingoGameBall', array(
        'url'=>array('controller'=>'BingoGame', 'action'=>'newdrawn'),
        'class'=>'form-horizontal',
        'role'=>'form',
        'inputDefaults'=>array(
            'label'=>false,
            'div'=>false,
            'class'=>'form-control',
            'error'=>false
        )
    ));

    echo $this->Form->hidden('bingo_game_id', array('value'=>$bingo_game_id));
    ?>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Bingo Ball: <small>Start Date: '.CakeTime::format($bingo['BingoGame']['start_date'], '%b %e, %Y'));?></h2>
</div>

<div class="modal-body">
	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Bingo Balls:</label>
        <div class="col-sm-8">
            <?php
            echo $this->Form->input('bingo_ball_id', array (
                'options'=>$ballList,
				'empty'=>false,
                'class'=>'form-select chzn-select',
				'multiple'=>false,
				'data-placeholder'=>'Select Bingo Ball'
            ));
            ?>
        </div>
    </div>

	<div class="form-group">
        <label class="col-sm-4 control-label" for="name">Current Balls:</label>
        <div class="col-sm-8">
			<ul class="list-unstyled form-control-static">
				<?php
				foreach($drawn as $v){
					?>
					<li><?=$allballs[$v]?></li>
					<?php
				}
				?>
			</ul>
		</div>
    </div>
</div>

<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

	echo $this->Form->button(
        '<i class="fa fa-save fa-fw"></i> Save',
        array('type'=>'submit', 'class'=>'btn btn-primary pull-left')
    );
    ?>
</div>
<?php echo $this->Form->end();?>
<script type="text/javascript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen({
            allow_single_deselect: true
        });
	});
</script>