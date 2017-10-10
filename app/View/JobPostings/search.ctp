<?php 
    #pr( $distance );
    #pr( $jobPost );
    #pr($accountLevel);
    #exit;
    echo $this->Form->create('search', array(
        'url' => array('controller'=>'JobPostings', 'action'=>'search', 'member'=>true, $id), 
        'role'=>'form',
        'class'=>'form-inline',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class'=>'form-control',
            'error'=>false
        )
    ));
                            
    $miles = array(
        '20'=>'20 Miles',
        '50'=>'50 Miles',
        '100'=>'100 Miles',
        '250'=>'250 Miles',
        '3959'=>'Everywhere',
    );
?>
<style type="text/css">
    #ex2Slider .slider-selection {
        background: #BABABA;
    }
    
    #ex2Slider {
        margin:0px 10px;
    }
    
    .faded{
        color: #cccccc;
    }
    
</style>
<div class="container">
    <h2 class="title"><i class="fa fa-search"></i><span class="text">Job Seeker Search</span></h2>
    <hr class="solidOrange" />
    
    <div class="well">
        <div class="form-group">
            <label for="exampleInputName2" class="sr-only">Job Opening:</label>
            <?php 
            echo $this->Form->input('id', array(
                'type'=>'select',
                'options' => $pick_list,
                'selected'=>$id,
                'empty' => 'Select A Job Opening',
                'multiple' => false,
                'class'=>'form-control',
            )); 
            ?>
        </div>
        
        <div class="form-group">
            <label for="exampleInputName2" class="sr-only">Distance:</label>
            <?php
            echo $this->Form->input('area', array(
                'type'=>'select',
                'options' => $miles,
                'selected'=>$distance,
                'empty' => 'Select A Distance',
                'multiple' => false,
                'class'=>'form-control',
            )); 
            ?>
        </div>
        
        <div class="form-group">
            <label for="exampleInputName2">Talent Match: <span id="sliderVal"><?=$percent_match?>%</span></label>
            
                <?php 
                echo $this->Form->input('match', array (
                    'id'=>'ex2', 
                    'data-slider-id' => 'ex2Slider',
                    'type'=>'text', 
                    'data-slider-min'=>'50', 
                    'data-slider-max'=>'100', 
                    'data-slider-step'=>'1',
                    'data-slider-value'=>$percent_match,
                    'class'=>'form-control',
                ));
                ?>
                
        </div>
        
        <div class="form-group">
            <?php
            echo $this->Form->button('<i class="fa fa-search"></i><span class="text">Search</span>', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm'));
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php
    if(isset($jobPost['Job']['name'])){
        ?>
        <h3><?=$jobPost['Job']['name']?>: <small><?=$jobPost['Group']['city']?>, <?=$jobPost['Group']['State']['state_name']?></small></h3>
        <?php
    }
    ?>
    <table class="table table-hover table-condensed">
        <thead>
            <tr class="tr-heading">
                <th>Name</th>
                <th>E-Mail Address</th>
                <th>Location</th>
                <th>Percent Match</th>
                <th>Status</th>
            </tr>
        </thead>
                
        <tbody>
            <?php
            if(!empty($users['none'])){
                ?>
                <tr>
                    <td colspan="5"><?=$users['none'][0];?></td>
                </tr>
                <?php
            }else{
                #pr($jobPost);
                #pr($users);
                foreach($users as $user){
                    $full_name = $user['User']['first_name'].' '.$user['User']['last_name'];
                    $e_mail = $user['User']['username'];
                    $match = $user['match']['overall'].'%';
                    
                    $status = empty($user['User']['ExemptJob']) ? null : 'Not A Fit' ; 
                    $status = (empty($user['User']['ApplyJob']) AND is_null($status)) ? 'New' : $status ;
                    $status = (!empty($user['User']['ApplyJob']) AND is_null($status)) ? 'Invited To Apply' : $status ;
                    
                    $color = ($status == 'Not A Fit')? 'faded' : null ;
                    
                    ?>
                    <tr class="<?=$color?>">
                        <td>
                        <?php
                            if($status == 'Not A Fit'){
                                echo $full_name;
                            }else{
                                echo $this->Html->link( 
                                    $full_name, 
                                    array('controller'=>'Jobseekers','action'=>'details','member'=>true, $user['User']['id'],$jobPost['JobPosting']['id']), 
                                    array('escape'=>false) 
                                );
                            }
                        ?>
                        </td>
                        <td><?=$e_mail?></td>
                        <td><?=$user['DetailUser']['city']?>, <?=$user['State']['state_name']?></td>
                        <td><?=$match?></td>
                        <td><?=$status?></td>
                    </tr>
                    <?php
                }
            }    
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(window).ready( function($) {
        $("#myModal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $("#myModal2").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
        
        $('#ex2').slider({
            formatter: function(value) {
                return 'Talent Match: ' + value + '%';
            },
            
        });
        
        $("#ex2").on("slide", function(slideEvt) {
            $("#sliderVal").text(slideEvt.value+'%');
        });
    });
</script>