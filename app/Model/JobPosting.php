<?php

App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class JobPosting extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
    public $actsAs = array('Containable');
    //public $table = array('pro_job_postings');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(

    );

    public $hasMany = array(
        'Collaborater',
        'ApplyJob',
        'ExemptJob',

    );

    public $belongsTo = array(
        'Job' => array(
            'className' => 'Job',
            'foreignKey' => 'job_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'JobTalentpattern' => array(
            'className' => 'JobTalentpattern',
            'foreignKey' => 'job_talentpattern_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'JobQuestion' => array(
            'className' => 'JobQuestion',
            'foreignKey' => 'job_question_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'posted_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Group' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EmploymentType' => array(
            'className' => 'EmploymentType',
            'foreignKey' => 'employment_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SalaryType' => array(
            'className' => 'SalaryType',
            'foreignKey' => 'salary_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )

    );

    public $validationSets = array(
        'newListing' => array(
            'job_id' => array(
                'rule' => 'notBlank',
                'message' => 'Please select a Job Title'
            ),
            'job_talentpattern_id' => array(
                'rule' => 'notBlank',
                'message' => 'Please select a Talent Pattern'
            ),
            'status' => array(
                'rule' => 'notBlank',
                'message' => 'Please select a status',
                'allowEmpty' => false,
            ),
            'account_id' => array(
                'rule' => 'notBlank',
                'message' => 'Please select a Location/Department'
            ),
        )
    );

    public function retreiveJobPostings($group_ids=null, $talent_pattern_ids=null, $exempt_ids=null){
        $opts = array(
            'JobPosting.group_id' => $group_ids,
            'JobPosting.id !=' => $exempt_ids,
            'JobPosting.status' => 1
        );

        if(!is_null($talent_pattern_ids)){
            $opts = array_merge($opts, array('JobPosting.job_talentpattern_id' => $talent_pattern_ids));
        }

        $data = $this->find('all', array(
            'conditions' => $opts,
            'contain'=>array(
                'Job'=>array(
                    'Group'=>array(
                        'fields'=>array('Group.name', 'Group.pin_number'),
                    ),
                    'fields'=>array('Job.name'),
                ),
                'JobTalentpattern',
            )

        ));
        return $data;
    }

    public function retreiveJobPostingDetails($id=null){
        $data = $this->find('first', array(
            'conditions' => array(
                'JobPosting.id' => $id,
            ),
            'contain'=>array(
                'Job'=>array(
                    'Group'=>array(
                        'fields'=>array(
                            'Group.name',
                            'Group.welcome_title',
                            'Group.welcome_notes',
                            'Group.logo',
                            'Group.pin_number',
                            'Group.city',
                            'Group.state',
                        )

                    ),
                    'fields' => array('Job.name', 'Job.description', 'Job.salary_range'),
                ),
                'JobQuestion'=>array(
                    'JobQuestionDetail'
                ),
                'SalaryType',
                'ApplyJob',
                'JobTalentpattern',
                'Group'=>array(
                    'State',
                    'fields'=>array('Group.id', 'Group.zip', 'Group.group_type_id', 'Group.city', 'Group.state', 'Group.welcome_title', 'Group.welcome_notes',)
                ),
            )
        ));

        return $data;
    }

    public function retreiveAllJobPostings(){
        $data = $this->find('all', array(
            'conditions' => array(
                'JobPosting.status' => 1
            ),
            'contain'=>array(
                'Job'=>array(
                    'Group'
                ),
                'JobApply'
            ),
            'order'=>array('JobPosting.updated DESC')
        ));

        #pr($data);
        #exit;

        return $data;
    }

    public function searchJobPostings($group_ids=null, $talent_pattern_ids=null, $exempt_ids=null, $user=null){
        $opts = array(
            'JobPosting.group_id' => $group_ids,
            'JobPosting.id !=' => $exempt_ids,
            'JobPosting.status' => 1
        );

        if(!is_null($talent_pattern_ids)){
            $opts = array_merge($opts, array('JobPosting.job_talentpattern_id' => $talent_pattern_ids));
        }

        $data = $this->find('all', array(
            'conditions' => $opts,
            'contain'=>array(
                'Job'=>array(
                    'Group'=>array(
                        'fields'=>array('Group.name', 'Group.pin_number'),
                    ),
                    'fields'=>array('Job.name'),
                ),
                'JobTalentpattern',
            )

        ));

        if(!empty($data)){
            //Grab the users results
            $myTalentPattern = $user['TalentpatternUser'][0];

            foreach($data as $jp_key=>$jobPat){
                $point_dif = 0;
                $count = 1;

                $data[$jp_key]['match']['cat1Total'] = 0;
                $data[$jp_key]['match']['cat2Total'] = 0;
                $data[$jp_key]['match']['overall'] = 0;

                foreach($jobPat['JobTalentpattern'] as $key=>$jobPattern){
                    if($count >= 4 && $count <= 9){
                        $point_dif = abs($myTalentPattern[$key] - $jobPattern);
                        $data[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                        $data[$jp_key]['match']['cat1Total'] += $point_dif;
                    }

                    if($count >= 10 && $count <= 13){
                        $point_dif = abs($myTalentPattern[$key] - $jobPattern);
                        $data[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                        $data[$jp_key]['match']['cat2Total'] += $point_dif;
                    }

                    $count++;
                }

                $data[$jp_key]['match']['cat1Total'] = 100 - $data[$jp_key]['match']['cat1Total'];
                $data[$jp_key]['match']['cat2Total'] = 100 - $data[$jp_key]['match']['cat2Total'];

                $data[$jp_key]['match']['overall'] = round(($data[$jp_key]['match']['cat1Total'] + $data[$jp_key]['match']['cat2Total']) / 2, 2);

                if(!empty($jobPat['JobPosting']['percent_match'])){
                    if($data[$jp_key]['match']['overall'] < $jobPat['JobPosting']['percent_match']){
                        unset($data[$jp_key]);
                    }
                }
            }
        }

        return $data;
    }
}