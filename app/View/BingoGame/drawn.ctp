<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Bingo Balls Drawn: <small>Start Date: '.$bingo['BingoGame']['start_date']); ?></h2>
</div>

<div class="modal-body">
	<div class="row">
		<?php
		foreach($balls as $letter=>$col){
        	?>
			<div class="col-md-2 text-center">
				<div class="info-box-icon bg-yellow" style="margin-bottom: 5px;">
					<?=$letter?>
				</div>
				<?php
				foreach($col as $ball){
					$is_drawn = (in_array($ball['id'], $drawn)) ? 'bg-green' : null ;
					?>
					<div class="info-box-icon <?=$is_drawn?>" style="margin-bottom: 5px;">
						<?=$ball['num']?>
        			</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
		<div class="col-md-2">

		</div>
	</div>
</div>

<div class="modal-footer">
    <?php
    echo $this->Form->button(
        '<i class="fa fa-times fa-fw"></i> Close',
        array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')
    );

	?>
</div>