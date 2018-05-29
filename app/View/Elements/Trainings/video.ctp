<?php
    $user = AuthComponent::user();
    $fileTypes = Set::extract($trn['TrainingFile'], '/file_type');
    $i_key = null;
    
    $v_key = array_search('mp4', $fileTypes); 
    
    $i_key = (is_null($i_key)) ? array_search('gif', $fileTypes) : null; 
    $i_key = (is_null($i_key)) ? array_search('jpg', $fileTypes) : $i_key; 
    
    $v_img = '/files/training/'.$trn['Training']['id'].'/'. $trn['TrainingFile'][$i_key]['file'];
    $v_video = '/files/training/'.$trn['Training']['id'].'/'.$trn['TrainingFile'][$v_key]['file'];
    echo $this->Html->media(
		array($v_video),
        array('controls', 'fullBase' => true, 'style'=>'width: 100%;', 'poster'=>$v_img)
	);

?>