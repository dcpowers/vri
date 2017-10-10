<?php
App::uses('AppController', 'Controller');

/**
 * Apps Controller
 *
 */
class JobTalentpatternsController extends AppController {

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
        'User',
        'AuthRoleUser',
        'TalentpatternUser'
    );
    
    //beforeFilter callback
    public function beforeFilter( ) {
        parent::beforeFilter();

        
    }
    
    public function member_index(){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
        
        if(!empty($supervisorOf_id) || in_array(4,$role_ids) || in_array(20,$role_ids) || in_array(30,$role_ids) ){
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
            
            //Get Talent Patterns
            $jobTalentpattern = $this->JobTalentpattern->get_talent_patterns($group_id);
                
            $this->set( 'jobTalentpattern', $jobTalentpattern );
        }
        
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Talent Patterns', 'link'=>array('controller'=>'jobTalentPatterns', 'action'=>'index', 'member'=>true ) ),
        ));
    }
    
    public function member_confirm($id=null, $app=null){
        //Get Jobs
        $jobs = $this->JobPosting->find('all', array(
            'conditions' => array(
                'JobPosting.job_talentpattern_id' => $id
            ),
            'contain' => array(
            )
        ));
        $settings['job_status'] = $this->Job->jobStatusInt();
            
        $this->set( 'settings', $settings );
        
        $layout = '<h2 class="text-danger">Are you Sure You Want To Delete?</h2><h3>This will delete all Job Openings that use this talent pattern.</h3>';
        $this->set('id', $id );
        $this->set('content', $layout );
        $this->set('jobs', $jobs );
        
    }
    
    public function member_delete($id=null){
        $this->JobTalentpattern->id = $id;
        
        if($this->JobTalentpattern->delete()){
            
            $this->JobPosting->deleteAll(array('JobPosting.job_talentpattern_id' => $id), true);
            
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
        
        return $this->redirect(array('controller'=>'jobTalentpatterns','action'=>'index', 'member'=>true));
    }
    
    public function member_add($group_id=null){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
        
        $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $result = $this->TalentpatternUser->createTalentPatterns($this->request->data['JobTalentpattern']['users']);
            $i = 0;
            foreach($result as $key=>$r){
                if($i<=5){
                    $interest[$key] = $r;
                }else{
                    $behavior[$key] = $r;
                }
                $i++;
            }
            
            arsort($interest);
            arsort($behavior);
            
            $interest = array_slice($interest, 0, 2);
            $behavior = array_slice($behavior, 0, 2);
            
            $data  = array_merge($this->request->data['JobTalentpattern'], $result);
            
            $i=0;
            foreach($interest as $key=>$p){
                if($i == 0){ $data['interest1'] = $key; }
                else { $data['interest2'] = $key;}
                $i++;
            }
            
            $i=0;
            foreach($behavior as $key=>$p){
                if($i == 0){ $data['behavior1'] = $key; }
                else { $data['behavior2'] = $key;}
                $i++;
            }
            
            $data['users'] = serialize($data['users']);
            $data['group_id'] = $group_id[0];
            
            if ($this->JobTalentpattern->save($data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Talent Pattern Has Been saved'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Talent Pattern was not saved. Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            return $this->redirect(array('controller'=>'jobTalentpatterns','action'=>'index', 'member'=>true));
        }
        
        
        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            $group_ids = $this->Group->getChildren($group_id);
            
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);
            
            $searchActive = array();
            foreach($active_user_ids as $key=>$activeId){
                $search_ids[$key] = $activeId['pro_users']['id'];
            }
            
            $users = $this->TalentpatternUser->find('all', array(
                'conditions' => array(
                    'TalentpatternUser.User_id'=>$search_ids, 
                ),
                'contain' => array(
                    'User'=>array(
                        'fields'=>array('User.id', 'User.fullname')        
                    ),
                ),
                //'fields'=>array('TalentpatternUser.id', 'TalentpatternUser.user_id')
            ));
            foreach($users as $user){
                $userList[$user['User']['id']] = $user['User']['fullname'];
            }
        }   
        
        if(empty($userList)){ $userList = "No Users Found"; }
        
        $this->set( 'users', $userList );
        
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Talent Patterns', 'link'=>array('controller'=>'jobTalentPatterns', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Add Talent Pattern', 'link'=>array('controller'=>'jobTalentPatterns', 'action'=>'add', 'member'=>true ) ),
        ));
        
    }
    
    public function member_updatejob($group_id=null){
        $info = $this->JobTalentpattern->find('all', array(
            'conditions' => array(
                
            ),
            'contain' => array(
            )
        ));
        
        $count = 0;
        
        foreach($info as $d){
            $id = $d['JobTalentpattern']['id'];
            
            unset(
                $d['JobTalentpattern']['id'],
                $d['JobTalentpattern']['group_id'],
                $d['JobTalentpattern']['name'],
                $d['JobTalentpattern']['users'],
                $d['JobTalentpattern']['behavior1'],
                $d['JobTalentpattern']['behavior2'],
                $d['JobTalentpattern']['interest1'],
                $d['JobTalentpattern']['interest2'],
                $d['JobTalentpattern']['created'],
                $d['JobTalentpattern']['modified']
            );
            $i = 0;
            foreach($d['JobTalentpattern'] as $key=>$r){
                if($i<=5){
                    $interest[$key] = $r;
                }else{
                    $behavior[$key] = $r;
                }
                $i++;
            }
            
            arsort($interest);
            arsort($behavior);
            
            $interest = array_slice($interest, 0, 2);
            $behavior = array_slice($behavior, 0, 2);
            
            $i=0;
            foreach($interest as $key=>$p){
                if($i == 0){ $data['JobTalentpattern']['interest1'] = $key; }
                else { $data['JobTalentpattern']['interest2'] = $key;}
                $i++;
            }
            
            $i=0;
            foreach($behavior as $key=>$p){
                if($i == 0){ $data['JobTalentpattern']['behavior1'] = $key; }
                else { $data['JobTalentpattern']['behavior2'] = $key;}
                $i++;
            }
            $data['JobTalentpattern']['id'] = $id;
            
            $this->JobTalentpattern->save($data);
            
            $count++;
            
        }
        pr('Done');
        pr($count);
        exit;
    }
    
    public function member_updateuser(){
        $info = $this->TalentpatternUser->find('all', array(
            'conditions' => array(
                'OR'=>array(
                    'TalentpatternUser.behavior1 IS NULL',
                    'TalentpatternUser.behavior2 IS NULL',
                    'TalentpatternUser.interest1 IS NULL',
                    'TalentpatternUser.interest2 IS NULL'
                )
                
            ),
            'contain' => array(
            )
        ));
        
        $count = 0;
        foreach($info as $d){   
            
            $id = $d['TalentpatternUser']['id'];
            
            unset(
                $d['TalentpatternUser']['id'],
                $d['TalentpatternUser']['user_id'],
                $d['TalentpatternUser']['behavior1'],
                $d['TalentpatternUser']['behavior2'],
                $d['TalentpatternUser']['interest1'],
                $d['TalentpatternUser']['interest2'],
                $d['TalentpatternUser']['created'],
                $d['TalentpatternUser']['modified']
            );
            $i = 0;
            foreach($d['TalentpatternUser'] as $key=>$r){
                if($i<=5){
                    $interest[$key] = $r;
                }else{
                    $behavior[$key] = $r;
                }
                $i++;
            }
            
            arsort($interest);
            arsort($behavior);
            
            $interest = array_slice($interest, 0, 2);
            $behavior = array_slice($behavior, 0, 2);
            
            $i=0;
            foreach($interest as $key=>$p){
                if($i == 0){ $data['TalentpatternUser']['interest1'] = $key; }
                else { $data['TalentpatternUser']['interest2'] = $key;}
                $i++;
            }
            
            $i=0;
            foreach($behavior as $key=>$p){
                if($i == 0){ $data['TalentpatternUser']['behavior1'] = $key; }
                else { $data['TalentpatternUser']['behavior2'] = $key;}
                $i++;
            }
            $data['TalentpatternUser']['id'] = $id;
            
            $this->TalentpatternUser->save($data);
            
            $count++;
        }
        return true;
    }
}
