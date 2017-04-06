<?php
    $this->Html->css('Training.bootstrap-lightbox.css', '', array('block' => 'css') );
    
?>
<div class="row-fluid two-column with-right-sidebar">
    <div class="span12">
        <h1>
            <?php echo 'TRN-' . str_pad( $quiz[0]['Training']['id'], 4, 0, STR_PAD_LEFT )?> <?php echo $quiz[0]['Training']['title'];?>
            <small>
                Rev# <?php echo $quiz[0]['Training']['revision'];?>,
                Revised: <?php echo date( APP_DATE_FORMAT, strtotime( $quiz[0]['Training']['revised_on'] ));?>
            </small>
        </h1>
        
        <div class="row-fluid">
            <div class="training_signoffs sme_queue span9">
                <?php echo $this->Form->create('TrnSignoff');?>
                <?php $c = 1;  foreach( $quiz as $q ) { 
                $name = "q".$c;
                $new_class = ($c >= 2) ? "hide": NULL;
                ?>
                
                <div class="questionContainer radius <?=$new_class?>" style="height: 275px">
                    <div class="question" rel="<?php echo $q['TrainingQuiz']['answer'] ?>"><?php echo $q['TrainingQuiz']['question']?></div>
                    
                    <div class="answers">
                        <ul>
                            <?php
                            if(!empty($q['TrainingQuiz']['answer_a']))
                            {
                                ?>
                                <li>
                                    <label><input type="radio" name="<?=$name?>" id="<?=$name?>-a" class="answer" rel="A"/><?=$q['TrainingQuiz']['answer_a']?></label>
                                </li>
                                <?php
                            }
                        
                            if(!empty($q['TrainingQuiz']['answer_b']))
                            {
                                ?>
                                <li>
                                    <label><input type="radio" name="<?=$name?>" id="<?=$name?>-b" class="answer" rel="B"/><?=$q['TrainingQuiz']['answer_b']?></label>
                                </li>
                                <?php
                            }
                        
                            if(!empty($q['TrainingQuiz']['answer_c']))
                            {
                                ?>
                                <li>
                                    <label><input type="radio" name="<?=$name?>" id="<?=$name?>-c" class="answer" rel="C"/><?=$q['TrainingQuiz']['answer_c']?></label>
                                </li>
                                <?php
                            }
                        
                            if(!empty($q['TrainingQuiz']['answer_d']))
                            {
                                ?>
                                <li>
                                    <label><input type="radio" name="<?=$name?>" id="<?=$name?>-d" class="answer" rel="D"/><?=$q['TrainingQuiz']['answer_d']?></label>
                                </li>
                                <?php
                            }
                            
                            if(!empty($q['TrainingQuiz']['answer_e']))
                            {
                                ?>
                                <li>
                                    <label><input type="radio" name="<?=$name?>" id="<?=$name?>-e" class="answer" rel="E"/><?=$q['TrainingQuiz']['answer_e']?></label>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div><!--.answers-->
                    
                    <div class="btnContainer">
                        <?php
                        if($c >= 2)
                        {
                            ?>
                            <div class="prev button">
                                <a class="btnPrev">&lt;&lt; Prev</a>
                            </div>
                            <?php
                        }
                
                        if($c == 10)
                        {
                            ?>
                            <div class="next button">
                                <a class="btnShowResult">Finish &gt;&gt;</a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="next button">
                                <a class="btnNext">Next &gt;&gt;</a>
                            </div>
                            <?php
                        }?>
                        <div class="clear"></div>
                    </div>
            
                    <div class="success hide">
                        <i class="icon-ok icon-white"></i>
                        <strong>Correct</strong> - <?php echo  $q['TrainingQuiz']['action_step']; ?>
                    </div>
                    
                    <div class="fail hide">
                        <i class="icon-remove icon-white"></i>
                        <strong>Incorrect</strong> - <?php echo  $q['TrainingQuiz']['action_step']; ?>
                    </div>
                </div>
        
                <?php $c++; } ?>
                <div class="txtStatusBar">Status Bar</div>
        
                <div id="progressKeeper" class="radius">
                    <div id="bar"></div>
                </div>
            
                <div id="resultKeeper" class="radius hide"></div>
            
                <div class="submit">
                    <?php //echo $this->Html->link(__('Add'), array('action'=>'add' ), array('class'=>'btn') ); ?>
                </div>
                <?php echo $this->Form->end();?>
            </div>
            <?php echo $this->element( 'sidebar' );?>
        </div>
    </div>
</div>

<script type=text/javascript>
$(function(){
    var jQuiz = {
        answers: { q1: 'b', q2: 'd', q3: 'a', q4: 'c', q5: 'a' },
        questionLenght: 10,
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
                el.width(el.width() + 60 + 'px');
            });
            $('.answer').click(function(){
                q = $(this).attr('name');
                clicked = $(this).attr('rel');
                
                answerKey = $(this).closest('.questionContainer').find('.question').attr('rel');
                
                $(this).closest('.questionContainer').find('.reveal').show();
                
                if(answerKey == clicked)
                {
                    $(this).closest('.questionContainer').find('.success').show();
                    $(this).closest('.questionContainer').find('.success').addClass('alert-success');
                    $(this).closest('.questionContainer').find('.success').addClass('alert');
                    
                    jQuiz.questionCorrect++;
                }
                else
                {
                    $(this).closest('.questionContainer').find('.fail').show();
                    $(this).closest('.questionContainer').find('.fail').addClass('alert-error');
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
                
                score = Math.round((jQuiz.questionCorrect / jQuiz.questionLenght) * 100);
                
                if(score >= 70)
                {
                    resultSet = 'Congratulations!! You Scored a '+ score +' ';
                    
                    $.ajax({
                        url:'/training/training_records/update_score/<?=$trn_record_id?>/'+ score
                    })
                        
                }
                else
                {
                    resultSet = 'Your score of '+ score +' does not meet the minimum score of 70. Please review the material and retake the quiz.' ;
                    
                    $.ajax({
                        url:'/training/training_records/cancel/<?=$trn_record_id?>'
                    })
                }
                
                $('#resultKeeper').html(resultSet).show();
            })
        }
    };
    jQuiz.init();
})
</script>