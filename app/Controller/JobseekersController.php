<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');
/**
 * Associates Controller
 *
 * @property Associate $Associate
 */
class JobseekersController extends AppController {
    public $uses = array(
        'User', 
        'State', 
        'DetailUser', 
        'Report.Report', 
        'Report.ReportSwitch', 
        'SocUser', 
        'Group',
        'AssignedTest',
        'TalentpatternUser',
        'ApplyJob',
        'ExemptJob',
        'GroupMembership',
        'AuthRoleUser',
        'AssignedJobseeker',
        'Jobseeker',
        'JobPosting',
        'CollaboraterNote',
        'UserReference'
    );
    public $helpers = array('Session');
    
    public $components = array('Search.Prg', 'RequestHandler', 'Paginator', 'Session');
    
    //Search Plugin
    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );
    
    public $paginate = array(
        'contain' => array(),
        'limit'=>30,
        'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc'),
        //'contain'=>array('GroupMembership'=>array('Group'))
    );
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
        $link = array();
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.SiteName', 'Employees - iWorkZone');
    }
    
    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow(
            'forgetpwd', 'reset', 'view_applicant', 'search_results'
        );
    }

/**
 * index method
 *
 * @return void
 */
    public function member_index($status=null) {
        
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRoleUser/auth_role_id' );
        
        if(in_array(5, $role_ids) || in_array(6, $role_ids)){
            $this->redirect(array('controller'=>'registration', 'action'=>'index', 'member'=>true));
        }
        
        $search_ids = $this->AssignedJobseeker->myList(AuthComponent::user('id'));
        
        $this->Paginator->settings = array(
            'conditions' => array('User.id' => $search_ids),
            'contain'=>array(
                'AssignedJobseekers'=>array(
                    'WfcUser'=>array(
                        'fields'=>array('WfcUser.id', 'WfcUser.first_name', 'WfcUser.last_name')
                    )
                )
            ),
            'limit' => 30,
            'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc'),
        );
                
        $data = $this->Paginator->paginate('User');
        
        $this->set('users', $data);
        
        $this->set('breadcrumbs', array(
            array('title'=>'Job Seekers', 'link'=>array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'My Case Load', 'link'=>array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true ) ),
        ));
    }
    
    public function member_all() {
        $id[0] = AuthComponent::user('parent_group_ids.1');
        
        //get children ids of the super id
        $group_ids = $this->Group->getChildren($id);
        
        $active_user_ids = $this->User->jobSeekerUserList($group_ids);
        
        $search_ids = array();
        foreach($active_user_ids as $key=>$activeId){
            $search_ids[$key] = $activeId['pro_users']['id'];
        }
        $this->Paginator->settings = array(
            'conditions' => array('User.id' => $search_ids),
            'contain'=>array(
                'AssignedJobseekers'=>array(
                    'WfcUser'=>array(
                        'fields'=>array('WfcUser.id', 'WfcUser.first_name', 'WfcUser.last_name')
                    )
                )
            ),
            'limit' => 30,
            'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc'),
        );
                
        $data = $this->Paginator->paginate('User');
        $this->set('users', $data);
        
        $this->set('breadcrumbs', array(
            array('title'=>'Job Seekers', 'link'=>array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'all Job Seekers', 'link'=>array('controller'=>'Jobseekers', 'action'=>'all', 'member'=>true ) ),
        ));
        
    }
    
    public function member_search($id = null) {
        $id = $this->request->data['User']['id'];
        
        if(!empty($id)){
            $this->redirect(array('controller'=>'Jobseekers', 'action'=>'view', 'member'=>true, $id));
        }
        
        $this->Session->setFlash(
            __('Please Click on a name, Then click View.'), 
            'alert-box', 
            array('class'=>'alert-danger')
        );
        
        $this->redirect(array('controller'=>'Jobseekers', 'action'=>'all', 'member'=>true));
        
        
    }
    
    public function member_view($id=null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid User'));
        }
        $this->User->recursive = 1;
        $data = $this->User->find('all', array(
            'conditions' => array(
                'User.id' => $id
            ),
            'contain'=>array(
                'GroupMembership',
                'DetailUser',
                'AssignedTest'=>array(
                    'Test'=>array(
                        'fields'=>array('Test.name')
                    ),
                    'fields'=>array(
                        'AssignedTest.id', 
                        'AssignedTest.assigned_date', 
                        'AssignedTest.completion_date',
                        'AssignedTest.expires_date',
                        'AssignedTest.complete',
                    ),
                ),
                'AssignedTraining'=>array(
                    'Training'=>array(
                        'fields'=>array('Training.name')
                    ),
                    'fields'=>array(
                        'AssignedTraining.id', 
                        'AssignedTraining.assigned_date', 
                        'AssignedTraining.completion_date',
                    ),
                ),
                'ApplyJob'=>array(
                    'JobPosting'=>array(
                        'Job'=>array(
                            'Group'=>array(
                                'fields'=>array('Group.name')
                            ),
                            'fields'=>array('Job.name')
                        ),
                        'fields'=>array(),
                    ),
                    'fields'=>array('ApplyJob.updated'),
                ),
                'ExemptJob'=>array(
                    'JobPosting'=>array(
                        'Job'=>array(
                            'Group'=>array(
                                'fields'=>array('Group.name')
                            ),
                            'fields'=>array('Job.name')
                        ),
                        'fields'=>array(),
                    ),
                    'fields'=>array('ExemptJob.updated'),
                ),
                'AssignedJobseekers'
            ),
            'fields'=>array('User.id', 'User.first_name', 'User.last_name', 'User.username')
        ));
        
        //loop through assigned test and determine what is completed
        //if it it completed lets load up what repoerts they can see
        foreach($data[0]['AssignedTest'] as $key=>$test){
            $date = explode('-', $test['completion_date']);
            $m = $date[1];
            $d = $date[2];
            $y = $date[0];
                            
            if(checkdate($m, $d, $y)){
                //Let's get the report Ids available
                $report_ids = $this->Report->getIds($test['test_id']);
                $data[0]['AssignedTest'][$key]['report'] = $this->ReportSwitch->getReports(AuthComponent::user('parent_group_ids'), $report_ids);
            }
        }
        
        $this->set('user', $data);
        
        //Gets list for CC and PG
        $myGroupId[0] = AuthComponent::user('parent_group_ids.1');
        
        $this->loadModel('Group');
        //get children ids of the super id
        $group_ids = $this->Group->getChildren($myGroupId);
        
        $user_ids = $this->GroupMembership->userList($group_ids);
        //get users by auth role
        $search_user_ids = $this->AuthRoleUser->userList($user_ids);
        $userList = $this->User->userList($group_ids,$search_user_ids);
        
        foreach($userList as $user){
            $s=explode("(",$user['value']);
            $dataList[$user['url']] = trim($s[0]);
        }
        $this->set('userList', $dataList);
        
        $this->set('breadcrumbs', array(
            array('title'=>'Job Seekers', 'link'=>array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true )),
            array('title'=>'My Case Load', 'link'=>array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true )),
            array('title'=>'All Job Seekers', 'link'=>array('controller'=>'Jobseekers', 'action'=>'all', 'member'=>true )),
            array('title'=>'View', 'link'=>array('controller'=>'Jobseekers', 'action'=>'view', $id ) )
        ));
        
    }
    
    public function member_details($user_id=null, $job_post_id=null){
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id'=>$user_id
            ),
            'contain' => array(
                'DetailUser'=>array(
                    'State',
                    'fields'=>array('DetailUser.address', 'DetailUser.city', 'DetailUser.county',
                        'DetailUser.state', 'DetailUser.zip','DetailUser.phone', 
                        'DetailUser.mobile','DetailUser.fax', 'DetailUser.img', 
                        'DetailUser.uploadDir'),
                ),
                'ApplyJob'=>array(
                    'conditions'=>array(
                        'ApplyJob.job_posting_id' => $job_post_id
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
                'UserFile',
                'AssignedTest'=>array(
                    'conditions'=>array(
                        'AssignedTest.test_id' => 62
                    ),
                    'fields'=>array('AssignedTest.id'),
                )
                
            ),
            'fields'=>array( 'User.sur_name', 'User.first_name', 'User.last_name', 'User.username')
            
        ));
        
        $jobPosting = $this->JobPosting->find('first', array(
            'conditions' => array(
                'JobPosting.id'=>$job_post_id
            ),
            'contain'=>array(
                'JobTalentpattern'
            )
        ));
        
        $this->set('applicants',$user);
        
        $tp['rel_diff'] = round(abs($user['TalentpatternUser'][0]['realistic'] - $jobPosting['JobTalentpattern']['realistic'] ), 2 );
        $tp['inv_diff'] = round(abs($user['TalentpatternUser'][0]['investigative'] - $jobPosting['JobTalentpattern']['investigative'] ), 2 );
        $tp['con_diff'] = round(abs($user['TalentpatternUser'][0]['conventional'] - $jobPosting['JobTalentpattern']['conventional'] ), 2 );
        $tp['ent_diff'] = round(abs($user['TalentpatternUser'][0]['enterprising'] - $jobPosting['JobTalentpattern']['enterprising'] ), 2 );
        $tp['soc_diff'] = round(abs($user['TalentpatternUser'][0]['social'] - $jobPosting['JobTalentpattern']['social'] ), 2 );
        $tp['art_diff'] = round(abs($user['TalentpatternUser'][0]['artistic'] - $jobPosting['JobTalentpattern']['artistic'] ), 2 );
        $tp['total_cat1'] = 100 - ($tp['rel_diff'] + $tp['inv_diff'] + $tp['con_diff'] + $tp['ent_diff'] + $tp['soc_diff'] + $tp['art_diff']);
        
        $tp['com_diff'] = abs($user['TalentpatternUser'][0]['competitor'] - $jobPosting['JobTalentpattern']['competitor'] );
        $tp['comm_diff'] = abs($user['TalentpatternUser'][0]['communicator'] - $jobPosting['JobTalentpattern']['communicator'] );
        $tp['coo_diff'] = abs($user['TalentpatternUser'][0]['cooperator'] - $jobPosting['JobTalentpattern']['cooperator'] );
        $tp['coor_diff'] = abs($user['TalentpatternUser'][0]['coordinator'] - $jobPosting['JobTalentpattern']['coordinator'] );
        
        $tp['total_cat2'] = 100 - ($tp['com_diff'] + $tp['comm_diff'] + $tp['coo_diff'] + $tp['coor_diff']);
        
        $tp['total'] = round(($tp['total_cat1'] + $tp['total_cat2']) / 2, 2 );
        $this->set('tp',$tp);
        $this->set('jobPosting',$jobPosting);
    }
    
    public function member_invite($user_id=null, $job_posting_id=null, $match=null){
        $userInfo = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ),
            'contain' => array(
            ),
            'fields'=>array('User.fullname', 'User.username', 'User.pin_number')
        ));
        
        $job_list = $this->JobPosting->retreiveJobPostingDetails($job_posting_id);
            
        $data['ApplyJob']['job_posting_id'] = $job_posting_id;
        $data['ApplyJob']['user_id'] = $user_id;
        $data['ApplyJob']['posted_by'] = AuthComponent::user('id');
        $data['ApplyJob']['job_name'] = $job_list['Job']['name'];
        $data['ApplyJob']['percent_match'] = $match;
        $data['ApplyJob']['status'] = 7;
        
        $this->ApplyJob->create();
        $this->ApplyJob->save($data);
                
        if (env('SERVER_NAME') != 'iwz-3.0'){
            $ms = "Congratulations! You've been invited to apply for job by one of iWorkZone's participating employers. Follow this link to see more about the job, including any criteria the employer has set. You can contact the employer to discuss things further, or simply select \"Apply\".";
            $ms .= '<a href="http://iworkzone.com/jobviews/view/'.$job_posting_id.'/'.$match.'/'.$userInfo['User']['pin_number'].'">View Job Posting</a>';         
            //Audit::log('Group record edited', $this->request->data );
            
            //Email Link To user
            $email = new CakeEmail();
            $email->config('smtp');
            #$email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
            $email->from(array('admin@iworkzone.com' => 'iWorkZone Administrator'));
            $email->template('invite');
            $email->to(array($userInfo['User']['username'] => $userInfo['User']['fullname']));
                                
            $email->subject('You have been invited to apply for a job');
            $email->emailFormat('both');

            $this->set('ms', $ms);
            $email->viewVars(array('ms' => $ms));
            
            if($email->send()){
                $this->Session->setFlash(
                    __('Invite to apply offer has been e-mailed'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
        }
        
        $this->redirect(array('controller'=>'JobPostings', 'action' => 'view', 'member'=>true, $job_posting_id));
    }
    
    public function member_reset($id=null, $user_id=null) {
        
        $this->request->data['AssignedTest']['id'] = $id;
        $this->request->data['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
        $this->request->data['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
        $this->request->data['AssignedTest']['completion_date'] = '0000-00-00';
        $this->request->data['AssignedTest']['t_ans'] = '';
        $this->request->data['AssignedTest']['t_marks'] = '';
        $this->request->data['AssignedTest']['complete'] = 0;
        
        if ($this->AssignedTest->save($this->request->data)) {
            
            $this->TalentpatternUser->deleteAll(array('TalentpatternUser.user_id' => $user_id), false);
            
            #Audit::log('Group record edited', $this->request->data );
            $this->Session->setFlash(
                __('Test Reset'), 
                'alert-box', 
                array('class'=>'alert-success')
            );
        } else {
            $this->Session->setFlash(
                __('Testing Reset Error! Please try again.'), 
                'alert-box', 
                array('class'=>'alert-danger')
            );
        }
                
        $this->redirect(array('controller'=>'Users','action'=>'view', $user_id ));
        
    }
    
    public function member_exempt($user_id=null, $job_posting_id=null, $match=null){
        $this->JobPosting->id = $job_posting_id;
        if (!$this->JobPosting->exists()) {
            throw new NotFoundException(__('Invalid Job Post'));
        }
        $job_list = $this->JobPosting->retreiveJobPostingDetails($job_posting_id);
        
        $data['ExemptJob']['job_posting_id'] = $job_posting_id;
        $data['ExemptJob']['job_name'] = $job_list['Job']['name'];
        $data['ExemptJob']['percent_match'] = $match;
        $data['ExemptJob']['user_id'] = $user_id;
        $data['ExemptJob']['posted_by'] = AuthComponent::user('id');
        
        $this->ExemptJob->create();
        if ($this->ExemptJob->save($data)) {
            $this->Session->setFlash(
                __('Search has been updated'), 
                'alert-box', 
                array('class'=>'alert-success')
            );
        }
        
        $this->redirect(array('controller'=>'JobPostings', 'action'=>'search', 'member'=>true, $job_posting_id));
    }
    
    public function member_edituser(){
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $this->AssignedJobseeker->deleteAll(array('AssignedJobseeker.user_id' => $this->request->data['AssignedJobseeker']['user_id']), false);
            
            $data = array();
            
            if(!empty($this->request->data['AssignedJobseeker']['cc_id'])){
                $data[0]['AssignedJobseeker']['model'] = 'cc';
                $data[0]['AssignedJobseeker']['wfc_id'] = $this->request->data['AssignedJobseeker']['cc_id'];
                $data[0]['AssignedJobseeker']['user_id'] = $this->request->data['AssignedJobseeker']['user_id'];
            }
            
            if(!empty($this->request->data['AssignedJobseeker']['pg_id'])){
                $data[1]['AssignedJobseeker']['model'] = 'pg';
                $data[1]['AssignedJobseeker']['wfc_id'] = $this->request->data['AssignedJobseeker']['pg_id'];
                $data[1]['AssignedJobseeker']['user_id'] = $this->request->data['AssignedJobseeker']['user_id'];
            }
            
            if(!empty($data)){
                if ($this->AssignedJobseeker->saveAll($data)) {
                    $this->Session->setFlash(
                        __('Jobseeker information has been saved'), 
                        'alert-box', 
                        array('class'=>'alert-success')
                    );
                } else {
                    $this->Session->setFlash(
                        __('There Was An Error! Please try again.'), 
                        'alert-box', 
                        array('class'=>'alert-danger')
                    );
                }
            }else{
                $this->Session->setFlash(
                    __('Jobseeker information has been saved.'), 
                    'alert-box', 
                    array('class'=>'alert-warning')
                );
            }
            
            $this->redirect(array('controller'=>'Jobseekers', 'action'=>'index', 'member'=>true));
        }
    }
    
    public function admin_index() {
        $this->layout = 'admin';
        $this->Paginator->settings = array(
            'contain'=>array(),
            'limit' => 100,
            'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc')
        );
        //$this->User->contain(array());
        
        $this->Prg->commonProcess();
        $this->paginate['conditions'] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
        $this->set('users', $this->paginate('User')); 
    }
    
    public function admin_addUserByGroup($group_id=null){
        if ($this->request->is('post')) {
            $error = false;
            $this->User->set($this->request->data);
            if(!$this->User->validates()){
                $validationErrors['User'] = $this->User->validationErrors;
                $error = true;
            }
            
            if($error == false){
                //Save User First
                
                $this->request->data['User']['is_activated'] = 1;
                $this->request->data['User']['activated_on'] = date("Y-m-d");
                $this->request->data['User']['is_active'] = 1;
                $this->request->data['User']['pin_number'] = md5($this->request->data['User']['username']);
                
                $this->User->create();
                $this->User->save($this->request->data['User']);
                $userId = $this->User->id;
                
                unset($this->request->data['User']);
                
                //Save Group Second
                $this->loadModel('GroupMembership');
                $this->request->data['GroupMembership']['user_id'] = $userId;
                $this->GroupMembership->save($this->request->data);
                
                //save a detailUser Record
                $this->loadModel('DetailUser');
                $rand = rand ( 10000 , 99999 );
                $cast_id = md5($rand);
                $result = substr($cast_id, 0, 8);
                $dir = mkdir('files/'.$result, 0777);
                $dir = 'files/'.$result;
                
                $data['DetailUser']['user_id'] = $userId;
                $data['DetailUser']['is_test_complete'] = 1;
                $data['DetailUser']['is_profile_complete'] = 1;
                $data['DetailUser']['is_eeoc_complete'] = 1;
                $data['DetailUser']['uploadDir'] = $dir;
                
                $this->DetailUser->save($data);
                
                //save a auth role to 1
                $this->loadModel('AuthRoleUser');
                $data['AuthRoleUser']['user_id'] = $userId;
                $data['AuthRoleUser']['auth_role_id'] = 1;
                $this->AuthRoleUser->save($data);
                
                $this->loadModel('AssignedTest');
                $this->request->data['AssignedTest']['user_id'] = $userId;
                $this->request->data['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                $this->request->data['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                $this->AssignedTest->save($this->request->data);
                
                if ($error == false) {
                    //Audit::log('Associate record created', $this->request->data );
                    
                    $this->Session->setFlash(
                        __('The employee has been saved'), 
                        'alert-box', 
                        array('class'=>'alert-success')
                    );
                    
                } else {
                    $this->Session->setFlash(
                        __('The employee could not be saved. Please, try again.'), 
                        'alert-box', 
                        array('class'=>'alert-danger')
                    );
                }
            }else{
                $this->set( compact( 'validationErrors' ) );
            }
            
            $this->redirect(array('controller'=>'Groups','action' => 'byGroup', 'admin'=>true, $group_id));
        }
    
    }
    
    public function admin_edit($id = null) {
        $this->Associate->id = $id;
        
        if (!$this->Associate->exists()) {
            throw new NotFoundException(__('Invalid associate'));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $c = 0;
            if(!empty($this->request->data['MemberOf']['group_id'])) {
                
                $this->loadModel('GroupMembership');
                $this->GroupMembership->deleteAll(array('GroupMembership.associate_id' => $id), false);
                
                foreach ($this->request->data['MemberOf']['group_id'] as $group_id){
                    $this->request->data['MemberOf'][$c]['group_id'] = $group_id;
                    $c++;
                }
            
                unset($this->request->data['MemberOf']['group_id']);
            }
            
            if(!empty($this->request->data['AssociateRole']['auth_role_id'])) {
                
                $this->loadModel('AssociateRole');
                $this->AssociateRole->deleteAll(array('AssociateRole.associate_id' => $id), false);
                
                foreach ($this->request->data['AssociateRole']['auth_role_id'] as $auth_id){
                    $this->request->data['AuthRole'][$c]['auth_role_id'] = $auth_id;
                    $c++;
                }
            
                unset($this->request->data['AssociateRole']['auth_role_id']);
            }
            //pr($this->request->data);
            //exit;
            if ($this->Associate->saveall($this->request->data)) {
                Audit::log('Associate record edited', $this->request->data );
                $this->Session->setFlash(__('The associate has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The associate could not be saved. Please, try again.'));
            }
        } else {
            $custom_array = array();
            
            if ( !empty($this->Auth->user['SupervisorOf'])) {   
                $custom_array = array('Associate.supervisor_id'=>$this->Auth->user['id']);
            }
        
            if ( in_array( 'SuperAdmin', $this->Auth->user('AuthRole') ) OR in_array( 'Associate Admin', $this->Auth->user('AuthRole') )) {   
                $custom_array = array();
                $skip = true;
            }
        
            $default_array = array('Associate.is_active' => 1, 'Associate.userid !=' => 'admin', 'Associate.org_unit_id'=>$this->Auth->user('org_unit_id'));
        
            $query_array = array_merge($default_array,$custom_array);
            
            $this->request->data = $this->Associate->read(null, $id);
            
            $this->Associate->virtualFields['name'] = 'CONCAT(first_name, " " , last_name)';
            
            $super = $this->Associate->find('list', array(
                'conditions'=>array(
                    'Associate.org_unit_id'=>AuthComponent::user('org_unit_id')
                    
                ),
                'order'=>array('Associate.first_name', 'Associate.last_name'),
                'fields'=>array('Associate.userid', 'Associate.name' )
            ));
            $this->set( 'supervisor', $super );
            
            
            $this->loadModel('Group');
        
            $Group = $this->Group->find('list', array(
                'conditions'=>array(
                    'Group.org_unit_id'=>AuthComponent::user('org_unit_id')
                ),
                'order'=>array('Group.name'),
                'fields'=>array('Group.id', 'Group.name')
            ));
        
            $this->set( 'group', $Group );
        
            $this->loadModel('AuthRole');
                
            $AuthRole = $this->AuthRole->find('list', array(
                'conditions'=>array(
                ),
                'fields'=>array('AuthRole.id', 'AuthRole.name')
            ));
        
            $this->set( 'AuthRole', $AuthRole );
            
            $this->loadModel('Org');
                
            $Org = $this->Org->find('list', array(
                'conditions'=>array(
                ),
                'fields'=>array('Org.id', 'Org.name')
            ));
        
            $this->set( 'Org', $Org );
            
            $this->loadModel('OrgUnit');
                
            $OrgUnit = $this->OrgUnit->find('list', array(
                'conditions'=>array(
                ),
                'fields'=>array('OrgUnit.id', 'OrgUnit.name')
            ));
        
            $this->set( 'OrgUnit', $OrgUnit );
            
            $this->set('breadcrumbs', array(
                array('title'=>'Associates','link'=>array( 'action'=>'index' ) ),
                array('title'=>'Edit','link'=>array( 'action'=>'edit', $id ) )
            ));
            
        }
    }

/**
 * view method
 *
 * @param string $id
 * @return void
 */
    public function admin_view($id = null) {
        $this->layout = 'admin';
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid associate'));
        }
        $results = $this->User->find('all', array(
            'conditions'=>array(
                'User.id'=> $id
            ),
            'contain'=>array(
                'DetailUser'=>array(
                    'fields'=>array(
                        'DetailUser.id',
                        'DetailUser.address',
                        'DetailUser.city',
                        'DetailUser.county',
                        'DetailUser.state',
                        'DetailUser.zip',
                        'DetailUser.phone',
                        'DetailUser.mobile',
                        'DetailUser.img',
                        'DetailUser.uploadDir',
                        'DetailUser.job_status',
                        'DetailUser.is_relocate',
                        'DetailUser.dob',
                        'DetailUser.gender',
                        'DetailUser.race',
                        'DetailUser.soc',
                        'DetailUser.is_test_complete',
                        'DetailUser.is_profile_complete',
                        'DetailUser.is_eeoc_complete'
                    )
                ),
                'SupervisorOf'=>array(
                    'fields'=>array('SupervisorOf.id', 'SupervisorOf.name')
                ),
                'AssignedTesting'=>array(
                    'fields'=>array('AssignedTesting.name')
                ),
                'AssignedTraining',
                'TalentpatternUser',
                'ApplyJob',
                'ExemptJob',
                'FileUser',
                'MemberOf'=>array(
                    'fields'=>array('MemberOf.name')
                ),
                'AuthRole',
                'SocUser'
            ),
            'fields'=>array(
                'User.id', 
                'User.sur_name',
                'User.first_name',
                'User.last_name',
                'User.username',
                'User.is_active',
                'User.reset_password'
            )
        ));
        
        $this->set('user', $results);
    }
    
    public function admin_search($id = null) {
        
        preg_match_all('#\((.*?)\)#', $this->request->data['User']['userSearch'] , $email);
        $results = $this->User->find('list', array(
            'conditions'=>array(
                'User.username'=> trim($email[1][0])
            ),
            
        ));
        
        foreach($results as $key=>$item){
            $id = $key;
        }
        $this->redirect(array('controller'=>'Users', 'action'=>'view', 'admin'=>true, $id));
        
    }
        
    public function admin_fetch( $id ) {
        $this->autoRender =  false;
        $this->Associate->updateFromPhoneBook( $id );
        $this->redirect(array('action'=>'index'));
    }
    
    public function admin_pickListByAccount(){
        
        $role_names = Set::extract( AuthComponent::user(), '/AuthRole/tag' );
        
        if(in_array('sales', $role_names )){
            $account_ids = $this->Session->read('my_accounts');
            
            $records = $this->User->retrieveUsers($account_ids);
        }else if(in_array('superAdmin', $role_names ) || in_array('admin', $role_names )){
            $records = $this->User->retrieveAllUsers();
        }
        
        #pr($accounts);
        #pr($records);
        #exit;
        return $records;
    }
    
    public function admin_pickListJobSeeker(){
        
        $records = $this->User->retrieveJobSeekers();
        return $records;
    }
    
    public function search() {
        if ($this->request->is('ajax')) {
            $account_id = AuthComponent::user('parent_group_ids.1');
            $userList = $this->GroupMembership->userList($account_id);
            $term = $this->request->query('term');
            $users = $this->User->searchMemberJobseekers($term,$userList);
            $this->set(compact('users'));
            $this->set('_serialize', 'users');
        }
        $this->layout = 'admin';
    }
    
    public function member_view_applicant($applyId=null){
        $item = $this->ApplyJob->find('first', array(
            'conditions' => array(
                'ApplyJob.id'=>$applyId
            ),
            'contain'=>array(
                'User'=>array(
                    'DetailUser'=>array(
                        'State',
                        'fields'=>array('DetailUser.address', 'DetailUser.city', 'DetailUser.county',
                            'DetailUser.state', 'DetailUser.zip','DetailUser.phone', 
                            'DetailUser.mobile','DetailUser.fax', 'DetailUser.img', 
                            'DetailUser.uploadDir'
                        ),
                    ),
                    
                    'TalentpatternUser',
                    'UserWorkHistory'=>array(
                        'order'=>array('UserWorkHistory.order')
                    ),
                    'UserReference'=>array(
                        'ReferenceResponce',
                    ),
                    'UserEducation'=>array(
                        'order'=>array('UserEducation.order')
                    ),
                    'UserFile',
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
                    'Collaborater'=>array(
                        'conditions' => array(
                            'Collaborater.job_posting_id'=> AuthComponent::user('id')
                        ),
                        
                    )
                ),
                'CollaboraterNote'=>array(
                    'conditions' => array(
                        'CollaboraterNote.apply_job_id'=>$applyId
                    ),
                )
            )
        ));
        #pr($item);
        #exit;
        //Set all info to carry
        $this->set('item',$item);
        
        $this->set('my_notes',array());
        
        foreach($item['CollaboraterNote'] as $note){
            if($note['user_id'] == AuthComponent::user('id')){
                $this->set('my_notes',$note);
            }
        }
        
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
        $settings['applicant_status'] = $this->Jobseeker->applicantFullStatus();
        $settings['questionOpt'] = $this->Jobseeker->questionOpt();
        $settings['yesNoInt'] = $this->Jobseeker->yesNoInt();
        $this->set('settings',$settings);
        
    }
    
    public function member_view_reference($id=null){
        $item = $this->UserReference->find('first', array(
            'conditions' => array(
                'UserReference.id'=>$id
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array('User.fullname')
                ),
                'ReferenceResponce',
            )
        ));
        
        #pr($item);
        #exit;
        $title = "View Reference";
        $this->set( 'title', $title );
        $this->set( 'item', $item );
        $this->layout = 'blank_nojs';
        
        
    } 
    
    public function member_add_rating_collaborater(){
        $id = $this->request->data['CollaboraterNote']['apply_job_id'];
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;
            
            if(empty($this->request->data['CollaboraterNote']['notes'])){
                $error = true;
                $this->Session->setFlash(
                    __('Please add a Note to your rating.'),
                    'alert-box', 
                    array('class'=>'alert-danger'),
                    'applicant'
                );
            }
            
            if(empty($this->request->data['CollaboraterNote']['rating'])){
                $this->request->data['CollaboraterNote']['rating'] = 0;
            }
            
            if($error == false){
                if ($this->CollaboraterNote->save($this->request->data)) {
                    //Audit::log('Group record edited', $this->request->data );
                    $this->Session->setFlash(
                        __('Thank You For Rating This Applicant'), 
                        'alert-box', 
                        array('class'=>'alert-success'),
                        'applicant'
                        
                    );
                } else {
                    $this->Session->setFlash(
                        __('There Was An Error! Please try again.'), 
                        'alert-box', 
                        array('class'=>'alert-danger'),
                        'applicant'
                    );
                }
            }
        }
        
        return $this->redirect(array('controller'=>'Jobseekers','action' => 'view_applicant', 'member'=>true, $id ));
    }
    
    public function member_update_applicant($id=null, $key=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $settings['applicant_status'] = $this->Jobseeker->applicantChangeStatus();
            $statusText = $settings['applicant_status'][$this->request->data['ApplyJob']['status']];
            
            $notes = '[ '.$statusText.' ] '. date( APP_DATE_FORMAT, strtotime(date(DATE_MYSQL_DATE)));
            $notes .= "\n".$this->request->data['ApplyJob']['notes'];
            
            if(!empty($this->request->data['ApplyJob']['prev_notes'])){
                $notes .= "\n\n".$this->request->data['ApplyJob']['prev_notes'];
            }
            $this->request->data['ApplyJob']['notes'] = $notes;
            //pr($this->request->data);
            //exit; 
            if ($this->ApplyJob->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Applicant Has Been Updated'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Applicant not updated. Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            $jobPostInfo = $this->ApplyJob->find('all', array(
                'conditions' => array(
                    'ApplyJob.id' => $this->request->data['ApplyJob']['id']
                ),
                'contain' => array(
                ),
            ));
        
            return $this->redirect(array('controller'=>'JobPostings','action' => 'view', 'member'=>true, $jobPostInfo[0]['ApplyJob']['job_posting_id'] ));
        }
        
        $jobPostInfo = $this->ApplyJob->find('all', array(
            'conditions' => array(
                'ApplyJob.id' => $id
            ),
            'contain' => array(
            ),
        ));
        $settings['applicant_status'] = $this->Jobseeker->applicantChangeStatus();
        
        $this->set('settings',$settings);
        
        $title = "Update Applicant Status";
        $this->set( 'title', $title );
        $this->set( 'id', $id );
        $this->set( 'key', $key );
        $this->set( 'info', $jobPostInfo );
        $this->layout = 'blank_nojs';
    }
    
    public function member_make_offer($id=null, $key=null, $pin_number=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $msg = '
                <p>We are excited to offer you a position with '. $this->request->data['ApplyJob']['company'].'. The details of the offer are listed below...</p>
                <ul>
                    <li>Company Name: '. $this->request->data['ApplyJob']['company'].'</li>
                    <li>Pay: '. $this->request->data['ApplyJob']['salary'].'</li>
                    <li>Frequency of Pay: '. $this->request->data['ApplyJob']['range'].'</li>
                    <li>Job Classification: '. $this->request->data['ApplyJob']['classification'].'</li>
                    <li>Job Title: '. $this->request->data['ApplyJob']['position'].'</li>
                </ul>
                <p>'.$this->request->data['ApplyJob']['url'].'</p>
            ';
            
            $notes_msg = '
                <p>We are excited to offer you a position with '. $this->request->data['ApplyJob']['company'].'. The details of the offer are listed below...</p>
                <ul>
                    <li>Company Name: '. $this->request->data['ApplyJob']['company'].'</li>
                    <li>Pay: '. $this->request->data['ApplyJob']['salary'].'</li>
                    <li>Frequency of Pay: '. $this->request->data['ApplyJob']['range'].'</li>
                    <li>Job Classification: '. $this->request->data['ApplyJob']['classification'].'</li>
                    <li>Job Title: '. $this->request->data['ApplyJob']['position'].'</li>
                </ul>
            ';
            //pr($msg);
            //exit;
            $userInfo = $this->ApplyJob->find('all', array(
                'conditions' => array(
                    'ApplyJob.id' => $this->request->data['ApplyJob']['id']
                ),
                'contain' => array(
                    'User'=>array(
                        'fields'=>array('User.fullname', 'User.username', 'User.id')
                    )
                ),
            ));
            
            $settings['applicant_status'] = 6;
            $this->request->data['ApplyJob']['status'] = 6;
            
            $notes = '[ Made Offer ] '. date( APP_DATE_FORMAT, strtotime(date(DATE_MYSQL_DATE)));    
            $notes .= "\n".$notes_msg;
                
            if(!empty($userInfo[0]['ApplyJob']['notes'])){
                $notes .= "\n\n".$userInfo[0]['ApplyJob']['notes'];
            }
                
            $this->request->data['ApplyJob']['notes'] = $notes;
            $conditions = array(
                'JobOffer.user_id' => $userInfo[0]['User']['id'],
                'JobOffer.apply_job_id ' => $userInfo[0]['ApplyJob']['id'],
                'JobOffer.status' => 'New'
            );
            
            //if ($this->JobOffer->hasAny($conditions)){
                //do something
            //}    
            if ($this->ApplyJob->saveall($this->request->data)) {
                $data['JobOffer']['user_id'] = $userInfo[0]['User']['id'];
                $data['JobOffer']['apply_job_id'] = $userInfo[0]['ApplyJob']['id'];
                $data['JobOffer']['salary'] = $this->request->data['ApplyJob']['salary'];
                $data['JobOffer']['range'] = $this->request->data['ApplyJob']['range'];
                $data['JobOffer']['type'] = $this->request->data['ApplyJob']['classification'];
                //$data['JobOffer']['start_date'] = ;
                $data['JobOffer']['percent_match'] = $userInfo[0]['ApplyJob']['percent_match'];
                
                $this->JobOffer->saveall($data);
                //Audit::log('Group record edited', $this->request->data );
                //Email Link To user
                $email = new CakeEmail();
                $email->config('smtp');
                
                #$email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
                $email->from(array('admin@iworkzone.com' => 'iWorkZone Administrator'));
                $email->template('jobOffer');
                $email->to(array($userInfo[0]['User']['username'] => $userInfo[0]['User']['fullname']));
                                
                $email->subject('You have been offered a job');
                $email->emailFormat('both');

                $this->set('ms', $msg);
                $email->viewVars(array('ms' => $msg));
                $email->send();
                    
                $this->Session->setFlash(
                    __('Applicant Has Been E-mailed A Job Offer'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            $this->redirect(array('controller'=>'JobPostingss','action' => 'view', 'member'=>true, $userInfo[0]['ApplyJob']['job_posting_id'] ));
            
        }
        
        $jobPostInfo = $this->ApplyJob->find('all', array(
            'conditions' => array(
                'ApplyJob.id' => $id
            ),
            'contain' => array(
                'JobPosting'=>array(
                    'Group'=>array(
                        'fields'=>array('Group.name')
                    )
                )
            ),
        ));
        $settings['applicant_status'] = $this->Jobseeker->applicantChangeStatus();
        $msg = '';
        $this->set('settings',$settings);
        
        $title = "Make An Offer";
        $this->set( 'title', $title );
        $this->set( 'id', $id );
        $this->set( 'pin_number', $pin_number );
        $this->set( 'key', $key );
        $this->set( 'msg', $msg );
        $this->set( 'info', $jobPostInfo );
        $this->layout = 'blank_nojs';
    }
    
    
}

