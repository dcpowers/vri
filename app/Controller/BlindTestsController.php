<?php
App::uses('AppController', 'Controller');
/**
 * AuthRoles Controller
 *
 * @property AuthRole $AuthRole
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BlindTestsController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $uses = array( 
        'BlindTest',
        'TestSchedule',
        'Test',
        'Group'
    );
    
    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
    }
    
    public function test($id=null){
        $this->layout = 'home';
        $this->set('id', $id);
        
        $blindTestId = $this->Session->read('BlindTest.id');
        
        $assignedTest = $this->TestSchedule->getAssignedTestingInfo($id, $blindTestId);
        
        if($assignedTest['TestSchedule']['ask_group'] == 1 && empty($assignedTest['BlindTest'][0]['group_id'])){
            $this->set( 'groups', $this->Group->pickListBySupervisor( $assignedTest['TestSchedule']['group_id'] ) );
            $this->render('_select_group');
            
            return;
        }
        $test = $this->Test->createTest($assignedTest['TestSchedule']['test_id'], $assignedTest['BlindTest'][0]['t_ans'] );    
        
        if($test[0] == 'complete'){ 
            $completed = $this->BlindTest->finishTest($assignedTest['BlindTest'][0]['id'], $test[1]);
                    
            if ($completed == 1) {
                #Audit::log('Group record edited', $this->request->data );
                $this->Session->setFlash(
                    __('Thank You, You Have Finished'), 
                    'alert-box', 
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('There Was An Error. We Are Looking Into it.'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
            }
            
            $this->redirect(array('controller'=>'', 'action'=>'index', 'member'=>false)); 
             
                
        }else{
            
            $this->set('content', $test);
            $this->set('assignedTest', $assignedTest);
            $this->set('completed', intval($assignedTest['BlindTest'][0]['complete']));
            
        }
        
        $this->layout = 'home';
        
    }
    
    public function process(){
        //Add new data to current saved date
        //grab all saved answers and unserlize
        $blindTestId = $this->Session->read('BlindTest.id');
        
        $u = $this->TestSchedule->getAssignedTestingInfo($this->request->data['BlindTest']['id'], $blindTestId);
        
        if(!empty($this->request->data['t_ans'])){
            
            $result = array();
            
            foreach($this->request->data['t_ans'] as $key=>$data){
                $info = $this->Test->getPath($key);
                $path = Hash::extract($info, '{n}.Test.id');
                $out = array();
                $curr = &$out;
                        
                if(is_numeric ($data)){
                    $value = $this->Test->grabValue($data);
                }else{
                    $value = $this->Test->grabValue($path[$countPath - 1]);
                }
                $count = count($path);
                $i=1;
                foreach ($path as $item) {
                    $curr[$item] = array(); 
                    $curr = &$curr[$item];
                    if($i == $count){
                        if(is_numeric ($data)){
                            $curr = $value['Test'];
                        }else{
                            $value['Test']['answerText'] = $value['Test']['answerText'];
                            $value['Test']['answerValue'] = $data;
                            $value['Test']['answerId'] = NULL;
                            
                            $curr = $value['Test'];        
                        }
                    }
                    $i++;
                }
                $out = Hash::flatten($out);
                $result = $result + $out;
            }
            unset($data);
            //$result = Hash::expand($result);
            $currentMarks = (empty($u['BlindTest'][0]['t_ans'])) ? array() : unserialize($u['BlindTest'][0]['t_ans']);
            $currentMarks = Hash::flatten($currentMarks);
            //merge arrays to together
            $result = array_replace_recursive($currentMarks, $result);
            $totalDone = count($result) / 3;
            
            $q_count = $this->Test->find('count', array(
                'conditions' => array(
                    'Test.lft >=' =>$u['Test']['lft'],
                    'Test.rght <=' =>$u['Test']['rght'],
                    'Test.category_type'=>3
                ),
                'contain'=>array(
                )
            ));
            $result = Hash::expand($result);
            //serilize and save
            $data['id'] = $u['BlindTest'][0]['id'];
            $data['complete'] = 100 * round( $totalDone / $q_count , 2);
            $data['t_ans'] = serialize($result);
            $this->BlindTest->save($data);
        }
        
        //redirect
        $this->redirect(array('controller'=>'BlindTests', 'action'=>'test', 'member'=>false, $this->request->data['BlindTest']['id'])); 
    }
    
    public function department($id=null, $link_num=null){
        
        $blindTestId = $this->Session->read('BlindTest.id');
        
        $data['BlindTest']['id'] = $blindTestId;
        $data['BlindTest']['group_id'] = $id;
        
        $this->BlindTest->save($data);
        
        $this->redirect(array('controller'=>'BlindTests', 'action'=>'test', $link_num)); 
    }
}