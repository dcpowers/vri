<?php
App::uses('AppController', 'Controller');

/**
 * Apps Controller
 *
 */
class JobQuestionsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
    public $scaffold = 'admin';

    public $uses = array('Job','JobQuestion', 'JobTalentpattern', 'Group', 'JobPosting', 'JobQuestionDetail');
    
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
            $jobQuestions = $this->JobQuestion->get_questions($group_id);
            
            $this->set( 'jobQuestions', $jobQuestions );
        }
        
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Screening Questions', 'link'=>array('controller'=>'jobQuestions', 'action'=>'index', 'member'=>true ) ),
        ));
    }
    public function member_delete($id=null){
        $this->JobQuestion->id = $id;
        
        if($this->JobQuestion->delete()){
            
            $this->JobPosting->deleteAll(array('JobPosting.job_question_id' => $id), true);
            $this->JobQuestionDetail->deleteAll(array('JobQuestionDetail.job_question_id' => $id), true);
            
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
        
        return $this->redirect(array('controller'=>'jobQuestions','action' => 'index', 'member'=>true, '#'=>'qualifyingQuestions'));
    }
    
    public function member_edit($id=null){
       
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $this->JobQuestionDetail->deleteAll(array('JobQuestionDetail.job_question_id' => $this->request->data['JobQuestion']['id']), true);
            
            foreach($this->request->data['JobQuestionDetail'] as $key=>$item){
                if(empty($item['question'])){
                    unset($this->request->data['JobQuestionDetail'][$key]);
                }
            }
            #pr($this->request->data);
            #exit;
            if ($this->JobQuestion->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Question Set Has Been saved'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Question Set was not saved. Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            return $this->redirect(array('controller'=>'jobQuestions','action' => 'index', 'member'=>true, '#'=>'qualifyingQuestions'));
        }
        
        //Get Jobs
        $jobQuestion = $this->JobQuestion->find('all', array(
            'conditions' => array(
                'JobQuestion.id' => $id
            ),
            'contain' => array(
                'JobQuestionDetail'
            )
        ));
        $settings['questionOpt'] = $this->Job->questionOpt();
        unset($settings['questionOpt'][0]);
         
        $this->set( 'settings', $settings );
        $this->set( 'jobQuestion', $jobQuestion );
        
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Screening Questions', 'link'=>array('controller'=>'jobQuestions', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Edit Screening Questions', 'link'=>array('controller'=>'jobQuestions', 'action'=>'edit', $id, 'member'=>true ) ),
        ));
        
    }
    
    public function member_add($group_id=null){
       
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['JobQuestion']['group_id'] = AuthComponent::user('parent_group_ids.1');
            //Loop through questions and remove any that are blank
            foreach($this->request->data['JobQuestionDetail'] as $key=>$item){
                if(empty($item['question'])){
                    unset($this->request->data['JobQuestionDetail'][$key]);
                }
            }
             
            if ($this->JobQuestion->saveall($this->request->data)) {
                //Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Question Set Has Been saved'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error! Question Set was not saved. Please try again.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            return $this->redirect(array('controller'=>'jobQuestions','action' => 'index', 'member'=>true, '#'=>'qualifyingQuestions'));
        }
        
        $settings['questionOpt'] = $this->Job->questionOpt();
        unset($settings['questionOpt'][0]);
        
        $title = "Create New Question Set";
        $this->set( 'group_id', $group_id );
        $this->set( 'settings', $settings );
        $this->set( 'title', $title );
        
        $this->set('breadcrumbs', array(
            array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Screening Questions', 'link'=>array('controller'=>'jobQuestions', 'action'=>'index', 'member'=>true ) ),
            array('title'=>'Add Screening Questions', 'link'=>array('controller'=>'jobQuestions', 'action'=>'add', 'member'=>true ) ),
        ));
    }
    
    public function member_confirm($id=null, $app=null){
        //Get Jobs
        $jobs = $this->JobPosting->find('all', array(
            'conditions' => array(
                'JobPosting.job_question_id' => $id
            ),
            'contain' => array(
            )
        ));
        $settings['job_status'] = $this->Job->jobStatusInt();
            
        $this->set( 'settings', $settings );
        
        $layout = '<h2 class="text-danger">Are you Sure You Want To Delete?</h2><h3>This will delete all Job Openings that use this screening question set.</h3>';
        $this->set('id', $id );
        $this->set('content', $layout );
        $this->set('jobs', $jobs );
        
    }
}
