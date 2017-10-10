<?php
App::uses('AppController', 'Controller');

/**
 * Apps Controller
 *
 */
class JobviewsController extends AppController {

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
        'GroupMembership', 
        'JobPosting', 
        'DetailUser',
        'ExemptJob',
        'ApplyJob',
        'JobQuestionAnswer',
        'User',
        'JobOffer',
        'AuthRoleUser'
    );
    
    //beforeFilter callback
    public function beforeFilter( ) {
        parent::beforeFilter();
        
        $this->Auth->allow(
            'index', 'company', 'user', 'apply', 'employment'
        );
    }
    
    public function beforeRender() {
        parent::beforeRender();

        //$this->layout = 'member';
    }
    public function index(){
       
        $job_list = $this->JobPosting->retreiveAllJobPostings();
        
            
        $this->set( 'postings', $job_list );
        $this->layout = 'member';
        
    }
    
    public function view($id=null,$match=null, $pin=null){
        
        //if isn't null, lets login in the user
        //http://iwz-3.0/jobviews/view/16/100/b790bae7f5ef5522eb92acad2024d824
        if(!is_null($pin)){
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.pin_number'=> $pin
                ),
                'contain'=>array(
                    'DetailUser',
                    'SupervisorOf',
                    'AuthRoleUser',
                    'GroupMembership',
                    'AuthRole'
                )
            ));
        
            $data = $user['User'];
            $this->Auth->login($user['User']['id']);
            
            unset($user['User']);
            $data = array_merge($data,$user);
            
            $this->User->remote_login($data);
            
        }
        //pr(AuthComponent::user());
        //exit;
        $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
        
        $job_list['JobPosting']['match'] = $match;
        $job_list['JobPosting']['user_pin_number'] = $pin;
        
        $this->set( 'postings', $job_list );
        $this->layout = 'member';
    }
    
    public function employment($id=null,$pin=null){
        //if isn't null, lets login in the user
        //http://iwz-3.0/jobviews/view/16/100/b790bae7f5ef5522eb92acad2024d824
        if(!is_null($pin)){
            
           $user = $this->User->find('first', array(
               'conditions' => array(
                    'User.pin_number'=> $pin
                ),
                'contain'=>array(
                    'DetailUser',
                    'SupervisorOf',
                    'AuthRoleUser',
                    'GroupMembership',
                    'AuthRole'
                )
            ));
            
            $this->request->data = $user['User'];
            unset($user['User']);
            $this->request->data = array_merge($this->request->data,$user);
                
            $this->Auth->login($this->request->data);
            $app_ids = array();
            $parent_group_ids = array();
            
            foreach($this->request->data['GroupMembership'] as $member){
                $parents = $this->Group->getPath($member['group_id']);
                $current_id = Set::extract( $parents, '/Group/id' );
                        
                $app_ids = array_merge($app_ids,$current_id);
                $parent_group_ids = array_merge($parent_group_ids,$current_id);
            }
            
            $this->request->data['parent_group_ids'] = $parent_group_ids;
            //Write the data to the session
            $this->Session->write('Auth.User', $this->request->data );
                    
            //loop through app_ids and get apps
            $apps = array();
                    
            if(!empty($this->request->data['SupervisorOf'])){
                foreach($app_ids as $id){
                    //Get Applications (menu) from App table based on groups.
                    App::uses( 'ApplicationSwitch', 'Model' );
                            
                    $AppModel = new ApplicationSwitch();
                    $AppModel->recursive = 0;
                            
                    $GroupApps = $AppModel->findByGroup( $id );
                            
                    $apps = array_merge($apps,$GroupApps);
                            
                }
            }else{
                App::uses( 'ApplicationSwitch', 'Model' );
                $AppModel = new ApplicationSwitch();
                $AppModel->recursive = 0;
                            
                $GroupApps = $AppModel->findByNullGroup();
                $apps = array_merge($apps,$GroupApps);
            } 
            
            if(in_array(1,$this->request->data['parent_group_ids']) && !in_array(4,$this->request->data['parent_group_ids'] )){
                //Get Applications (menu) from App table based on groups.
                App::uses( 'ApplicationSwitch', 'Model' );
                            
                $AppModel = new ApplicationSwitch();
                $AppModel->recursive = 0;
                            
                $GroupApps = $AppModel->findByGroup( 1 );
                $apps['Application'] = $apps['Application'] + $GroupApps['Application'];
            }
            
            $this->Session->write('Apps', $apps );
        }
        $newId = $this->ApplyJob->find('first', array(
            'conditions' => array(
                'ApplyJob.id'=> $id
            ),
            'contain'=>array(
                'JobOffer'=>array(
                    'conditions'=>array(
                        'JobOffer.apply_job_id'=>$id,
                        'JobOffer.user_id'=>AuthComponent::user('id')
                    )
                )
            )
        ));
        #pr($newId);
        #pr(AuthComponent::user('id'));
        #exit;
        $id = $newId['ApplyJob']['job_posting_id'];
        $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
        $job_list[0]['JobPosting']['match'] = $newId['ApplyJob']['percent_match'];
        $job_list[0]['JobPosting']['user_pin_number'] = $pin;
        $job_list[0]['JobOffer']['id'] = $newId['JobOffer'][0]['id'];
        $job_list[0]['JobOffer']['status'] = $newId['JobOffer'][0]['status'];
        
        $this->set( 'id', $newId['ApplyJob']['id'] );
        $this->set( 'postings', $job_list );
        $this->layout = 'member';
    }
    
    public function member_acceptEmployment($id=null, $job_offer_id=null){
        $job_info = $this->ApplyJob->find('first', array(
            'conditions' => array(
                'ApplyJob.id'=> $id
            ),
            'contain'=>array(
                'JobPosting',
                'JobOffer'=>array(
                    'conditions'=>array(
                        'JobOffer.apply_job_id'=>$id,
                        'JobOffer.user_id'=>AuthComponent::user('id')
                    )
                )
            )
        ));
        $notes = '[ Accepted Offer ] '. date( APP_DATE_FORMAT, strtotime(date(DATE_MYSQL_DATE)));    
        
        if(!empty($job_info['ApplyJob']['notes'])){
            $notes .= "\n\n".$job_info['ApplyJob']['notes'];
        }
        
        $this->request->data['ApplyJob']['id'] = $id;
        $this->request->data['ApplyJob']['status'] = 10;
        $this->request->data['ApplyJob']['notes'] = $notes;
        
        $this->request->data['JobOffer']['id'] = $job_offer_id;
        $this->request->data['JobOffer']['status'] = 'Accepted';
        
        if ($this->ApplyJob->save($this->request->data['ApplyJob']) && $this->JobOffer->save($this->request->data['JobOffer'])) {
            //Audit::log('Group record edited', $this->request->data );
            
            //Delete from group and create record for new group
            $this->GroupMembership->deleteAll(array('GroupMembership.user_id' => AuthComponent::user('id')), false);
            $this->AuthRoleUser->deleteAll(array('AuthRoleUser.user_id' => AuthComponent::user('id')), false);
            
            $data['GroupMembership']['group_id'] = $job_info['JobPosting']['group_id'];
            $data['GroupMembership']['user_id'] = AuthComponent::user('id');
            
            $this->GroupMembership->create();
            $this->GroupMembership->save($data['GroupMembership']);
            
            $data['AuthRoleUser']['auth_role_id'] = 1;
            $data['AuthRoleUser']['user_id'] = AuthComponent::user('id');
            
            $this->AuthRoleUser->create();                
            $this->AuthRoleUser->save($data['AuthRoleUser']);
            
            $user = $this->User->find('first', array(
               'conditions' => array(
                    'User.id'=> AuthComponent::user('id')
                ),
                'contain'=>array(
                    'DetailUser',
                    'SupervisorOf',
                    'AuthRoleUser',
                    'GroupMembership',
                    'AuthRole',
                    'MemberOf'
                )
            ));
            //Log out
            $this->Session->destroy();
            $this->Cookie->destroy();
            $this->response->disableCache();
            
            //log back in
            $this->request->data = $user['User'];
            unset($user['User']);
            $this->request->data = array_merge($this->request->data,$user);
                
            $this->Auth->login($this->request->data);
            $app_ids = array();
            $parent_group_ids = array();
            
            foreach($this->request->data['GroupMembership'] as $member){
                $parents = $this->Group->getPath($member['group_id']);
                $current_id = Set::extract( $parents, '/Group/id' );
                        
                $app_ids = array_merge($app_ids,$current_id);
                $parent_group_ids = array_merge($parent_group_ids,$current_id);
            }
            
            $this->request->data['parent_group_ids'] = $parent_group_ids;
            //Write the data to the session
            $this->Session->write('Auth.User', $this->request->data );
                    
            //loop through app_ids and get apps
            $apps = array();
                    
            if(!empty($this->request->data['SupervisorOf'])){
                foreach($app_ids as $id){
                    //Get Applications (menu) from App table based on groups.
                    App::uses( 'ApplicationSwitch', 'Model' );
                            
                    $AppModel = new ApplicationSwitch();
                    $AppModel->recursive = 0;
                            
                    $GroupApps = $AppModel->findByGroup( $id );
                            
                    $apps = array_merge($apps,$GroupApps);
                            
                }
            }else{
                App::uses( 'ApplicationSwitch', 'Model' );
                $AppModel = new ApplicationSwitch();
                $AppModel->recursive = 0;
                            
                $GroupApps = $AppModel->findByNullGroup();
                $apps = array_merge($apps,$GroupApps);
            } 
            
            if(in_array(1,$this->request->data['parent_group_ids']) && !in_array(4,$this->request->data['parent_group_ids'] )){
                //Get Applications (menu) from App table based on groups.
                App::uses( 'ApplicationSwitch', 'Model' );
                            
                $AppModel = new ApplicationSwitch();
                $AppModel->recursive = 0;
                            
                $GroupApps = $AppModel->findByGroup( 1 );
                $apps['Application'] = $apps['Application'] + $GroupApps['Application'];
            }
            
            $this->Session->write('Apps', $apps );
           
            $group_name = $this->request->data['MemberOf'][0]['name'];
           
            $this->Session->setFlash(
                __('Congratulations! Your iWorkZone account is now a part of the "'. $group_name .'" account. '), 
                'alert-box', 
                array('class'=>'alert-success')
            );
        } else {
            $this->Session->setFlash(
                __('There Was An Error! Job Offer was not Accepted. Please try again.'), 
                'alert-box', 
                array('class'=>'alert-danger')
            );
        }
            
        //Redirect back to dashboard
        $this->redirect(array('controller'=>'dashboard', 'action'=>'index', 'member'=>true));
    }
    
    public function member_declineEmployment($id=null, $jobOfferId=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            //Update apply Jobs to decline
            //$notes = '[ Declined Offer ] '. date( APP_DATE_FORMAT, strtotime(date(DATE_MYSQL_DATE)));    
            //$notes .= "\n".$notes_msg;
            $this->request->data['ApplyJob']['status'] = 9;
            
            //Update job offers status, add any notes
            $this->request->data['JobOffer']['status'] = 'Declined';
            
            if ($this->ApplyJob->save($this->request->data['ApplyJob']) && $this->JobOffer->save($this->request->data['JobOffer'])) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Job Offer Has Been updated'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Job Offer was not updated. Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            //Redirect back to dashboard
            $this->redirect(array('controller'=>'dashboard', 'action'=>'index', 'member'=>true));
        }
        
        $this->set( 'title', 'Decline Offer' );
        $this->set( 'id', $id );
        $this->set( 'jobOfferId', $jobOfferId );
        $this->layout = 'blank_nojs';
        
    }
    
    public function company($id=null){
       
        $this->layout = 'member';
        
    }
    
    public function user($id=null){
        $this->layout = 'member';
        
        
    }
    
    public function exempt($id=null, $match=null){
        $this->JobPosting->id = $id;
        if (!$this->JobPosting->exists()) {
            throw new NotFoundException(__('Invalid Job Post'));
        }
        $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
        $data['ExemptJob']['job_posting_id'] = $id;
        $data['ExemptJob']['job_name'] = $job_list['Job']['name'];
        $data['ExemptJob']['percent_match'] = $match;
        $data['ExemptJob']['user_id'] = AuthComponent::user('id');
        $data['ExemptJob']['posted_by'] = AuthComponent::user('id');
        
        $this->ExemptJob->create();
        if ($this->ExemptJob->save($data)) {
            $this->Session->setFlash(
                __('You have successfully exempted from the job: '.$job_list[0]['Job']['name']), 
                'alert-box', 
                array('class'=>'alert-success')
            );
            $this->redirect(array('controller'=>'dashboard', 'action'=>'home', 'member'=>true, '#'=>'search'));
        }
    }
    
    public function apply($id=null, $match=null){
        
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $count=0;
            $id = $this->request->data['JobQuestionAnswer']['id'];
            
            $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
            
            $data['ApplyJob']['job_posting_id'] = $id;
            $data['ApplyJob']['user_id'] = AuthComponent::user('id');
            $data['ApplyJob']['posted_by'] = AuthComponent::user('id');
            $data['ApplyJob']['job_name'] = $job_list['Job']['name'];
            $data['ApplyJob']['percent_match'] = $match;
            //pr(AuthComponent::user());
            //pr($data);
            //pr($this->request->data);
            //exit;
            $this->ApplyJob->create();
            if ($this->ApplyJob->save($data)) {
                
                $apply_id = $this->ApplyJob->getLastInsertID();
                unset($data);
                
                foreach($this->request->data['answer'] as $q=>$ans){
                    $data[$count]['user_id'] = AuthComponent::user('id');
                    $data[$count]['job_question_detail_id'] = $q;
                    $data[$count]['answer'] = $ans;
                    $data[$count]['job_post_id'] = $id;
                    $data[$count]['apply_job_id'] = $apply_id;
                    
                    $count++;
                }
                                
                $this->JobQuestionAnswer->saveall($data);
                
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('You have successfully applied for this job'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
                //$this->redirect(array('controller'=>'jobviews', 'action'=>'view', $id));
                $this->redirect(array('controller'=>'dashboard', 'action'=>'home', 'member'=>true, '#' => 'search'));
            }
        }
        $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
        #pr($job_list);
        #exit;
        $title = $job_list['Job']['name'];
        
        unset(
            $job_list[0]['JobPosting'],
            $job_list[0]['Job'],
            $job_list[0]['JobTalentpattern'],
            $job_list[0]['ApplyJob']
        );
        
        $this->set( 'title', $title );
        $this->set( 'id', $id);
        $this->set( 'match', $match);
        $this->set( 'questions', $job_list['JobQuestion']['JobQuestionDetail'] );
        $this->layout = 'blank_nojs';
    }
    
    public function applyNow($id=null, $match=null){
        $this->JobPosting->id = $id;
        if (!$this->JobPosting->exists()) {
            throw new NotFoundException(__('Invalid Job Post'));
        }
        $job_list = $this->JobPosting->retreiveJobPostingDetails($id);
        $data['ApplyJob']['job_posting_id'] = $id;
        $data['ApplyJob']['job_name'] = $job_list['Job']['name'];
        $data['ApplyJob']['percent_match'] = $match;
        $data['ApplyJob']['user_id'] = AuthComponent::user('id');
        $data['ApplyJob']['posted_by'] = AuthComponent::user('id');
        
        $this->ApplyJob->create();
        if ($this->ApplyJob->save($data)) {
            $this->Session->setFlash(
                __('You have successfully applied for the job: '.$job_list['Job']['name']), 
                'alert-box', 
                array('class'=>'alert-success')
            );
            $this->redirect(array('controller'=>'dashboard', 'action'=>'home', 'member'=>true, '#' => 'search'));
        }
        
        $this->layout = 'blank_nojs';
    }
    
    public function removeApp($id=null){
        $this->JobPosting->id = $id;
        if (!$this->JobPosting->exists()) {
            throw new NotFoundException(__('Invalid Job Post'));
        }
        
        $deleteId = $this->ApplyJob->find('first', array(
            'conditions' => array(
                'ApplyJob.user_id'=> AuthComponent::user('id'),
                'ApplyJob.job_posting_id' => $id
            ),
            'fields'=>array('ApplyJob.id')
        ));
        if ($this->ApplyJob->delete($deleteId['ApplyJob'])) {
            
            $this->JobQuestionAnswer->deleteAll(array('JobQuestionAnswer.apply_job_id'=>$deleteId['ApplyJob']), false);
            $this->Session->setFlash(
                __('You have successfully deleted your application'), 
                'alert-box', 
                array('class'=>'alert-success')
            );
            
        }
        
        $this->redirect(array('controller'=>'jobviews', 'action'=>'view', $id));
        
    }
}
