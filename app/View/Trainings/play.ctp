<div class="modal-header modal-header-warning">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __($this->request->data['Training']['name']); ?></h2>
</div>

<div class="modal-body">
	<?php
	$fileTypes = Set::extract($trn['TrainingFile'], '/file_type');
	
	if(empty($trn['TrainingFile'])) {
        if(!empty($trn['Training']['description'])){
            echo $trn['Training']['description'];
            $trn['TrainingFile'] = 1;
        }else{
            echo "No Content Found For This Training";
        }
    }else if(in_array('mp4', $fileTypes)) {
        echo $this->element( 'Trainings/video' );
    }else{
        echo $this->element( 'Trainings/photos' ); 
    }
    /*
	$v = '/files/training/'.$trn['Training']['id'].'/'.$video;
    $p = '/files/training/'.$trn['Training']['id'].'/'.$poster;

	echo $this->Html->media(
		array($v),
        array('controls', 'fullBase' => true, 'style'=>'width: 100%;', 'poster'=>$p)
	);
	*/
	?>
</div>
<div class="modal-footer">
    <?php
    if(empty($trn['TrainingFile'])) {
        echo $this->Html->link( 
            __('Close Window'), 
            array('plugin'=>'training', 'controller'=> 'trainings', 'action'=>'index'),
            array('class'=>'btn btn-default pull-left','escape'=>false, 'data-dismiss'=>'modal')  
        );
    }else if(!empty($trn['TrainingQuiz'])) {
        echo $this->Html->link(
            'I certify that I have viewed the above material and understand it\'s meaning.',
            #array('controller'=> 'Trainings', 'action'=>'quiz', $trn['Training']['id'] ),
            array('controller'=> 'Trainings', 'action'=>'signoff', $trn['Training']['id'] ),
            array('class'=>'btn btn-lg btn-block btn-danger','escape'=>false),
            "I certify that I have viewed the above material and understand it's meaning."
        );
    }else{
        echo $this->Html->link(
            'I certify that I have viewed the above material and understand it\'s meaning.',
            array('controller'=> 'Trainings', 'action'=>'signoff', $trn['Training']['id']),
            array('class'=>'btn btn-lg btn-block btn-danger','escape'=>false),
            "I certify that I have viewed the above material and understand it's meaning."
        );
    }
    /*
    echo $this->Html->link(
    	'I certify that I have viewed the above material and understand it\'s meaning.',
        array('controller'=> 'Trainings', 'action'=>'signoff', $trn['Training']['id'] ),
        array('class'=>'btn btn-block btn-danger','escape'=>false),
        "I certify that I have viewed the above material and understand it's meaning."
    );
    */
    ?>
</div>
<?php echo $this->Form->end();?>

<script type="text/javascript">
    jQuery(window).ready( function($) {

    });
</script>