<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __($this->request->data['Training']['name']); ?></h2>
</div>

<div class="modal-body">
	<?php
	$v = '/files/training/'.$trn['Training']['id'].'/'.$video;
    $p = '/files/training/'.$trn['Training']['id'].'/'.$poster;

	echo $this->Html->media(
		array($v),
        array('controls', 'fullBase' => true, 'style'=>'width: 100%;', 'poster'=>$p)
	);
	?>
</div>
<div class="modal-footer">
    <?php
    echo $this->Html->link(
    	'I certify that I have viewed the above material and understand it\'s meaning.',
        array('controller'=> 'Trainings', 'action'=>'signoff', $trn['Training']['id'] ),
        array('class'=>'btn btn-block btn-danger','escape'=>false),
        "I certify that I have viewed the above material and understand it's meaning."
    );
    ?>
</div>
<?php echo $this->Form->end();?>

<script type="text/javascript">
    jQuery(window).ready( function($) {

    });
</script>