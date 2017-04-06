<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AssignedTest extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	//public $displayField = 'id';
    public $actsAs = array('Containable');
    public function beforeFilter() {
        parent::beforeFilter();
        
    }
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'Test',
        'User',
        'TestSchedule',
        'TestRole'
    );
    
    public function getAssignedTesting() {
        $conditions = array(
            'AssignedTest.user_id' => AuthComponent::user('id')
        );
        
        if ($this->hasAny($conditions)){
            //do something
            return 1;
        }else{
            return 0;
        }
    }
    
    public function getAssignedTestingInfo($id=null) {
        $data = $this->find('first', array(
            'conditions' => array(
                'AssignedTest.id' => $id
            ),
            'contain'=>array(
                'TestSchedule',
                'TestRole'
                
            )
        ));
        
        return $data;
    }
    
    public function grabTesting($userId=null) {
        $data = $this->find('all', array(
            'conditions' => array(
                'AssignedTest.user_id' => $userId
            ),
            'contain'=>array(
                'Test'=>array(
                    'fields'=>array('Test.name', 'Test.conclusion')
                )
            ),
            'fields'=>array(
                'AssignedTest.id',
                'AssignedTest.test_id',
                'AssignedTest.assigned_date',
                'AssignedTest.completion_date',
                'AssignedTest.expires_date',
                'AssignedTest.t_ans',
                'AssignedTest.complete'
            )
        ));
        return $data;
    }
    
    public function grabTestingAll($page_num=null) {
        $this->recursive = 0;
        $data = $this->find('all', array(
            'conditions' => array(
                'AssignedTest.t_marks NOT LIKE ' => '%section_id%',
                'AssignedTest.completion_date != ' => '0000-00-00',
                
            ),
            'contain'=>array(),
            'limit' =>100,
            'page'=>$page_num
        ));
       
        return $data;
    }
    
    public function grabReportData($id=null) {
        $data = $this->find('first', array(
            'conditions' => array(
                'AssignedTest.id' => $id
            ),
            'contain'=>array('Test', 'User')
        ));
        
        return $data;
    }
    
    public function grabAllAssigned($id=null) {
        $data = $this->find('all', array(
            'conditions' => array(
                'AssignedTest.test_schedule_id' => $id,
                'AssignedTest.complete' => 100,
            ),
            'contain'=>array(
                'User'=>array(
                    'GroupMembership'=>array(
                        'conditions'=>array(
                            'GroupMembership.group_id != ' =>array(3,4)
                        ),
                        'Group'=>array(
                            'fields'=>array('Group.name')
                        )
                    ),
                    'fields'=>array('User.fullname')
                ), 
                'TestRole'=>array(
                    'fields'=>array('TestRole.name')
                ), 
            )
        ));
        return $data;
    }
    
    public function finishTest($id=null, $t_marks=null) {
        $data['id'] = $id;
        $data['complete'] = 100;
        $data['completion_date'] = date(DATE_MYSQL_DATE);
        $data['t_marks'] = serialize($t_marks);
        
        if($this->save($data)){
            return 1;
        }else{
            return 0;
        }
    }
    
    function _getTestStatus(){
        $content = $this->find('first', array(
            'conditions' => array(
                'AssignedTest.user_id'=> AuthComponent::user('id')
            ),
            'fields'=>array('AssignedTest.completion_date')
        ));
        
        $date = $content['AssignedTest']['completion_date'];
        return($date);
    }
    
    function _processOldTest($answer=null, $id=null, $user_id=null){
        $answer = unserialize($answer);
        $answer = unserialize('a:4:{i:0;a:2:{i:0;s:10:"Permissive";i:1;s:1:"4";}i:1;a:2:{i:0;s:5:"Plain";i:1;s:1:"3";}i:2;a:2:{i:0;s:5:"Proud";i:1;s:1:"5";}i:3;a:2:{i:0;s:11:"Pessimistic";i:1;s:1:"2";}}');
        pr($answer);
        
        exit;
        $data = array();
        /*
        1,$Dpercent ()
        2,$Ipercent ()
        3,$Spercent ()
        4,$Cpercent ()
        5,$REALISTIC
        6,$INVESTIGATIVE
        7,$ARTISTIC
        8,$SOCIAL
        9,$ENTERPRISING
        10,$CONVENTIONAL
        
        */
        $socialValue['total'] = $answer[4] + $answer[5] + $answer[6] + $answer[7] + $answer[8] + $answer[9];
        $discValue['total'] = $answer[0] + $answer[1] + $answer[2] + $answer[3];
        
        $data1[0]['avg'] = round( ($answer[0][1] / $discValue['total'] ) * 100, 2 );
        $data1[0]['name'] = 'Competitor'; //5
        $data1[0]['min_score'] = '5';
        $data1[0]['max_score'] = '5';
        $data1[0]['section_id'] = '210';
        
        $data1[1]['avg'] = round( ( $answer[1][1] / $discValue['total'] ) * 100, 2 );
        $data1[1]['name'] = 'Communicator'; //4
        $data1[1]['min_score'] = '4';
        $data1[1]['max_score'] = '4';
        $data1[1]['section_id'] = '210';
        
        $data1[2]['avg'] = round( ( $answer[2][1] / $discValue['total'] ) * 100, 2 );
        $data1[2]['name'] = 'Cooperator'; //3
        $data1[2]['min_score'] = '3';
        $data1[2]['max_score'] = '3';
        $data1[2]['section_id'] = '210';
        
        $data1[3]['avg'] = round( ( $answer[3][1] / $discValue['total'] ) * 100, 2 );
        $data1[3]['name'] = 'Coordinator'; //2
        $data1[3]['min_score'] = '2';
        $data1[3]['max_score'] = '2';
        $data1[3]['section_id'] = '210';
        
        $jpSaveInfo['competitor'] = $data1[0]['avg'];
        $jpSaveInfo['communicator'] = $data1[1]['avg'];
        $jpSaveInfo['cooperator'] = $data1[2]['avg'];
        $jpSaveInfo['coordinator'] = $data1[3]['avg'];
        
        array_multisort($data1, SORT_DESC);
        
        $data = array_merge($data,$data1);
        
        $data2[4]['avg'] = $data1[0]['avg'] + $data1[1]['avg'];
        $data2[4]['name'] = 'Active'; //2
        $data2[4]['min_score'] = '6';
        $data2[4]['max_score'] = '6';
        $data2[4]['section_id'] = '210';
        
        $data2[5]['avg'] = $data1[2]['avg'] + $data1[3]['avg'];
        $data2[5]['name'] = 'Passive'; //2
        $data2[5]['min_score'] = '7';
        $data2[5]['max_score'] = '7';
        $data2[5]['section_id'] = '210';
        
        array_multisort($data2, SORT_DESC);
        
        $data = array_merge($data,$data2);
        
        $data3[6]['avg'] = $data1[0]['avg'] + $data1[3]['avg'];
        $data3[6]['name'] = 'Task'; //2
        $data3[6]['min_score'] = '8';
        $data3[6]['max_score'] = '8';
        $data3[6]['section_id'] = '210';
        
        $data3[7]['avg'] = $data1[1]['avg'] + $data1[2]['avg'];
        $data3[7]['name'] = 'People'; //2
        $data3[7]['min_score'] = '9';
        $data3[7]['max_score'] = '9';
        $data3[7]['section_id'] = '210';
        
        array_multisort($data3, SORT_DESC);
        
        $data = array_merge($data,$data3);
        
        if(isset($socialValue[1])){ 
            $data4[8]['avg'] = round( ($socialValue[1] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[8]['avg'] = 0;
        }
        
        $data4[8]['name'] = 'Realistic';
        $data4[8]['min_score'] = '1';
        $data4[8]['max_score'] = '1';
        $data4[8]['section_id'] = '213';
        
        if(isset($socialValue[2])){ 
            $data4[9]['avg'] = round( ($socialValue[2] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[9]['avg'] = 0;
        }
        
        $data4[9]['name'] = 'Investigative';
        $data4[9]['min_score'] = '2';
        $data4[9]['max_score'] = '2';
        $data4[9]['section_id'] = '213';
        
        if(isset($socialValue[3])){ 
            $data4[10]['avg'] = round( ($socialValue[3] / $socialValue['total']) * 100, 2 );
        }else {      
            $data4[10]['avg'] = 0;
        }
        
        $data4[10]['name'] = 'Artistic';
        $data4[10]['min_score'] = '3';
        $data4[10]['max_score'] = '3';
        $data4[10]['section_id'] = '213';
        
        if(isset($socialValue[4])){ 
            $data4[11]['avg'] = round( ($socialValue[4] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[11]['avg'] = 0;
        }
        
        $data4[11]['name'] = 'Social';
        $data4[11]['min_score'] = '4';
        $data4[11]['max_score'] = '4';
        $data4[11]['section_id'] = '213';
        
        if(isset($socialValue[5])){ 
            $data4[12]['avg'] = round( ($socialValue[5] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[12]['avg'] = 0;
        }
        
        $data4[12]['name'] = 'Enterprising';
        $data4[12]['min_score'] = '5';
        $data4[12]['max_score'] = '5';
        $data4[12]['section_id'] = '213';
        
        if(isset($socialValue[6])){ 
            $data4[13]['avg'] = round( ($socialValue[6] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[13]['avg'] = 0;
        }
        $data4[13]['name'] = 'Conventional';
        $data4[13]['min_score'] = '6';
        $data4[13]['max_score'] = '6';
        $data4[13]['section_id'] = '213';
        
        $jpSaveInfo['user_id'] = $user_id;
        $jpSaveInfo['realistic'] = $data4[8]['avg'];
        $jpSaveInfo['investigative'] = $data4[9]['avg'];
        $jpSaveInfo['artistic'] = $data4[10]['avg'];
        $jpSaveInfo['social'] = $data4[11]['avg'];
        $jpSaveInfo['enterprising'] = $data4[12]['avg'];
        $jpSaveInfo['conventional'] = $data4[13]['avg'];
        
        array_multisort($data4, SORT_DESC);
        $data = array_merge($data,$data4);
        
        pr($data);
        
        exit;
    }
    
    public function saveSelfAssignedTest($test_list=null, $userId=null){
        
        if(!is_null($test_list)){
            foreach($test_list as $tkey=>$test){
                $data['AssignedTest'][$tkey]['test_id'] = $tkey;
                $data['AssignedTest'][$tkey]['user_id'] = $userId;
                $data['AssignedTest'][$tkey]['assigned_date'] = date(DATE_MYSQL_DATE);
                $data['AssignedTest'][$tkey]['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
            }
            
            $this->create();
            $this->saveAll($data['AssignedTest']);
            
            return true;
        }else{
            return false;
        }
    }
}
