<?php
App::uses('AppController', 'Controller');
App::uses('CakePdf', 'CakePdf.Pdf');


/**
 * Apps Controller
 *
 */
class JobTitlesController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
    public $scaffold = 'admin';

    public $uses = array(
        'Job',
        'JobQuestion',
        'JobTalentpattern',
        'Group',
        'JobPosting',
        'JobTitle',
        'JobOffer',
        'ApplyJob',
        'User',
        'DetailUser',
        'State'
    );

    //beforeFilter callback
    public function beforeFilter( ) {
        parent::beforeFilter();

        $this->Auth->allow(
            'view_applicant'
        );

        if (isset($this->request->params['prefix'])){
            if ( $this->request->params['prefix'] == 'admin'){
                $role_names = Set::extract( AuthComponent::user(), '/AuthRole/tag' );
                if(!in_array('superAdmin', $role_names )){
                    $this->redirect(array('controller'=>'groups', 'action' => 'index', 'admin'=>true));
                }
            }
        }
    }

    public function index() {

		$job_titles = $this->JobTitle->find('all', array(
			'conditions' => array(
        	),
            'contain'=>array(
				#'Job'=>array()
            ),
        ));

		#pr($job_titles);
		#exit;

        //grab list of states for form
        #$states = $this->State->getListState();
        #$this->set('states', $states);
        $this->set('content', $job_titles);
    }



    public function search($area=null,$id=null, $percent=null){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

        if(!empty($supervisorOf_id) ){
            $parentNode = $this->Group->getPath($supervisorOf_id[0]);

            //[1] is alway sthe lead company id
            $comp_id = $parentNode[1]['Group']['id'];
            //$this->Job->recursive = 3;
            //Get all job postings
            $jobPosting = $this->JobPosting->find('all', array(
                'conditions' => array(
                    'JobPosting.group_id' => $comp_id
                ),
                'contain'=>array(
                    'Job'=>array(

                    ),
                    'Group'=>array(
                        'fields'=>array('Group.group_type_id', 'Group.zip')
                    ),
                    'ApplyJob'=>array(
                        'User'=>array(
                            'DetailUser'=>array(
                                'fields'=>array(
                                    'DetailUser.phone'
                                ),
                            ),
                            'fields'=>array(
                                'User.id',
                                'User.fullname',
                                'User.username',
                            ),
                            'ApplyJob'
                        ),
                    ),
                    'User'=>array(
                        'fields'=>array('User.fullname')
                    )
                )
            ));

            //Get Jobs
            $jobs = $this->Job->find('all', array(
                'conditions' => array(
                    'Job.group_id' => $comp_id
                ),
                'contain' => array(
                ),
                'order'=>array('Job.name ASC')

            ));

            //Get Talent Patterns
            $jobTalentpattern = $this->JobTalentpattern->find('all', array(
                'conditions' => array(
                    'JobTalentpattern.group_id' => $comp_id
                ),
                'contain' => array(
                )
            ));

            //Get questions
            $jobQuestion = $this->JobQuestion->find('list', array(
                'conditions' => array(
                    'JobQuestion.group_id' => $comp_id
                ),
                'contain' => array(
                )
            ));

            //get job post ids
            $jobPostingIds = $this->JobPosting->find('list', array(
                'conditions' => array(
                    'JobPosting.group_id' => $comp_id
                ),
                'contain'=>array(
                ),
                'fields'=>array('JobPosting.id')
            ));

            //get Company Info
            $group_info = $this->Group->find('all', array(
                'conditions' => array(
                    'Group.id' => $comp_id
                ),
                'contain'=>array(
                ),
            ));

            $settings['job_status'] = $this->Job->jobStatusInt();
            $settings['applicant_status'] = $this->Job->applicantStatus();
            $jobSearch['search']['area'] = null;
            $jobSearch['search']['id'] = null;
            $jobSearch['search']['match'] = 75;

            if($group_info[0]['Group']['group_type_id'] >= 1){
                if(!empty($area) && !empty($id)){
                    $jobSearch = $this->JobPosting->find('first', array(
                        'conditions' => array(
                            'JobPosting.id' => $id
                        ),
                        'contain'=>array(
                            'Job'=>array(

                            ),
                            'Group'=>array(
                                'fields'=>array('Group.group_type_id', 'Group.zip')
                            ),
                            'JobTalentpattern',
                            'JobQuestion',
                            'ApplyJob'=>array(
                                'User'=>array(
                                    'DetailUser'=>array(
                                        'fields'=>array(
                                            'DetailUser.phone'
                                        ),
                                    ),
                                    'fields'=>array(
                                        'User.id',
                                        'User.fullname',
                                        'User.username',
                                    ),
                                    'ApplyJob'
                                ),
                            ),
                            'User'=>array(
                                'fields'=>array('User.fullname')
                            )
                        )
                    ));

                    $distance = $area;
                    $group_zip = $jobSearch['Group']['zip'];

                    //search for users within X area
                    if(!empty($group_zip)){
                        //find zip codes in search area
                        $this->loadModel('ZipCode');
                        $searchZipCodes = $this->ZipCode->findArea($group_zip, $distance);

                        //find Users in search area based on zip codes
                        $this->loadModel('Group');
                        $jobSeekers = $this->DetailUser->searchZipCode($searchZipCodes);

                        //At this point if there are job postings we need to create a percentage match
                        foreach($jobSeekers as $jp_key=>$jobSeeker){
                            #pr($user);
                            #exit;
                            $point_dif = 0;
                            $count = 0;

                            $jobSearch['jobSeekers'][$jp_key]['match']['cat1Total'] = 0;
                            $jobSearch['jobSeekers'][$jp_key]['match']['cat2Total'] = 0;

                            foreach($jobSeeker['User']['TalentpatternUser'][0] as $key=>$jobPattern){
                                if($count >= 2 && $count <= 7){
                                    $point_dif = abs($jobSearch['JobTalentpattern'][$key] - $jobSeeker['User']['TalentpatternUser'][0][$key]);

                                    $jobSearch['jobSeekers'][$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                                    $jobSearch['jobSeekers'][$jp_key]['match']['cat1Total'] += $point_dif;
                                }

                                if($count >= 8 && $count <= 11){
                                    $point_dif = abs($jobPosting[0]['JobTalentpattern'][$key] - $jobSeeker['User']['TalentpatternUser'][0][$key]);
                                    $jobSearch['jobSeekers'][$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                                    $jobSearch['jobSeekers'][$jp_key]['match']['cat2Total'] += $point_dif;
                                }

                                $count++;
                            }

                            $jobSearch['jobSeekers'][$jp_key]['match']['cat1Total'] = 100 - $jobSearch['jobSeekers'][$jp_key]['match']['cat1Total'];
                            $jobSearch['jobSeekers'][$jp_key]['match']['cat2Total'] = 100 - $jobSearch['jobSeekers'][$jp_key]['match']['cat2Total'];

                            $jobSearch['jobSeekers'][$jp_key]['match']['overall'] = round(($jobSearch['jobSeekers'][$jp_key]['match']['cat1Total'] + $jobSearch['jobSeekers'][$jp_key]['match']['cat2Total']) / 2, 2);
                            #pr($jobPosting[0]['User']);
                            #exit;
                            if(is_null($percent)){
                                $percent_match = $jobSearch['JobPosting']['percent_match'];
                            }else{
                                $percent_match = $percent;
                            }

                            if($jobSearch['jobSeekers'][$jp_key]['match']['overall'] < $percent_match){
                                unset($jobSearch['jobSeekers'][$jp_key]);
                                //$jobPosting[0]['jobSeekers'][$jp_key]['User'] = $jobSeekers[$jp_key]['User'];
                            }else{
                                $applyJobId = Set::extract( $jobSeekers[$jp_key]['User'], '/ApplyJob/job_posting_id' );

                                if(!in_array($id, $applyJobId)){
                                    $jobSearch['jobSeekers'][$jp_key]['User'] = $jobSeekers[$jp_key]['User'];
                                    $jobSearch['jobSeekers'][$jp_key]['DetailUser'] = $jobSeekers[$jp_key]['DetailUser'];
                                }else{
                                    unset($jobSearch['jobSeekers'][$jp_key]);
                                }
                            }
                            #pr($jobSeekers);
                            #pr($jobPosting[0]['User']);
                            #pr($jobPosting[0]['jobSeekers']);
                            #exit;
                            //unset($users[$jp_key]['JobPosting'],$jobPostings[$jp_key]['JobTalentpattern'], $jobPostings[$jp_key]['JobQuestion']);
                        }

                        if(empty($jobSearch['jobSeekers'])){
                            $jobSearch['jobSeekers']['none'] = array('There are currently no qualified applicants '. $distance .' miles of your location');
                        }
                    }else {
                        $jobSearch['jobSeekers']['none'] = array('A Zip code must be filled out to use this feature');
                    }
                    $jobSearch['search']['area'] = $distance;
                    $jobSearch['search']['id'] = $id;
                    $jobSearch['search']['match'] = $percent;
                }
            }
            #pr($jobPosting[0]['jobSeekers']);
            #exit;
            $this->set( 'jobPosting', $jobPosting );
            $this->set( 'jobSearch', $jobSearch );
            $this->set( 'jobs', $jobs );
            $this->set( 'group_info', $group_info );
            $this->set( 'jobTalentpattern', $jobTalentpattern );
            $this->set( 'jobQuestion', $jobQuestion );
            $this->set( 'settings', $settings );
            $this->set( 'group_id', $comp_id );


            $this->set('breadcrumbs', array(
                array('title'=>'Job Openings', 'link'=>array('controller'=>'jobs', 'action'=>'index', 'member'=>true ) ),
            ));
        }
    }

    public function  home_search(){
        if ($this->request->is('post') || $this->request->is('put')){
            $area = $this->request->data['search']['area'];
            $id = $this->request->data['search']['id'];
            $match = $this->request->data['search']['match'];
        }

        //redirect
        $this->redirect(array('controller'=>'jobs', 'action'=>'index', $area, $id, $match,  'member'=>true, '#' => 'search'));
    }

    public function search_applicant($userId=null, $jobPost=null){
        $applicants = $this->User->find('all', array(
            'conditions' => array(
                'User.id'=>$userId
            ),
            'contain' => array(
                'DetailUser'=>array(
                    'fields'=>array('DetailUser.address', 'DetailUser.city', 'DetailUser.county',
                        'DetailUser.state', 'DetailUser.zip','DetailUser.phone',
                        'DetailUser.mobile','DetailUser.fax', 'DetailUser.img',
                        'DetailUser.uploadDir'),
                ),
                'ApplyJob'=>array(
                    'conditions'=>array(
                        'ApplyJob.job_posting_id' => $jobPost
                    ),
                    'JobPosting'=>array(
                        'Job'=>array(
                            'fields'=>array('Job.name'),
                        ),
                        'JobTalentpattern',

                    ),
                    'JobQuestionAnswer'=>array(
                        'JobQuestionDetail'=>array(
                            'fields'=>array('JobQuestionDetail.question', 'JobQuestionDetail.option' ),
                        ),
                        'fields'=>array('JobQuestionAnswer.answer'),
                    ),
                    'fields'=>array('ApplyJob.id','ApplyJob.updated'),
                ),
                'TalentpatternUser',
                'FileUser',
                'AssignedTest'=>array(
                    'conditions'=>array(
                        'AssignedTest.test_id' => 62
                    ),
                    'fields'=>array('AssignedTest.id'),
                )

            ),
            'fields'=>array( 'User.first_name', 'User.last_name', 'User.username')

        ));

        $jobPosting = $this->JobPosting->find('all', array(
            'conditions' => array(
                'JobPosting.id'=>$jobPost
            ),
            'contain'=>array(
                'JobTalentpattern'
            )
        ));
        $applicants = $applicants[0];
        $this->set('applicants',$applicants);


        $tp['rel_diff'] = round(abs($applicants['TalentpatternUser'][0]['realistic'] - $jobPosting[0]['JobTalentpattern']['realistic'] ), 2 );
        $tp['inv_diff'] = round(abs($applicants['TalentpatternUser'][0]['investigative'] - $jobPosting[0]['JobTalentpattern']['investigative'] ), 2 );
        $tp['con_diff'] = round(abs($applicants['TalentpatternUser'][0]['conventional'] - $jobPosting[0]['JobTalentpattern']['conventional'] ), 2 );
        $tp['ent_diff'] = round(abs($applicants['TalentpatternUser'][0]['enterprising'] - $jobPosting[0]['JobTalentpattern']['enterprising'] ), 2 );
        $tp['soc_diff'] = round(abs($applicants['TalentpatternUser'][0]['social'] - $jobPosting[0]['JobTalentpattern']['social'] ), 2 );
        $tp['art_diff'] = round(abs($applicants['TalentpatternUser'][0]['artistic'] - $jobPosting[0]['JobTalentpattern']['artistic'] ), 2 );
        $tp['total_cat1'] = 100 - ($tp['rel_diff'] + $tp['inv_diff'] + $tp['con_diff'] + $tp['ent_diff'] + $tp['soc_diff'] + $tp['art_diff']);

        $tp['com_diff'] = abs($applicants['TalentpatternUser'][0]['competitor'] - $jobPosting[0]['JobTalentpattern']['competitor'] );
        $tp['comm_diff'] = abs($applicants['TalentpatternUser'][0]['communicator'] - $jobPosting[0]['JobTalentpattern']['communicator'] );
        $tp['coo_diff'] = abs($applicants['TalentpatternUser'][0]['cooperator'] - $jobPosting[0]['JobTalentpattern']['cooperator'] );
        $tp['coor_diff'] = abs($applicants['TalentpatternUser'][0]['coordinator'] - $jobPosting[0]['JobTalentpattern']['coordinator'] );

        $tp['total_cat2'] = 100 - ($tp['com_diff'] + $tp['comm_diff'] + $tp['coo_diff'] + $tp['coor_diff']);

        $tp['total'] = round(($tp['total_cat1'] + $tp['total_cat2']) / 2, 2 );
        $this->set('tp',$tp);
        $this->set('jobPosting',$jobPosting);

        $this->layout = 'blank_nojs';
        #pr($applicants);
        #pr($userId);
        #pr($jobPost);
        #exit;
    }

    public function view_notes($id=null){
        $jobPostInfo = $this->ApplyJob->find('all', array(
            'conditions' => array(
                'ApplyJob.id' => $id
            ),
            'contain' => array(
            ),
            'fields'=>array('ApplyJob.notes')
        ));

        $title = "View Applicant Notes";
        $this->set( 'title', $title );
        $this->set( 'info', $jobPostInfo );
        $this->layout = 'blank_nojs';
    }

    public function view_applicant($applyId=null){
        $item = $this->ApplyJob->find('first', array(
            'conditions' => array(
                'ApplyJob.id'=>$applyId
            ),
            'contain'=>array(
                'User'=>array(
                    'DetailUser'=>array(
                        'fields'=>array('DetailUser.address', 'DetailUser.city', 'DetailUser.county',
                            'DetailUser.state', 'DetailUser.zip','DetailUser.phone',
                            'DetailUser.mobile','DetailUser.fax', 'DetailUser.img',
                            'DetailUser.uploadDir'
                        ),
                    ),
                    'TalentpatternUser',
                    'FileUser',
                    'AssignedTest'=>array(
                        'conditions'=>array(
                            'AssignedTest.test_id' => 62
                        ),
                        'fields'=>array('AssignedTest.id'),
                    ),
                    'fields'=>array( 'User.first_name', 'User.last_name', 'User.username', 'User.pin_number')
                ),
                'JobPosting'=>array(
                    'Job'=>array(
                        'fields'=>array('Job.name'),
                    ),
                    'JobTalentpattern',
                    'JobQuestion'=>array(
                        'JobQuestionDetail'=>array(
                            'JobQuestionAnswer'=>array(
                                'conditions'=>array(
                                    'JobQuestionAnswer.apply_job_id'=>$applyId
                                )
                                //'fields'=>array('JobQuestionAnswer.answer'),
                            ),
                            'fields'=>array('JobQuestionDetail.question', 'JobQuestionDetail.option' ),
                        ),

                    ),
                ),

            )
        ));
        pr($item);
        exit;
        //Set all info to carry
        $this->set('item',$item);

        $tp['rel_diff'] = round(abs($item['User']['TalentpatternUser'][0]['realistic'] - $item['JobPosting']['JobTalentpattern']['realistic'] ), 2 );
        $tp['inv_diff'] = round(abs($item['User']['TalentpatternUser'][0]['investigative'] - $item['JobPosting']['JobTalentpattern']['investigative'] ), 2 );
        $tp['con_diff'] = round(abs($item['User']['TalentpatternUser'][0]['conventional'] - $item['JobPosting']['JobTalentpattern']['conventional'] ), 2 );
        $tp['ent_diff'] = round(abs($item['User']['TalentpatternUser'][0]['enterprising'] - $item['JobPosting']['JobTalentpattern']['enterprising'] ), 2 );
        $tp['soc_diff'] = round(abs($item['User']['TalentpatternUser'][0]['social'] - $item['JobPosting']['JobTalentpattern']['social'] ), 2 );
        $tp['art_diff'] = round(abs($item['User']['TalentpatternUser'][0]['artistic'] - $item['JobPosting']['JobTalentpattern']['artistic'] ), 2 );
        $tp['total_cat1'] = 100 - ($tp['rel_diff'] + $tp['inv_diff'] + $tp['con_diff'] + $tp['ent_diff'] + $tp['soc_diff'] + $tp['art_diff']);

        $tp['com_diff'] = abs($item['User']['TalentpatternUser'][0]['competitor'] - $item['JobPosting']['JobTalentpattern']['competitor'] );
        $tp['comm_diff'] = abs($item['User']['TalentpatternUser'][0]['communicator'] - $item['JobPosting']['JobTalentpattern']['communicator'] );
        $tp['coo_diff'] = abs($item['User']['TalentpatternUser'][0]['cooperator'] - $item['JobPosting']['JobTalentpattern']['cooperator'] );
        $tp['coor_diff'] = abs($item['User']['TalentpatternUser'][0]['coordinator'] - $item['JobPosting']['JobTalentpattern']['coordinator'] );

        $tp['total_cat2'] = 100 - ($tp['com_diff'] + $tp['comm_diff'] + $tp['coo_diff'] + $tp['coor_diff']);

        $tp['total'] = round(($tp['total_cat1'] + $tp['total_cat2']) / 2, 2 );

        //Set Talent Pattern
        $this->set('tp',$tp);

        //Set the settings
        $settings['applicant_status'] = $this->Job->applicantFullStatus();
        $this->set('settings',$settings);

        $this->layout = 'blank_nojs';
        #pr($applicants);
        #pr($userId);
        #pr($jobPost);
        #exit;
    }

    //add new job title //used on accoun t settings and job posting
    //redirect used if account settings page
    public function add($group_id=null, $redirect=null){

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Job']['group_id'] = AuthComponent::user('parent_group_ids.1');

            if ($this->Job->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Job Title Has Been saved'),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Job title was not saved. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'jobs','action' => 'index', 'member'=>true));

        }
        $this->set( 'group_id', $group_id );
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Job Titles', 'link'=>array('controller'=>'jobs', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'New Job Title', 'link'=>array('controller'=>'jobs', 'action'=>'add', 'member'=>true ) ),
        ));
    }

    public function edit($id=null, $redirect=null){
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->Job->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Job Title Has Been saved'),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Job title was not saved. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'jobs','action' => 'index', 'member'=>true));

        }

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

        if(!empty($supervisorOf_id) ){
            //Get Jobs
            $jobs = $this->Job->find('first', array(
                'conditions' => array(
                    'Job.id' => $id
                ),
                'contain' => array(
                )

            ));

            $this->set( 'jobs', $jobs );
        }

        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Job Titles', 'link'=>array('controller'=>'jobs', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Edit Job Title', 'link'=>array('controller'=>'jobs', 'action'=>'edit', $id, 'member'=>true ) ),
        ));
    }

    //delete job title //used on account settings and job posting
    //redirect used if account settings page
    public function delete($id=null){
        $this->Job->id = $id;

        if($this->Job->delete()){

            $this->JobPosting->deleteAll(array('JobPosting.job_id' => $id), true);

            $this->Session->setFlash(
                __('Deletetion Successful'),
                'alert-box',
                array('class'=>'alert-success')
            );
        } else {
            $this->Session->setFlash(
                __('There Was An Error! Please Try Again'),
                'alert-box',
                array('class'=>'alert-danger')
            );
        }

        return $this->redirect(array('controller'=>'jobs','action' => 'index', 'member'=>true));
    }

    public function confirm($id=null, $app=null){
        //Get Jobs
        $jobs = $this->JobPosting->find('all', array(
            'conditions' => array(
                'JobPosting.job_id' => $id
            ),
            'contain' => array(
            )
        ));
        $settings['job_status'] = $this->Job->jobStatusInt();

        $this->set( 'settings', $settings );

        $layout = '<h2 class="text-danger">Are you Sure You Want To Delete?</h2><h3>This will delete all Job Openings that use this job title.</h3>';
        $this->set('id', $id );
        $this->set('content', $layout );
        $this->set('jobs', $jobs );

    }

    public function updateApplicant($return_id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ApplyJob->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Applicant Has Been updated'),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Applicant was not updated. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'jobs','action' => 'view_applicants', 'member'=>true, $return_id));
        }
    }

    public function admin_add($group_id=null){

        if ($this->request->is('post') || $this->request->is('put')) {
            $group_id = $this->request->data['Job']['group_id'];
            if ($this->Job->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Job Title Has Been saved'),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Job title was not saved. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'groups','action' => 'byGroup', $group_id, 'admin'=>true, '#'=>'jobTitles'));
        }

        $title = "Create New job";
        $this->set( 'group_id', $group_id );
        $this->set( 'title', $title );
        $this->layout = 'blank_nojs';
    }

    public function admin_edit($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $group_id = $this->request->data['Job']['group_id'];

            if ($this->Job->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Job Title Has Been saved'),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Job title was not saved. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'groups','action' => 'byGroup', $group_id, 'admin'=>true, '#'=>'jobTitles'));
        }

        //Get Jobs
        $jobs = $this->Job->find('first', array(
            'conditions' => array(
                'Job.id' => $id
            ),
            'contain' => array(
            )
        ));

        $title = 'Edit: '.$jobs['Job']['name'];

        $this->set( 'jobs', $jobs );
        $this->set( 'id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';
    }
}
