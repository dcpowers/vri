<?php
App::uses('AppModel', 'Model');
/**
 * AuthRoleUser Model
 *
 * @property User $User
 * @property AuthRole $AuthRole
 */
class BlindTest extends AppModel {
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
    * belongsTo associations
    *
    * @var array
    */
	public $belongsTo = array(
		'TestSchedule',
		'Group'
	);
    
    public $actsAs = array('Containable');
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->layout = 'member';
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
     
    public function updateUser($users=null){
        
        foreach($users as $user_id){
            $conditions = array(
                'user_id' => $user_id
            );
            
            if ($this->hasAny($conditions)){
                //do something
            }else{
                $data['user_id'] = $user_id;
                $data['auth_role_id'] = 1;
                
                $this->create();
                $this->save($data);
            }
        }
        pr($user_id);
        echo "done";
        exit;
    }
    
    public function userList($user_ids=null) {
        $ids = $this->find('all', array(
            'conditions' => array(
                'AuthRoleUser.user_id'=>$user_ids,
                'AuthRoleUser.auth_role_id !=' => array(5,6)
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array('User.id'),
                    'order'=>array('User.id')
                )
            )
        ));
        
        foreach($ids as $id){
            $data[] = $id['User']['id'];
        }
        #pr($data);
        //pr($ids);
        #exit;
        return $data;
    }
    
    public function jobseekerList($user_ids=null) {
        $data = false;
        $ids = $this->find('all', array(
            'conditions' => array(
                'AuthRoleUser.user_id'=>$user_ids,
                'AuthRoleUser.auth_role_id' => array(5)
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array('User.id'),
                    'order'=>array('User.id')
                )
            )
        ));
        #pr($user_ids);
        #pr($ids);
        #exit;
        foreach($ids as $id){
            $data[] = $id['User']['id'];
        }
        #pr($data);
        //pr($ids);
        #exit; 
        return $data;
    }
    
    public function updateAuthRole($list=null){
        foreach($list as $item){
            $this->deleteAll(array('user_id' => $item), false);
            
            $data['user_id'] = $item;
            $data['auth_role_id'] = 1;
                
            $this->saveAll($data);
            
        }
    }
    
    public function saveAuthRole($data=null){
        $this->create();        
        if($this->save($data['AuthRoleUser'])){
            return true;
        }else{
            return false;
        }
    }
    
    public function changeRegUser($id, $auth_role, $user_id){
        $this->updateAll(
            array('AuthRoleUser.auth_role_id' => $auth_role),
            array('AuthRoleUser.id' => $id)
        );
        
        $users = $this->find('all', array(
            'conditions' => array(
                'AuthRoleUser.user_id' => $user_id //Job Seeker
            ),
            'contain'=>array(
            ),
        ));
        
        foreach($users as $key=>$item){
            $data['AuthRoleUser'][$key] = $item['AuthRoleUser'];
        }
        return $data;
    }
    
    public function grabAllAssigned($id=null) {
        $data = $this->find('all', array(
            'conditions' => array(
                'BlindTest.test_schedule_id' => $id,
                'BlindTest.complete' => 100,
            ),
            'contain'=>array(
                'Group'=>array(
                    'fields'=>array('Group.name')
                )
            )
        ));
        return $data;
    }
}
