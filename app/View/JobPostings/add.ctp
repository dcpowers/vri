<?php
echo $this->Form->create('JobPosting', array(
    'url' => array('controller'=>'JobPostings', 'action'=>'add', 'member'=>true),
    'role'=>'form',
    //'class'=>'form-horizontal',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class'=>'form-control',
        'error'=>false
    )
));
echo $this->Form->hidden('group_id', array('value'=>$group_id));
?>
<style type="text/css">
    #ex1Slider .slider-selection {
        background: #BABABA;
    }
</style>
<div class="modal-header modal-header-success">
    <a class="close" data-dismiss="modal"><i class="fa fa-close fa-2x"></i></a>
    <h2><?php echo __('Create Job Posting');?></h2>
</div>

<div class="modal-body">

    <div class="row">
        <div class="col-md-6">
            <?php
            unset($class,$error);
            $class = !empty($validationErrors['JobPosting']['job_id']) ? 'has-error has-feedback' : '';
            $error = !empty($validationErrors['JobPosting']['job_id']) ? $validationErrors['JobPosting']['job_id'][0] : '';
            ?>

            <div class="form-group <?=$class?>">
                <label class="control-label" for="job_id">Job Title:</label>
                <?php
                echo $this->Form->input('job_id', array(
                    'type'=>'select',
                    'options'=>$jobs,
                    'empty' => false,
                    'multiple' => false,
                    'class'=>'form-control chzn-select'
                ));
                ?>
                <span class="text-danger"><?=$error?></span>
            </div>
        </div>
        <div class="col-md-6">
            <?php
            unset($class,$error);
            $class = !empty($validationErrors['JobPosting']['group_id']) ? 'has-error has-feedback' : '';
            $error = !empty($validationErrors['JobPosting']['group_id']) ? $validationErrors['JobPosting']['group_id'][0] : '';
            ?>
            <div class="form-group <?=$class?>">
                <label class="control-label" for="jobTalentpattern">Location:</label>
                <?php
                echo $this->Form->input('group_id', array(
                    'type'=>'select',
                    'options'=>$locations,
                    'empty' => false,
                    'multiple' => false,
                    'class'=>'form-control'
                ));
                ?>
                <small>Only Locations/Departments with city,state and zip codes will be displayed in list.</small><br />
                <small>To edit Locations/Deparments:
                    <?php
                    echo $this->Html->link(
                        'Edit Locations/Departments',
                        array('controller'=>'groups', 'action'=>'orgLayout', 'member'=>true),
                        array('escape'=>false)
                    );
                    ?>

                </small>
                <span class="text-danger"><br /><?=$error?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
            unset($class,$error);
            $class = !empty($validationErrors['JobPosting']['job_talentpattern_id']) ? 'has-error has-feedback' : '';
            $error = !empty($validationErrors['JobPosting']['job_talentpattern_id']) ? $validationErrors['JobPosting']['job_talentpattern_id'][0] : '';
            ?>
            <div class="form-group <?=$class?>">
                <label class="control-label" for="jobTalentpattern">Talent Pattern:</label>
                <?php
                echo $this->Form->input('job_talentpattern_id', array(
                    'type'=>'select',
                    'options'=>$jobTalentpattern,
                    'empty' => false,
                    'multiple' => false,
                    'class'=>'form-control'
                ));
                ?>
                <span class="text-danger"><?=$error?></span>
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
                    'class'=>'form-control'
                ));
                ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="employmentType">Employment Type:</label>
                <?php
                echo $this->Form->input('employment_id', array(
                    'type'=>'select',
                    'options'=>$employmentTypes,
                    'empty' => true,
                    'multiple' => false,
                    'class'=>'form-control'
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
                    'class'=>'form-control'
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
                    if(!empty($this->request->data['JobPosting']['active_till_date'])){
                            $pieces = explode("-", $this->request->data['JobPosting']['active_till_date']);

                            $m = $pieces[1];
                            $d = $pieces[2];
                            $y = $pieces[0];

                            $b_today = $m.'-'.$d.'-'.$y;
                        }else{
                            $b_today = null;

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

<script language="JavaScript">
    jQuery(window).ready( function($) {
        $(".chzn-select").chosen();

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