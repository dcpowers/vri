<?php
App::uses('AppController', 'Controller');
App::uses('CakePdf', 'CakePdf.Pdf');

/**
 * Apps Controller
 *
 */
class JobPostingsController extends AppController {

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
        'GroupCredit',
        'JobPosting',
        'ApplyJob',
        'ExemptJob',
        'EmploymentType',
        'Collaborater',
        'User',
        'TalentpatternUser',
        'Account'
    );

    //beforeFilter callback
    public function beforeFilter( ) {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Job Openings');
    }

    public function index($area=null,$id=null, $percent=null){
        $jobPosting = $this->JobPosting->find('all', array(
        	'conditions' => array(
            	#'JobPosting.group_id' => $group_ids
            ),
            'contain'=>array(
            	'Job'=>array(

                ),
                'Group'=>array(
                	#'fields'=>array('Group.group_type_id', 'Group.zip', 'Group.pin_number')
                ),
                'ApplyJob'=>array(
                	'User'=>array(
                    	'fields'=>array(
                        	'User.id',
                            'User.first_name',
                            'User.username',
                        ),
                    ),
                ),
                'User'=>array(
                	'fields'=>array('User.first_name')
                )
            )
        ));

		#pr($jobPosting);
		#exit;
        if ($this->request->is('requested')) {
        	return $jobPosting;
        }

        $settings['job_status'] = $this->JobPosting->jobStatusInt();
        $settings['applicant_status'] = $this->JobPosting->applicantStatus();

		#pr($settings);
		#exit;
        $this->set( 'jobPosting', $jobPosting );
        $this->set( 'settings', $settings );
    }

    public function confirm($id=null, $app=null){
        $layout = '<h2 class="text-danger">Are you Sure You Want To Delete?</h2>';
        $this->set('id', $id );
        $this->set('content', $layout );
    }

    public function delete($id=null){
        $this->JobPosting->id = $id;

        if($this->JobPosting->delete($id)){

            $this->Collaborater->deleteAll(array('Collaborater.job_posting_id' => $id), false);
            $this->ApplyJob->deleteAll(array('ApplyJob.job_posting_id' => $id), false);
            $this->ExemptJob->deleteAll(array('ExemptJob.job_posting_id' => $id), false);

            $this->Session->setFlash(
                __('Job Opening Deleted'),
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

        return $this->redirect(array('controller'=>'JobPostings','action' => 'index', 'member'=>true));
    }

    public function add(){
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['JobPosting']['posted_by'] = AuthComponent::user('id');

			if(!empty($this->request->data['JobPosting']['active_till_date'])){
				$this->request->data['JobPosting']['active_till_date'] = date('Y-m-d', strtotime($this->request->data['JobPosting']['active_till_date']));
            }

            $error = false;
            $validationErrors = array();
            pr($this->request->data);
            $this->JobPosting->validate = $this->JobPosting->validationSets['newListing'];
            $this->JobPosting->set($this->request->data['JobPosting']);

            if(!$this->JobPosting->validates()){
                $validationErrors['JobPosting'] = $this->JobPosting->validationErrors;
                $error = true;
            }

			pr($this->JobPosting->validates());
			pr($validationErrors);
            pr($this->request->data);
			exit;
            if($error == false){
                if ($this->JobPosting->saveall($this->request->data)) {
					//Audit::log('Group record edited', $this->request->data );
                    $this->Session->setFlash(
                    	__('Job Posting Has Been saved'),
                        'alert-box',
                        array('class'=>'alert-success')
                    );
                } else {
                	$this->Session->setFlash(
                    	__('There Was An Error! Job Posting information was not saved. Please try again.'),
                        'alert-box',
                        array('class'=>'alert-danger')
                    );
                }

            }else{
                $this->Session->setFlash(
                    __('Information not save! Please see errors below'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
                $this->set( compact( 'validationErrors' ) );
            }

			return $this->redirect(array('controller'=>'JobPostings','action' => 'index'));
        }

        //Get Jobs
        $jobs = $this->Job->find('list', array(
        	'conditions' => array(
            	#'Job.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));

        //Get Talent Patterns
        $jobTalentpattern = $this->JobTalentpattern->find('list', array(
        	'conditions' => array(
            	#'JobTalentpattern.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));

        //Get Questions
        $jobQuestion = $this->JobQuestion->find('list', array(
        	'conditions' => array(
            	#'JobQuestion.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));

        //Get Percent Match options
        $group_list = $this->Account->find('list', array(
        	'conditions' => array(
            	#'Account.zip !=' => '',
                #'Account.city !=' => '',
                #'Account.state !=' => '',
            ),
            'contain' => array(
            ),
        ));

        $percent_match_options = 'true';

        $settings['status'] = $this->Job->jobStatusInt();
        $settings['salaryTypes'] = $this->Job->salaryTypesInt();
        $employmentTypes = $this->EmploymentType->pick_list();

        $this->set( 'locations', $group_list );
        $this->set( 'jobs', $jobs );
        $this->set( 'percent_match_options', $percent_match_options );
        $this->set( 'jobTalentpattern', $jobTalentpattern );
        $this->set( 'jobQuestion', $jobQuestion );
        $this->set( 'settings', $settings );

        $this->set( 'employmentTypes', $employmentTypes );

        $this->set( 'today', date('m-d-Y', strtotime('now')) );
    }

    public function edit($id=null){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );

        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
            $group_ids = $this->Group->getChildren($group_id);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['JobPosting']['posted_by'] = AuthComponent::user('id');

            if(!empty($this->request->data['JobPosting']['active_till_date'])){
                $pieces = explode("-", $this->request->data['JobPosting']['active_till_date']);

                $day = $pieces[1];
                $month = $pieces[0];
                $year = $pieces[2];

                $this->request->data['JobPosting']['active_till_date'] = $year.'-'.$month.'-'.$day;
            }

            $error = false;
            $validationErrors = array();

            $this->JobPosting->validate = $this->JobPosting->validationSets['newListing'];
            $this->JobPosting->set($this->request->data['JobPosting']);

            if(!$this->JobPosting->validates()){
                $validationErrors['JobPosting'] = $this->JobPosting->validationErrors;
                $error = true;
            }


            if($error == false){
                if ($this->JobPosting->saveall($this->request->data)) {
                    //Audit::log('Group record edited', $this->request->data );
                    $this->Session->setFlash(
                        __('Job Opening Has Been saved'),
                        'alert-box',
                        array('class'=>'alert-success')
                    );

                    return $this->redirect(array('controller'=>'JobPostings','action' => 'view', 'member'=>true, $id));

                } else {
                    $this->Session->setFlash(
                        __('There Was An Error! Information was not saved. Please try again.'),
                        'alert-box',
                        array('class'=>'alert-danger')
                    );
                }
            }else{
                $this->Session->setFlash(
                    __('Information not save! Please see errors below'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
                $this->set( compact( 'validationErrors' ) );
            }
        }

		$jobPosting = $this->JobPosting->find('first', array(
        	'conditions' => array(
            	'JobPosting.id' => $id
            ),
        ));
        $this->set( 'jobPosting', $jobPosting );

        //Get Jobs
        $jobs = $this->Job->find('list', array(
        	'conditions' => array(
            	#'Job.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));
        $this->set( 'jobs', $jobs );

        //Get Talent Patterns
        $jobTalentpattern = $this->JobTalentpattern->find('list', array(
        	'conditions' => array(
            	'JobTalentpattern.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));
        $this->set( 'jobTalentpattern', $jobTalentpattern );

        //Get Questions
        $jobQuestion = $this->JobQuestion->find('list', array(
        	'conditions' => array(
            	#'JobQuestion.group_id' => $group_ids
            ),
            'contain' => array(
            )
        ));
        $this->set( 'jobQuestion', $jobQuestion );

        //Get Percent Match options
        $group_upgrade = $this->Group->find('all', array(
        	'conditions' => array(
            	'Group.id' => $group_ids
            ),
            'contain' => array(
            ),
            'fields'=>array('Group.id', 'Group.name', 'Group.group_type_id', 'Group.zip')
        ));

        $locations= array();
        foreach($group_upgrade as $item){
        	if(!empty($item['Group']['zip'])){
            	$locations[$item['Group']['id']] = $item['Group']['name'];
            }
        }

        $this->set( 'locations', $locations );

        if($group_upgrade[0]['Group']['group_type_id'] >= 1){
        	$percent_match_options = 'true';
        }else{
        	$percent_match_options = 'false';
        }
        $this->set( 'percent_match_options', $percent_match_options );

        $settings['status'] = $this->Job->jobStatusInt();
        $settings['salaryTypes'] = $this->Job->salaryTypesInt();
        $this->set( 'settings', $settings );

        $employmentTypes = $this->EmploymentType->pick_list();
        $this->set( 'employmentTypes', $employmentTypes );

        $this->set( 'id', $id );

        $today = date('m-d-Y', strtotime('now'));

        $this->set( 'today', $today );
	}

    public function search($job_posting_id=null){

        $distance = (empty($this->request->data['search']['area'])) ? 20 : $this->request->data['search']['area'] ;
        $id = (is_null($job_posting_id)) ? null : $job_posting_id ;
        $id = (empty($this->request->data['search']['id'])) ? $id : $this->request->data['search']['id'] ;

        $this->set( 'distance', $distance );
        $this->set( 'id', $id );

        if(isset($id)){
            //Grab job post info
            $jobPost = $this->JobPosting->retreiveJobPostingDetails($id);
            $this->set( 'jobPost', $jobPost );

            $searchZip = $jobPost['Group']['zip'];

            $parent = $this->Group->getPath($jobPost['Group']['id']);

            if($parent[1]['Group']['group_type_id'] >= 1){
                //search for users within X area
                if(!empty($searchZip)){
                    //find zip codes in search area
                    $this->loadModel('ZipCode');
                    $searchZipCodes = $this->ZipCode->findArea($searchZip, $distance);

                    $i1 = (!empty($jobPost['JobTalentpattern']['interest1'])) ? $jobPost['JobTalentpattern']['interest1'] : null ;
                    $i2 = (!empty($jobPost['JobTalentpattern']['interest2'])) ? $jobPost['JobTalentpattern']['interest2'] : null ;

                    $b1 = (!empty($jobPost['JobTalentpattern']['behavior1'])) ? $jobPost['JobTalentpattern']['behavior1'] : null ;
                    $b2 = (!empty($jobPost['JobTalentpattern']['behavior2'])) ? $jobPost['JobTalentpattern']['behavior2'] : null ;

                    //find Users based on zip/interest/behavior
                    $exempt_ids = $this->ExemptJob->pick_list($id);
                    $user_ids = $this->TalentpatternUser->pick_list($i1, $i2, $b1, $b2);
                    $user_ids = $this->GroupMembership->pick_list($user_ids);
                    $user_ids = $this->DetailUser->zipCode_picklist($searchZipCodes, $user_ids, $exempt_ids);

                    $users = $this->DetailUser->user_info($user_ids, $id);

                    //At this point if there are job postings we need to create a percentage match
                    foreach($users as $jp_key=>$user){
                        #pr($user);
                        #exit;
                        $point_dif = 0;
                        $count = 0;

                        $users[$jp_key]['match']['cat1Total'] = 0;
                        $users[$jp_key]['match']['cat2Total'] = 0;

                        foreach($user['User']['TalentpatternUser'][0] as $key=>$jobPattern){
                            if($count >= 2 && $count <= 7){
                                $point_dif = abs($jobPost['JobTalentpattern'][$key] - $user['User']['TalentpatternUser'][0][$key]);

                                $users[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                                $users[$jp_key]['match']['cat1Total'] += $point_dif;
                            }

                            if($count >= 8 && $count <= 11){
                                $point_dif = abs($jobPost['JobTalentpattern'][$key] - $user['User']['TalentpatternUser'][0][$key]);
                                $users[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                                $users[$jp_key]['match']['cat2Total'] += $point_dif;
                            }
                            $count++;

                        }

                        $users[$jp_key]['match']['cat1Total'] = 100 - $users[$jp_key]['match']['cat1Total'];
                        $users[$jp_key]['match']['cat2Total'] = 100 - $users[$jp_key]['match']['cat2Total'];

                        $users[$jp_key]['match']['overall'] = round(($users[$jp_key]['match']['cat1Total'] + $users[$jp_key]['match']['cat2Total']) / 2, 2);

                        if(empty($this->request->data['search']['match'])){
                            $percent_match = $jobPost['JobPosting']['percent_match'];
                        }else{
                            $percent_match = $this->request->data['search']['match'];
                        }

                        $this->set( 'percent_match', $percent_match );

                        if($users[$jp_key]['match']['overall'] < $percent_match){
                            unset($users[$jp_key]);

                        }else{
                            $applyJobId = Set::extract( $users[$jp_key]['User'], '/ApplyJob/job_posting_id' );

                            if(!in_array($job_posting_id, $applyJobId)){
                                $jobPost['jobSeekers'][$jp_key]['User'] = $users[$jp_key]['User'];
                                $jobPost['jobSeekers'][$jp_key]['DetailUser'] = $users[$jp_key]['DetailUser'];
                            }else{
                                unset($jobPost['jobSeekers'][$jp_key]);
                            }
                        }
                    }

                    if(empty($users)){
                        $users['none'] = array('There are currently no job seekers '. $distance .' miles of your location');
                        $this->set( 'percent_match', 75 );
                    }
                }else {
                    $users['none'] = array('A Zip code must be filled out to use this feature');
                }
            }else{
                $users['none'] = array('Upgrade today to use this feature');
            }

            if(empty($users['none'])){
                $price = array();
                foreach($users as $key=> $value) {
                    $price[] = $value['match']['overall'];
                }

                array_multisort($price, SORT_DESC, $users);
            }

            $this->set( 'users', $users );
        }else{
            $this->set( 'percent_match', '75' );
            $this->set( 'jobPost', null );

            $users['none'] = array('Please Select a Job Opening');

            $this->set( 'users', $users );
        }

        //Grab all job postings for that location
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );

        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            $list = array();
            //get children ids of the super id
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
            $group_ids = $this->Group->getChildren($group_id);

            $jobPosting = $this->JobPosting->find('all', array(
                'conditions' => array(
                    'JobPosting.group_id' => $group_ids
                ),
                'contain'=>array(
                    'Job'=>array(

                    ),
                    'Group'=>array(
                        'State',
                        'fields'=>array('Group.city', 'Group.state')
                    )
                )
            ));

            foreach($jobPosting as $item){
                $list[$item['JobPosting']['id']] = $item['Job']['name'].' ( '.$item['Group']['city'].', '.$item['Group']['State']['state_name'].' )';
            }

            $this->set( 'pick_list', $list );

            if ($this->request->is('requested')) {
                return $list;
            }
        }
    }

    public function compareOrder($a, $b){
        return $a['match']['overall'] - $b['match']['overall'];
    }

    public function distants_search($id=null, $area=null){
        //Grab company zip code
        $comp_id = AuthComponent::user('parent_group_ids.1');

        $group_info = $this->Group->find('first', array(
            'conditions' => array(
                'Group.id' => $comp_id
            ),
            'contain' => array(
            ),
            'fields'=>array('Group.id', 'Group.zip', 'Group.group_type_id')
        ));

        $distance = (is_null($area)) ? 10 : $area ;
        //Find Job Postings from companies
        $jobPost = $this->JobPosting->retreiveJobPostingDetails($id);

        if($group_info['Group']['group_type_id'] >= 1){
            $group_zip = $group_info['Group']['zip'];

            //search for users within X area
            if(!empty($group_zip)){
                $distance = (is_null($area)) ? 10 : $area ;

                //find zip codes in search area
                $this->loadModel('ZipCode');
                $searchZipCodes = $this->ZipCode->findArea($group_zip, $distance);

                //find Users in search area based on zip codes
                $this->loadModel('Group');
                $users = $this->DetailUser->searchZipCode($searchZipCodes);

                #pr($users);
                #exit;
                //At this point if there are job postings we need to create a percentage match

                foreach($users as $jp_key=>$user){
                    #pr($user);
                    #exit;
                    $point_dif = 0;
                    $count = 0;

                    $users[$jp_key]['match']['cat1Total'] = 0;
                    $users[$jp_key]['match']['cat2Total'] = 0;

                    foreach($user['User']['TalentpatternUser'][0] as $key=>$jobPattern){
                        if($count >= 2 && $count <= 7){
                            $point_dif = abs($jobPost[0]['JobTalentpattern'][$key] - $user['User']['TalentpatternUser'][0][$key]);

                            $users[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                            $users[$jp_key]['match']['cat1Total'] += $point_dif;
                        }

                        if($count >= 8 && $count <= 11){
                            $point_dif = abs($jobPost[0]['JobTalentpattern'][$key] - $user['User']['TalentpatternUser'][0][$key]);
                            $users[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                            $users[$jp_key]['match']['cat2Total'] += $point_dif;
                        }
                        $count++;

                    }

                    $users[$jp_key]['match']['cat1Total'] = 100 - $users[$jp_key]['match']['cat1Total'];
                    $users[$jp_key]['match']['cat2Total'] = 100 - $users[$jp_key]['match']['cat2Total'];

                    $users[$jp_key]['match']['overall'] = round(($users[$jp_key]['match']['cat1Total'] + $users[$jp_key]['match']['cat2Total']) / 2, 2);
                    #pr($jobPost);
                    #exit;
                    if($users[$jp_key]['match']['overall'] < $jobPost[0]['JobPosting']['percent_match']){
                        unset($users[$jp_key]);
                    }

                    //unset($users[$jp_key]['JobPosting'],$jobPostings[$jp_key]['JobTalentpattern'], $jobPostings[$jp_key]['JobQuestion']);
                }

                if(empty($users)){
                    $users['none'] = array('There are currently no qualified applicants '. $distance .' miles of your location');
                }
            }else {
                $users['none'] = array('A Zip code must be filled out to use this feature');
            }
        }else{
            $users['none'] = array('Upgrade today to use this feature');
        }

        $this->set( 'distance', $distance );
        $this->set( 'jobPost', $jobPost );
        $this->set( 'users', $users );

    }

    public function view($id=null){

        $applicants = $this->ApplyJob->find('all', array(
            'conditions' => array(
                'ApplyJob.job_posting_id' => $id
            ),
            'contain' => array(
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>1
                    ),
                    #'DetailUser'=>array(
                    #    'fields'=>array(
                    #        'DetailUser.phone'
                    #    ),
                    #),
                    'fields'=>array('User.id', 'User.first_name', 'User.last_name', 'User.username',),
                ),

            ),
            'order'=>array('ApplyJob.created DESC'),
        ));

        #pr($applicants);
        #exit;
        $jobInfo = $this->JobPosting->find('first', array(
            'conditions' => array(
                'JobPosting.id' => $id
            ),
            'contain' => array(
                'Job'=>array(
                    'fields'=>array('Job.name')
                ),
                'User'=>array(
                    'fields'=>array('User.first_name', 'User.last_name', 'User.id')
                ),
                'EmploymentType',
                'SalaryType',
                'Account'=>array(
                    'State',
                    'fields'=>array('Account.city', 'Account.state')
                ),
            ),
        ));

        $this->set( 'jobInfo', $jobInfo );

        if(empty($applicants)){
            $applicants['none'] = "No Applicants Found";
        }

        $settings['job_status'] = $this->Job->jobStatusInt();
        $settings['applicant_status'] = $this->Job->applicantStatus();

        $this->set( 'applicants', $applicants );
        $this->set( 'settings', $settings );
        $this->set( 'id', $id );


        if($jobInfo['JobPosting']['posted_by'] == AuthComponent::user('id')){
            $link = array('controller'=>'JobPostings', 'action'=>'index', 'member'=>true);
        }else{
            $link = '#';
        }

        $this->set('breadcrumbs', array(
            array('title'=>'Job Openings', 'link'=>$link),
            array('title'=>'View: '.$jobInfo['Job']['name'], 'link'=>array('controller'=>'JobPostings', 'action'=>'view', $id, 'member'=>true) ),
        ));

    }

    public function view_jobs($id=null){
    }

    public function view_applicantdetail($id=null){

        $applicants = $this->ApplyJob->find('all', array(
            'conditions' => array(
                'ApplyJob.job_posting_id' => $id
            ),
            'contain' => array(
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>1
                    ),
                    'ApplyJob'=>array(
                        'JobQuestionAnswer'=>array(
                            'JobQuestionDetail'
                        ),
                    ),
                    'TalentpatternUser',

                    'fields'=>array('User.id', 'User.first_name', 'User.last_name', 'User.username',),
                ),

                'JobPosting'=>array(
                    'Job'=>array(
                        'fields'=>array('Job.name'),
                    ),
                    'JobTalentpattern',
                    'JobQuestion'=>array(
                        'JobQuestionDetail'=>array(

                        ),

                    ),
                ),


            ),

        ));
     }

    public function collaboraters($id = null){
        if ($this->request->is('post') || $this->request->is('put')) {
            if(isset($this->request->data['user_id'][0])){
                unset($this->request->data['user_id'][0]);
            }
            $c = 0;

            $this->Collaborater->deleteAll(array('Collaborater.job_posting_id' => $this->request->data['JobPosting']['id'],), false);
            if(!empty($this->request->data['user_id'])){
                foreach($this->request->data['user_id'] as $p){
                    $this->request->data['Collaboraters'][$c]['user_id'] = $p;
                    $this->request->data['Collaboraters'][$c]['job_posting_id'] = $this->request->data['JobPosting']['id'];
                    $c++;
                }
            }else{
                $this->Session->setFlash(
                    __('Collaboraters Have Been Updated'),
                    'alert-box',
                    array('class'=>'alert-success')
                );

                return $this->redirect(array('controller'=>'JobPostings','action' => 'view', 'member'=>true, $id));
            }

            if ($this->Collaborater->saveall($this->request->data['Collaboraters'])) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Collaboraters Have Been Updated'),
                    'alert-box',
                    array('class'=>'alert-success')
                );

                return $this->redirect(array('controller'=>'JobPostings','action' => 'view', 'member'=>true, $id));

            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Information was not saved. Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }
        }

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );

        if(!empty($supervisorOf_id) || in_array(4,$role_ids) ){
            //get children ids of the super id
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
            $group_ids = $this->Group->getChildren($group_id);
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);

            $search_ids = array();
            foreach($active_user_ids as $key=>$activeId){
                $search_ids[$key] = $activeId['pro_users']['id'];
            }

            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$search_ids,
                ),
                'contain' => array(

                ),
                'fields'=>array('User.id', 'User.fullname')
            ));

            if(empty($users)){ $users = "No Users Found"; }

            $this->set( 'userList', $users );

            $jobInfo = $this->JobPosting->find('first', array(
                'conditions' => array(
                    'JobPosting.id' => $id
                ),
                'contain' => array(
                    'User'=>array(
                        'fields'=>array('User.id', 'User.fullname')
                    ),
                ),

            ));

            $info = $this->Collaborater->find('all', array(
                'conditions' => array(
                    'Collaborater.job_posting_id' => $id
                ),
                'contain' => array(
                    'User'=>array(
                        'fields'=>array('User.id', 'User.fullname')
                    ),
                ),
            ));

            $collaboraters[$jobInfo['User']['id']] = $jobInfo['User']['fullname'];
            $active[] = $jobInfo['User']['id'];

            foreach($info as $c){
                $collaboraters[$c['User']['id']] = $c['User']['fullname'];
                $active[] = $c['User']['id'];
            }

            $this->set( 'active', $active);
            $this->set( 'collaboraters', $collaboraters );
            $this->set( 'id', $id );
        }
    }

    public function collaborater_view(){
        $info = $this->Collaborater->find('all', array(
            'conditions' => array(
                'Collaborater.user_id' => AuthComponent::user('id')
            ),
            'contain' => array(
                'JobPosting'=>array(
                    'Job'=>array(
                        'fields'=>array('Job.id', 'Job.name'),
                        'order'=>array('Job.name'),
                    ),
                    'ApplyJob'=>array(
                        'fields'=>array('ApplyJob.id', 'ApplyJob.status')
                    ),
                    'fields'=>array('JobPosting.id', 'JobPosting.job_id')
                ),
            ),
        ));

        $price = array();

        foreach($info as $key=> $value) {
            $price[] = $value['JobPosting']['Job']['name'];
        }

        array_multisort($price, SORT_ASC, $info);

        if ($this->request->is('requested')) {
            return $info;
        }
    }

    public function user_search(){
        $user = $this->User->getUserInfo();
        //Search for jobs

        $apply_job_ids = Set::extract( $user, '/ApplyJob/job_posting_id' );
        $exempt_job_ids = Set::extract( $user, '/ExemptJob/job_posting_id' );

        //Do Not search For these jobs Again
        $exempt_job_ids = array_merge($exempt_job_ids, $apply_job_ids);

        $distance = (is_null($area)) ? 20 : $area ;

        $jobPostings = array();

        if(!empty($user['DetailUser']['zip'])){
            $zip = $user['DetailUser']['zip'];

            //find zip codes in search area
            $searchZipCodes = $this->ZipCode->findArea($zip, $distance);

            //find companies in search area based on zip codes
            $group_ids = $this->Group->searchZipCode($searchZipCodes);

            $i1 = (!empty($user['TalentpatternUser'][0]['interest1'])) ? $user['TalentpatternUser'][0]['interest1'] : null ;
            $i2 = (!empty($user['TalentpatternUser'][0]['interest2'])) ? $user['TalentpatternUser'][0]['interest2'] : null ;

            $b1 = (!empty($user['TalentpatternUser'][0]['behavior1'])) ? $user['TalentpatternUser'][0]['behavior1'] : null ;
            $b2 = (!empty($user['TalentpatternUser'][0]['behavior2'])) ? $user['TalentpatternUser'][0]['behavior2'] : null ;

            $talent_pattern_ids = $this->JobTalentpattern->pick_list($i1, $i2, $b1, $b2);

            //Find Job Postings from companies
            $jobPostings = $this->JobPosting->retreiveJobPostings($group_ids, $talent_pattern_ids, $exempt_job_ids);

            //At this point if there are job postings we need to create a percentage match
            if(!empty($jobPostings)){

                //Grab the users results
                $myTalentPattern = $user['TalentpatternUser'][0];

                foreach($jobPostings as $jp_key=>$jobPat){
                    $point_dif = 0;
                    $count = 1;
                    $jobPostings[$jp_key]['match']['cat1Total'] = 0;
                    $jobPostings[$jp_key]['match']['cat2Total'] = 0;
                    $jobPostings[$jp_key]['match']['overall'] = 0;

                    foreach($jobPat['JobTalentpattern'] as $key=>$jobPattern){
                        if($count >= 4 && $count <= 9){
                            $point_dif = abs($myTalentPattern[$key] - $jobPattern);
                            $jobPostings[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                            $jobPostings[$jp_key]['match']['cat1Total'] += $point_dif;
                        }

                        if($count >= 10 && $count <= 13){
                            $point_dif = abs($myTalentPattern[$key] - $jobPattern);
                            $jobPostings[$jp_key]['match'][$key] = round(100 - $point_dif, 2);
                            $jobPostings[$jp_key]['match']['cat2Total'] += $point_dif;
                        }

                        $count++;
                    }

                    $jobPostings[$jp_key]['match']['cat1Total'] = 100 - $jobPostings[$jp_key]['match']['cat1Total'];
                    $jobPostings[$jp_key]['match']['cat2Total'] = 100 - $jobPostings[$jp_key]['match']['cat2Total'];

                    $jobPostings[$jp_key]['match']['overall'] = round(($jobPostings[$jp_key]['match']['cat1Total'] + $jobPostings[$jp_key]['match']['cat2Total']) / 2, 2);

                    if(!empty($jobPat['JobPosting']['percent_match'])){
                        if($jobPostings[$jp_key]['match']['overall'] < $jobPat['JobPosting']['percent_match']){
                            unset($jobPostings[$jp_key]);
                        }
                    }
                }
            }
        }

        if ($this->request->is('requested')) {
            return $jobPostings;
        }

        $this->set( 'jobPostings', $jobPostings );
        $this->set( 'distance', $distance );
    }

    public function dashboard_search($area=null, $type=null){
        $distance = (is_null($area)) ? 20 : $area ;

        //Grab all user info
        $user = $this->User->getUserInfo();
        unset($user['GroupMembership'], $user['AssignedTest'], $user['AssignedTraining'], $user['FileUser']);

        //Do Not search For these jobs
        $apply_job_ids = Set::extract( $user, '/ApplyJob/job_posting_id' );
        $exempt_job_ids = Set::extract( $user, '/ExemptJob/job_posting_id' );

        $exempt_job_ids = array_merge($exempt_job_ids, $apply_job_ids);

        if(!empty($user['DetailUser']['zip'])){
            $zip = $user['DetailUser']['zip'];

            //find zip codes in search area
            $searchZipCodes = $this->ZipCode->findArea($zip, $distance);

            //find companies in search area based on zip codes
            $group_ids = $this->Group->searchZipCode($searchZipCodes);

            if($type == 0 || is_null($type)){
                $i1 = (!empty($user['TalentpatternUser'][0]['interest1'])) ? $user['TalentpatternUser'][0]['interest1'] : null ;
                $i2 = (!empty($user['TalentpatternUser'][0]['interest2'])) ? $user['TalentpatternUser'][0]['interest2'] : null ;

                $b1 = (!empty($user['TalentpatternUser'][0]['behavior1'])) ? $user['TalentpatternUser'][0]['behavior1'] : null ;
                $b2 = (!empty($user['TalentpatternUser'][0]['behavior2'])) ? $user['TalentpatternUser'][0]['behavior2'] : null ;

                $talent_pattern_ids = $this->JobTalentpattern->pick_list($i1, $i2, $b1, $b2);

            }

            $jobPostings = $this->JobPosting->searchJobPostings($group_ids, $talent_pattern_ids, $exempt_job_ids, $user);

        }else{

        }

        if ($this->request->is('requested')) {
            return $jobPostings;
        }

        $this->set( 'jobPostings', $jobPostings );
        $this->set( 'distance', $distance );
    }

}