<?php
    #$this->Html->css('Training.bootstrap-lightbox.css', '', array('block' => 'css') );
    
    $q_count = count($quiz);
    $step = 600/$q_count;
    
?>
<div class="modal-header modal-header-primary">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Quiz: <small> TRN-' . str_pad( $quiz[0]['Training']['id'], 4, 0, STR_PAD_LEFT ).' '. $quiz[0]['Training']['title'].'</small>'); ?></h2>
</div>
<div class="modal-body">
    <div id="alert" class="radius hide"></div>
    <?php echo $this->Form->create('TrnSignoff');?>
    
    <?php 
    $c = 1;  
    
    foreach( $quiz as $q ) { 
        $name = "q".$c;
        $new_class = ($c >= 2) ? "none": NULL;
        ?>
        <div class="questionContainer radius" style="height: 275px; display: <?=$new_class?>">
            <div class="question" rel="<?php echo $q['TrainingQuiz']['answer'] ?>"><?php echo $q['TrainingQuiz']['question']?></div>
                    
            <div class="answers">
                <?php
                if(!empty($q['TrainingQuiz']['answer_a'])){
                    ?>
                    <div class="radio">
                        <label><input type="radio" name="<?=$name?>" id="<?=$name?>-a" class="answer " rel="A"/><?=$q['TrainingQuiz']['answer_a']?></label>
                    </div>
                    <?php
                }
                        
                if(!empty($q['TrainingQuiz']['answer_b'])){
                    ?>
                    <div class="radio">
                        <label><input type="radio" name="<?=$name?>" id="<?=$name?>-b" class="answer" rel="B"/><?=$q['TrainingQuiz']['answer_b']?></label>
                    </div>
                    <?php
                }
                        
                if(!empty($q['TrainingQuiz']['answer_c'])){
                    ?>
                    <div class="radio">
                        <label><input type="radio" name="<?=$name?>" id="<?=$name?>-c" class="answer" rel="C"/><?=$q['TrainingQuiz']['answer_c']?></label>
                    </div>
                    <?php
                }
                            
                if(!empty($q['TrainingQuiz']['answer_d'])){
                    ?>
                    <div class="radio">
                        <label><input type="radio" name="<?=$name?>" id="<?=$name?>-d" class="answer" rel="D"/><?=$q['TrainingQuiz']['answer_d']?></label>
                    </div>
                    <?php
                }
                                
                if(!empty($q['TrainingQuiz']['answer_e'])){
                    ?>
                    <div class="radio">
                        <label><input type="radio" name="<?=$name?>" id="<?=$name?>-e" class="answer" rel="E"/><?=$q['TrainingQuiz']['answer_e']?></label>
                    </div>
                    <?php
                }
                ?>
            </div><!--.answers-->
                    
            <div class="btn-group">
                <?php
                if($c >= 2){
                    ?>
                    <button type="button" class="btnPrev btn btn-default">&lt;&lt; Prev</button>
                    <?php
                }
                
                if($c == $q_count){
                    ?>
                    <button type="button" class="btnShowResult btn btn-info">Finish &gt;&gt;</button>
                    <?php
                }else{
                    ?>
                    <button type="button" class="btnNext btn btn-info">Next &gt;&gt;</button>
                    <?php
                }
                ?>
                
                <div class="clear"></div>
            </div>
            
            <div class="success hide" style="margin-top: 15px;">
                <i class="fa fa-check fa-white"></i>
                <strong>Correct</strong> - <?php echo  $q['TrainingQuiz']['action_step']; ?>
            </div>
                    
            <div class="fail hide" style="margin-top: 15px;">
                <i class="fa fa-remove fa-white"></i>
                <strong>Incorrect</strong> - <?php echo  $q['TrainingQuiz']['action_step']; ?>
            </div>
        </div>
        <?php 
        $c++; 
    } 
    ?>
    
    <div class="txtStatusBar">Status Bar</div>
                
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="10" id="bar">
            <span class="sr-only"></span>
        </div>
    </div>
    <?php echo $this->Form->end();?>
</div>
<div class="modal-footer">
    <div id="resultKeeper" class="radius hide">
        <?php
        echo $this->Html->link(
            'Close Window',
            array('plugin'=>'training', 'controller'=>'trainings', 'action'=>'index'),
            array('escape'=>false, 'class'=>'btn btn-primary pull-left')
        );
        ?>
        <br />
    </div>
</div>
<script type=text/javascript>
$(function(){
    var jQuiz = {
        answers: { q1: 'b', q2: 'd', q3: 'a', q4: 'c', q5: 'a' },
        questionLenght: <?=$q_count?>,
        questionCorrect: 0,
        
        getAnswers: function() {
            return this.answers;
        },
        checkAnswers: function() {
            var arr = this.answers;
            var ans = this.userAnswers;
            var resultArr = []
            for (var p in ans) {
                var x = parseInt(p) + 1;
                var key = 'q' + x;
                var flag = false;
                if (ans[p] == 'q' + x + '-' + arr[key]) {
                    flag = true;
                }
                else {
                    flag = false;
                }
                resultArr.push(flag);
            }
            return resultArr;
        },
        init: function(){
            $('.btnNext').click(function(){
                if ($('input[type=radio]:checked:visible').length == 0) {
                            
                    return false;
                }
                $(this).parents('.questionContainer').fadeOut(500, function(){
                    $(this).next().fadeIn(500);
                });
                var el = $('#bar');
                el.width(el.width() + <?=$step?> + 'px');
            });
            
            $('.answer').click(function(){
                q = $(this).attr('name');
                clicked = $(this).attr('rel');
                
                answerKey = $(this).closest('.questionContainer').find('.question').attr('rel');
                
                $(this).closest('.questionContainer').find('.reveal').show();
                
                if(answerKey == clicked){
                    $(this).closest('.questionContainer').find('.fail').addClass('hide');
                    
                    $(this).closest('.questionContainer').find('.success').show();
                    $(this).closest('.questionContainer').find('.success').removeClass('hide');
                    $(this).closest('.questionContainer').find('.success').addClass('alert-success');
                    $(this).closest('.questionContainer').find('.success').addClass('alert');
                    
                    jQuiz.questionCorrect++;
                }else{
                    $(this).closest('.questionContainer').find('.success').addClass('hide');
                    
                    $(this).closest('.questionContainer').find('.fail').show();
                    $(this).closest('.questionContainer').find('.fail').removeClass('hide');
                    $(this).closest('.questionContainer').find('.fail').addClass('alert-danger');
                    $(this).closest('.questionContainer').find('.fail').addClass('alert');
                    
                }
                $(this).closest('.questionContainer').find('.answer').attr('disabled', 'disabled');
                console.log(jQuiz.questionCorrect);
                
            });
            $('.btnPrev').click(function(){
                $(this).parents('.questionContainer').fadeOut(500, function(){
                    $(this).prev().fadeIn(500)
                });
                var el = $('#bar');
                el.width(el.width() - 60 + 'px');
            })
            $('.btnShowResult').click(function(){
                var arr = $('input[type=radio]:checked');
                var ans = jQuiz.userAnswers = [];
                for (var i = 0, ii = arr.length; i < ii; i++) {
                    ans.push(arr[i].getAttribute('id'))
                }
            })
            /**
            $('.btnShowResult').click(function(){
                $('#bar').width(300);
                $('#progressKeeper').hide();
                var results = jQuiz.checkAnswers();
                var resultSet = '';
                var trueCount = 0;
                for (var i = 0, ii = results.length; i < ii; i++){
                    if (results[i] == true) trueCount++;
                    resultSet += '<div> Question ' + (i + 1) + ' is ' + results[i] + '</div>';
                }
                resultSet += '<div class="totalScore">Your total score is ' + trueCount * 20 + ' / 100</div>';
                $('#resultKeeper').html(resultSet).show();
            })**/
            
            $('.btnShowResult').click(function(){
                $('#progress').width(300);
                $('#progressKeeper').hide();
                $('.btnShowResult').hide();
                $('.btnPrev').hide();
                $('.questionContainer').hide();
                $('.txtStatusBar').hide();
                $('.progress').hide();
                
                score = Math.round((jQuiz.questionCorrect / jQuiz.questionLenght) * 100);
                
                if(score >= 70)
                {
                    resultSet = '<div class="alert alert-success" role="alert">Congratulations!! You Scored a '+ score +'</div> ';
                    
                    $.ajax({
                        url:'/training/training_records/update_score/<?=$trn_record_id?>/'+ score
                    })
                        
                }
                else
                {
                    resultSet = '<div class="alert alert-danger" role="alert">Your score of '+ score +' does not meet the minimum score of 70. Please review the material and retake the quiz.</div>' ;
                    
                    $.ajax({
                        url:'/training/training_records/cancel/<?=$trn_record_id?>'
                    })
                }
                
                $('#resultKeeper').removeClass('hide')
                $('#alert').removeClass('hide')
                $('#alert').append(resultSet).show();
            })
        }
    };
    jQuiz.init();
})
</script>