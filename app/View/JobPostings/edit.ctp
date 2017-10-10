<?php 
echo $this->Form->create('JobPosting', array(
    'url' => array('controller'=>'JobPostings', 'action'=>'edit', 'member'=>true, $id), 
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('id', array('value'=>$id));
?>
<style type="text/css">
    #ex1Slider .slider-selection {
        background: #BABABA;
    }
</style>
<div class="container">
    <h2 class="title clearfix"><i class="fa fa-thumb-tack"></i><span class="text">Edit Job Opening</span></h2>
    <hr class="solidOrange" />
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="job_id">Job Title:</label>
                <?php
                echo $this->Form->input('job_id', array(
                    'type'=>'select',
                    'options'=>$jobs,
                    'empty' => false, 
                    'multiple' => false, 
                    'class'=>'form-control',
                    'disabled'=>true,
                    'selected'=>$jobPosting['JobPosting']['job_id']
                ));
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="jobTalentpattern">Location:</label>
                <?php
                echo $this->Form->input('group_id', array(
                    'type'=>'select',
                    'options'=>$locations,
                    'empty' => false, 
                    'multiple' => false,
                    'class'=>'form-control',
                    'disabled'=>true,
                    'selected'=>$jobPosting['JobPosting']['group_id']
                ));
                ?>
                <small>Only Locations/Departments with zip codes will be displayed in list.</small><br />
                <small>To edit Locations/Deparments: 
                    <?php
                    echo $this->Html->link(
                        'Edit Locations/Departments',
                        array('controller'=>'groups', 'action'=>'orgLayout', 'member'=>true),
                        array('escape'=>false)
                    );
                    ?>
                    
                </small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="jobTalentpattern">Talent Pattern:</label>
                <?php
                echo $this->Form->input('job_talentpattern_id', array(
                    'type'=>'select',
                    'options'=>$jobTalentpattern,
                    'empty' => false, 
                    'multiple' => false,
                    'disabled'=>true, 
                    'class'=>'form-control',
                    'selected'=>$jobPosting['JobPosting']['job_talentpattern_id']
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="questionSet">Percent Match: </label>
                <div>
                <?php 
                echo $this->Form->input('percent_match', array (
                    'id'=>'ex1', 
                    'data-slider-id' => 'ex1Slider',
                    'type'=>'text', 
                    'data-slider-min'=>'60', 
                    'data-slider-max'=>'100', 
                    'data-slider-step'=>'1', 
                    'data-slider-value'=>'75',
                    'data-slider-enabled'=> $percent_match_options
                ));
                ?>
                </div>
                <small>This is a percent match ( % ) to job seekers. Default is 75%. </small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="questionSet">Screening Questions:</label>
                <?php
                echo $this->Form->input('job_question_id', array(
                    'type'=>'select',
                    'options'=>$jobQuestion,
                    'empty' => true, 
                    'multiple' => false, 
                    'class'=>'form-control',
                    'disabled'=>true,
                    'selected'=>$jobPosting['JobPosting']['job_question_id']
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="employmentType">Employment Type:</label>
                <?php
                echo $this->Form->input('employment_type_id', array(
                    'type'=>'select',
                    'options'=>$employmentTypes,
                    'empty' => true, 
                    'multiple' => false, 
                    'class'=>'form-control',
                    'disabled'=>true,
                    'selected'=>$jobPosting['JobPosting']['employment_type_id']
                ));
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="description">Job Description:</label>
                <?php
                echo $this->Form->input('description', array (
                    'type' => 'textarea',
                    'value'=>$jobPosting['JobPosting']['description']
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="requirements">Job Requirements:</label>
                <?php
                echo $this->Form->input('requirements', array (
                    'type' => 'textarea',
                    'value'=>$jobPosting['JobPosting']['requirements']
                ));
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="soc_code">SOC Code:</label>
                <?php
                echo $this->Form->input('soc_code', array (
                    'type' => 'text',
                    'value'=>$jobPosting['JobPosting']['soc_code']
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="salary">Salary:</label>
                <?php
                echo $this->Form->input('salary', array (
                    'type' => 'text',
                    'value'=>$jobPosting['JobPosting']['salary']
                ));
                ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="salary_type">Salary Type:</label>
                <?php
                echo $this->Form->input('salary_type_id', array(
                    'type'=>'select',
                    'options'=>$settings['salaryTypes'],
                    'empty' => true, 
                    'multiple' => false, 
                    'class'=>'form-control',
                    'selected'=>$jobPosting['JobPosting']['salary_type_id']
                ));
                ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="active_till_date">Active Till Date:</label>
                <div class="input-group input-append date" id="dob" data-date="<?=$today?>" data-date-format="mm-dd-yyyy" >
                    <span class="add-on input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php
                    
                    if(!empty($this->request->data['JobPosting']['active_till_date']) ){
                        $pieces = explode("-", $this->request->data['JobPosting']['active_till_date']);
            
                        $m = $pieces[1];
                        $d = $pieces[2];
                        $y = $pieces[0];
                                                                                                            
                        $b_today = $m.'-'.$d.'-'.$y;
                    }else{
                        $pieces = explode("-", $jobPosting['JobPosting']['active_till_date']);

                        if($pieces[0] == '00' || empty($pieces[0])){
                            $b_today = null;
                        }else{
                            $m = $pieces[1];
                            $d = $pieces[2];
                            $y = $pieces[0];
                                                                                                            
                            $b_today = $m.'-'.$d.'-'.$y;
                        }
                    }
                                                        
                    echo $this->Form->input('active_till_date', array(
                        'type'=>'text',
                        'readonly'=>true,
                        'value'=>$b_today
                    )); 
                    ?>
                </div>
                <small>Click Calendar to select date. Leave Blank for open date.</small>
            </div>
        </div>
        
        <div class="col-md-6">
            <?php 
            unset($class,$error);
            $class = !empty($validationErrors['JobPosting']['status']) ? 'has-error has-feedback' : ''; 
            $error = !empty($validationErrors['JobPosting']['status']) ? $validationErrors['JobPosting']['status'][0] : '';
            ?>
            
            <div class="form-group <?=$class?>">
                <label class="control-label" for="status">Status:</label>
                <?php 
                unset($settings['status'][0]); 
                ksort($settings['status']);
                
                echo $this->Form->input('status', array(
                    'type'=>'select',
                    'options'=>$settings['status'],
                    'selected'=>$settings['status'][1], 
                    'empty' => false, 
                    'multiple' => false, 
                    'class'=>'form-control'
                ));
                ?>
                <span class="text-danger"><?=$error?></span>
            </div>
        </div>
    </div>
    
    <?php 
    echo $this->Html->link( 
        '<i class="fa fa-times"></i> Cancel', 
        array('controller'=>'JobPostings', 'action'=>'index', 'member'=>true ), 
        array('escape'=>false, 'class'=>'btn btn-default btn-sm')  
    ); 
    ?>
    <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i> Save', array('type'=>'submit', 'div'=>false, 'label'=>false, 'class'=>'btn btn-primary btn-sm')); ?>
    <hr/>    
</div>
<?php echo $this->Form->end();?>    

<script language="JavaScript">
    jQuery(window).ready( function($) {
        $('#dob').datepicker({
            autoclose: true,
            startView: 0,
            startDate: '<?=$today?>',
            clearBtn: true
        });
        
        $('#ex1').slider({
            formatter: function(value) {
                return value + '%';
            }
        }); 
    });
</script>