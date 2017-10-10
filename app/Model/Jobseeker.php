<?php
App::uses('AppModel', 'Model');
/**
 * Associate Model
 *
 */
class Jobseeker extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'first_name';
    public $actsAs = array('Search.Searchable', 'Containable');
    public $uses = 'User';
    //Search Plugin
    public $filterArgs = array(
        array('name' => 'q', 'type' => 'query', 'method' => 'orConditions')
    );

    // public $findMethods = array('supervisor' =>  true);
    
/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'GroupMembership',
        'SocUser',
        'SupervisorOf'=>array(
            'className'=>'Group',
            'foreignKey'=>'supervisor_id'
        ),
        'AssignedTest',
        'AssignedTraining',
        'AuthRoleUser' => array(
            'className' => 'AuthRoleUser',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'TalentpatternUser',
        'ApplyJob',
        'ExemptJob',
        'JobQuestionAnswer',
        'FileUser',
        
        
    );

/**
* hasAndBelongsToMany associations
*
* @var array
*/
    public $hasAndBelongsToMany = array(
        'MemberOf'=>array(
            'className'=>'Group',
            'joinTable'=>'group_memberships'
        ),
        'AssignedTesting'=>array(
            'className'=>'Test',
            'joinTable'=>'assigned_tests'
        ),
        'AuthRole'=>array(
            'className'=>'AuthRole',
            'joinTable'=>'auth_role_users'
        ),
        'AssignedJobseeker'=>array(
            'className'=>'AssignedJobseeker',
            'joinTable'=>'assigned_jobseekers'
        ),
        
    );
    
    public $hasOne = array( 
        'DetailUser'
    );
    
    
    public $validate = array(
        'first_name' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        ),
        'last_name' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        ),
        'username' => array(
            'required' => array(
                'rule' => array('email', true),    
                'message' => 'Please provide a valid email address/ Username.'    
            ),
            'unique' => array(
                'rule'    => array('isUniqueUsername'),
                'message' => 'This email/Username is already in use',
            ),
            'between' => array( 
                'rule' => array('between', 6, 60), 
                'message' => 'Usernames must be between 6 to 60 characters'
            )
        ),
        'zip' => array(
            'rule' => array('postal', null, 'us'),
            'allowEmpty' => true,
            'message' => 'Zipcode must be valid'
        ),
        'phone' => array(
            'rule' => array('phone', null, 'us'),
            'allowEmpty' => true,
            'message' => 'Phone must be valid'
        ),
        'password' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),   
                'message' => 'Password must have a mimimum of 6 characters',
                'allowEmpty' => true,
                'required' => false,
                'last'=>false
            )
        ),
        'password_confirm' => array(
             'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Both passwords must match.',
                'required' => false,
                'allowEmpty' => true
            )
        ),
        'password_update' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),   
                'message' => 'Password must have a mimimum of 6 characters',
                'allowEmpty' => true,
                'required' => false,
                'last'=>false
            ),
            'equaltofield' => array(
                'rule' => array('equaltofield','password_confirm_update'),
                'message' => 'Both passwords must match.',
                
            )
        ),
        'password_confirm_update' => array(
             'equaltofield' => array(
                'rule' => array('equaltofield','password_update'),
                'message' => 'Both passwords must match.',
                'required' => false,
                'allowEmpty' => true
            )
        )
    );
    
    public function matchPasswords($data){
        if($data['password'] == $this->data['User']['password_confirm']){
            return true;
        }
        $this->invalidate('password_confirm', 'Your Passwords Do Not Match');
        return false;
    }
    
        /**
     * Before isUniqueUsername
     * @param array $options
     * @return boolean
     */
    function isUniqueUsername($check) {
        $username = $this->find('first',array(
            'conditions' => array(
                'User.username' => $check['username'],
            ),
            'contain'=>array(),
            'fields' => array('User.id'),
        ));
        if(!empty($username)){
            if(!empty($this->data[$this->alias]['id'])){
                if($this->data[$this->alias]['id'] == $username['User']['id']){
                    return true; 
                }else{
                    return false; 
                }
            }else{
                return false; 
            }
        }else{
            return true; 
        }
    }
    
    /**
     * Before isUniqueEmail
     * @param array $options
     * @return boolean
     */
    function isUniqueEmail($check) {

        $email = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.id'
                ),
                'conditions' => array(
                    'User.username' => $check['username'],
                    'User.id !=' => AuthComponent::user('id'),
                )
            )
        );

        if(!empty($email)){
            #if($this->data[$this->alias]['id'] == $email['User']['id']){
            #    return true; 
            #}else{
                return false; 
            #}
        }else{
            return true; 
        }
    }
    
    public function searchUsers($term = null) {
        if(!empty($term)) {
            $users = $this->find('list', array(
                'conditions' => array(
                    'first_name LIKE' => trim($term) . '%'
                )
            ));
            return $users;
        }
        return false;
    }
    
    public function userList($group_ids=null,$user_ids=null, $status = null) {
        $userList = array();
        
        $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name)';
        
        $users = $this->find('all', array(
            'conditions' => array(
                'User.id'=>$user_ids
                
            ),
            'contain'=>array(
                'GroupMembership'=>array(
                    'conditions'=>array(
                        'GroupMembership.group_id'=>$group_ids
                    )
                ),
                'AuthRoleUser'=>array(
                    'conditions'=>array(
                        'AuthRoleUser.auth_role_id !='=>array(5,6)
                    )
                )
            ),
            'fields'=>array('User.id', 'User.display', 'User.username'),
            'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc')
        ));
        $count = 0;
        foreach($users as $key=>$user){
            
            $userList[$count]['value'] = $user['User']['display'].' ( '.$user['User']['username'].' )';
            $userList[$count]['url'] = $user['User']['id'];
            
            $count++;
        }
        return $userList;
        
    }
    
    public function userListByGroup($cond = null) {
        $userList = array();
        $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name)' ;
        $users = $this->find('all', array(
            'conditions' => array(
                $cond
            ),
            'contain'=>array(
                'GroupMembership'
            )
            
        ));
        pr($users);
        exit;
        $count = 0;
        foreach($users as $key=>$user){
            
            $userList[$count]['value'] = $key;
            $userList[$count]['text'] = $user;
            
            $count++;
        }
        return $userList;
        
    }
    
    public function alphaNumericDashUnderscore($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];

        return preg_match('/^[a-zA-Z0-9_ \-]*$/', $value);
    }
    
    public function equaltofield($check,$otherfield){ 
        //get name of field 
        $fname = ''; 
        foreach ($check as $key => $value){ 
            $fname = $key; 
            break; 
        } 
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname]; 
    }
    
    public function beforeSave($options = array()) {
        // hash our password
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        
        // if we get a new password, hash it
        if (isset($this->data[$this->alias]['password_update'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password_update']);
        }
    
        // fallback to our parent
        return parent::beforeSave($options);
    }

    //Search Plugin
    public function orConditions($data = array()) {

        $filter = $data['q'];
        $words = explode( " ", $filter );
        //pr( $words );
        $cond = array(
            'OR' => array(
                $this->alias . '.id LIKE' => '%' . $filter . '%',
                $this->alias . '.first_name LIKE' => '%' . $filter . '%',
                $this->alias . '.last_name LIKE' => '%' . $filter . '%'
            ));
        return $cond;
    }

    public function pickList( $first_choice='- Select your UserID -', $show='', $find_options=array() ) {
        if ( $first_choice !== false ) {
            $dataArr = array(0=>$first_choice);
        } else {
            $dataArr = array();
        }

        $find_options = array_merge_recursive(
            array(
                'conditions'=>array(
                    $this->alias.'.is_active'=>1
                ),
                'order'=>$this->alias.'.first_name asc'
            ),
            $find_options
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            if ( $show == 'full_name') {
                $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name']));
            } else {
                $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] )). ' ('.$rec[$this->alias]['userid'].')';
            }
        }
        return $dataArr;
    }

    public function pickList_all( $first_choice='- Select your UserID -', $show='', $find_options=array() ) {
        if ( $first_choice !== false ) {
            $dataArr = array(0=>$first_choice);
        } else {
            $dataArr = array();
        }

        $find_options = array_merge_recursive(
            array(
                'order'=>$this->alias.'.first_name asc'
            ),
            $find_options
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            if ( $show == 'full_name') {
                $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name']));
            } else {
                $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] )). ' ('.$rec[$this->alias]['userid'].')';
            }
        }
        return $dataArr;
    }

    /**
    * This method will return a Associate record, if not found, it will create
    * the associate record by fetching details from the PhoneBook.
    *
    * @param mixed $userid
    */
    public function getUser( $userName ) {  
        if ( empty( $userName ) ) {
            throw new InvalidArgumentException('Associate::getUser() expects $userid');
        }
                     
        //Find associate by userid into $record
        $record = $this->find('first', array(
            'conditions' => array(
                'User.username' =>$userName
            ),
            'contain'=>array(
                'DetailUser',
                'GroupMembership',
                'SupervisorOf',
                'AuthRoleUser',
                'AuthRole'
                
            )
        ));
        return $record;
        
    }                            

    public function getUserInfo( ) {
        $this->recursive = 2;
        $record = $this->find('first', array(
            'conditions' => array(
                'User.id' => AuthComponent::user('id')
            ),
            'contain'=>array(
                'DetailUser',
                'GroupMembership'=>array(
                    'Group'
                )
            )
           
        ));
        
        return $record;
        
    }
    /**
    * Given an Associate $id, return the supervisor of the Associate's Group(Department)
    *
    * @param mixed $id
    */
    public function getSupervisor( $id=null ) {
        if ( empty( $id) && empty( $this->id ) ) {
            throw new InvalidArgumentException('Invalid argument, $id required.');
        }

        if ( !empty( $id ) ) {
            $this->id = $id;
        }
        $record = $this->find( 'first', array(
            'conditions'=>array(
                'User.id'=>$id
            ),
            'contain'=>array(
                'Group'=>array(
                    'Supervisor'
                )
            )
        )  );

        if ( !empty($record['Group']['Supervisor']) ) {
            return array('Associate'=>$record['Group']['Supervisor']);
        }
        return array('Associate'=>array() );

    }

    /**
    * Given an Associate $id, return the supervisor of the Associate's Group
    *
    * @param mixed $id
    */
    public function getByAuthRole( $role_name=null ) {
        if ( empty( $role_name) ) {
            throw new InvalidArgumentException('Invalid argument, $role_name required.');
        }
        $emailarray = array();

        $record = $this->AuthRole->find( 'first', array( 'conditions'=>array( 'AuthRole.name'=>$role_name ) ) );
        $c = 0;
        //pr($record);

        if ( !empty( $record['Associate']) ) {
            foreach ($record['Associate'] as $emailuser){
                if($emailuser['org_unit_id'] == AuthComponent::user('org_unit_id')){

                    $emailarray['Associate'][$c] = $emailuser;

                    $c++;
                }
            }
        }

        return $emailarray;

    }


    /**
    * The painful way to find a supervisor, manager information must be correct in phonebook.
    *
    * @param mixed $id
    */
    public function findSupervisor( $id ) {
        $record = $this->read(null, $id  );
        return $this->find('first', array('conditions'=>array('userid' => $record[$this->alias]['manager_userid'] ) ) );
    }
    
    public function getUserNames ($term = null) {
        if(!empty($term)) {
            $users = $this->find('list', array(
                'conditions' => array(
                    'OR' => array(
                        'User.first_name LIKE' => $term.'%'
                        
                )), 
                'limit'=>20,
                'fields'=>array('User.first_name', 'User.last_name')
            ));
            
            return $users;
        }
        return false;
    }

    
    /* public function _findSupervisor($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['conditions']['Associate.id'] = true;
            return $query;
        }
        return $results;
    } */
    
    public function getAdminUser($term = null, $accounts = null) {
        if(!empty($term)) {
            $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name, " ( ",User.username, " )")' ;
            $users = $this->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'User.id LIKE' => '%' . trim($term) . '%',
                        'User.first_name LIKE' => '%' . trim($term) . '%',
                        'User.last_name LIKE' => '%' . trim($term) . '%'
                    )
                ),
                'contain'=>array(
                     'GroupMembership'=>array(
                        'conditions'=>array(
                            'GroupMembership.group_id'=>$accounts,
                            'GroupMembership.group_id !='=>array(3,4)
                            
                        ),
                        'fields'=>array()
                    )
                ),
                'fields'=>array('User.id', 'User.display')
            ));
            pr($users);
            foreach($users as $user){
                if(!empty($user['GroupMembership'])){
                    $records[$user['User']['id']] = $user['User']['display'];
                }
            }
            
            #pr($accounts);
            #pr($records);
            #exit;
            return $records;
        }
        return false;
    }
    
    public function getJobSeeker($term = null) {
        if(!empty($term)) {
            $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name, " ( ",User.username, " )")' ;
            $users = $this->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'User.id LIKE' => '%' . trim($term) . '%',
                        'User.first_name LIKE' => '%' . trim($term) . '%',
                        'User.last_name LIKE' => '%' . trim($term) . '%'
                    )
                ),
                'contain'=>array(
                    'GroupMembership'=>array(
                        'conditions'=>array(
                            'GroupMembership.group_id'=>array(3,4)
                        ),
                        'fields'=>array()
                    )
                ),
                'fields'=>array('User.id', 'User.display')
            ));
            
            foreach($users as $user){
                $records[$user['User']['id']] = $user['User']['display'];
            }
            
            #pr($accounts);
            #pr($records);
            #exit;
            return $records;
        }
        return false;
    }
    
    public function retrieveUsers($accounts=null){
        $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name)' ;
            $records = $this->find('all', array(
                'conditions' => array(
                    
                ),
                'contain'=>array(
                     'GroupMembership'=>array(
                        'conditions'=>array(
                            'GroupMembership.group_id'=>$accounts,
                            'GroupMembership.group_id !='=>array(3,4)
                        ),
                        'fields'=>array()
                    )
                ),
                'fields'=>array('User.id', 'User.display', 'User.created'),
                'order'=>array('User.created DESC'),
                'limit'=>20
            ));
            
            #pr($accounts);
            #pr($records);
            #exit;
            return $records;
        
    }
    
    public function retrieveAllUsers(){
        $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name)' ;
        
        $users = $this->find('all', array(
            'conditions' => array(
                'User.is_active'=>1
            ),
            'contain'=>array(
                'GroupMembership'=>array(
                    'conditions'=>array(
                        'GroupMembership.group_id !='=>array(3,4)
                    ),
                )
            ),
            'fields'=>array('User.id', 'User.display', 'User.created'),
            'order'=>array('User.first_name ASC'),
            'limit'=>20
        ));
        $i=0;
        foreach($users as $user){
            if(!empty($user['GroupMembership'])){
                $records[$i]['User']['id'] = $user['User']['id'];
                $records[$i]['User']['display'] = $user['User']['display'];
                $records[$i]['User']['created'] = $user['User']['created'];
                $i++;
            }
        }
        #pr($accounts);
        #pr($records);
        #exit;
        return $records;
    }
    
    public function retrieveJobSeekers(){
        $this->virtualFields[ 'display' ] = 'CONCAT(User.first_name, " ", User.last_name)' ;
        
        $users = $this->find('all', array(
            'conditions' => array(
                
            ),
            'contain'=>array(
                'GroupMembership'=>array(
                    'conditions'=>array(
                        'GroupMembership.group_id'=>array(3,4)
                    )
                )
            ),
            'fields'=>array('User.id', 'User.display', 'User.created'),
            'order'=>array('User.created DESC'),
            'limit'=>20
        ));
        $i=0;
        
        foreach($users as $user){
            if(!empty($user['GroupMembership'])){
                $records[$i]['User']['id'] = $user['User']['id'];
                $records[$i]['User']['display'] = $user['User']['display'];
                $records[$i]['User']['created'] = $user['User']['created'];
                $i++;
            }
        }
        #pr($accounts);
        #pr($records);
        #exit;
        return $records;
    }
    
    public function getUserUpdates(){
        $users = $this->find('all', array(
            'conditions' => array(
                'id <' => 8500
            ),
            'contain'=>array(
                'GroupMembership'=>array(
                    'conditions'=>array(
                        'GroupMembership.group_id'=>4
                    )
                )
            ),
            'fields'=>array('User.id'),
            'order'=>array('User.id ASC'),
            
        ));
        
        return $users;
        
    }
    
    public function emp_list(){
        $list = array('salpert1208@yahoo.com', 'bumbec3@yahoo.com', 'brenda.mcnamara@mcc.edu', 'Tiffanyj@hidc.us', 'cynthia.collins@mcc.edu', 'Teresah@hidc.us', 'kathleen.lavallier@mcc.edu', 'bb_svrc@frontier.com', 'colleen.mccorkle@mcc.edu', 'linda.grigsby@mcc.edu', 'dave.r.rose@gmail.com', 'drose@careeralliance.org', 'tdbanks70@hotmail.com', 'dkepps5766@comcast.net', 'vcrespo@actionmanagement.com', 'yolanda.beasley@mcc.edu', 'rita.miller@mcc.edu', 'cora.johnson@mcc.edu', 'rkinsey@actionmanagement.com', 'tanselmo@actionmanagement.com', 'mbanda@actionmanagement.com', 'kodum@actionmanagement.com', 'escott@actionmanagement.com', 'dmontgomery@actionmanagement.com', 'clawrence@actionmanagement.com', 'mclark@actionmanagement.com', 'sbeech@actionmanagement.com', 'sreaves@actionmangement.com', 'kwhite@actionmangement.com', 'shughes@actionmanagement.com', 'cwilliams@actionmanagement.com', 'jasonblazen@actionmanagement.com', 'dlepine@actionmanagement.com', 'bvaughn@actionmanagement.com', 'dwatson@actionmanagement.com', 'rhastings@actionmanagement.com', 'kmays@actionmanagement.com', 'tcooley@actionmanagement.com', 'tcordell@actionmanagement.com', 'RTeodosio@actionmanagement.com', 'dplamondon@actionmanagement.com', 'jdively@actionmanagement.com', 'eotis@actionmanagement.com', 'SteenahM@hidc.us', 'tkraus07@baker.edu', 'svrcindustries@frontier.com', 'ksmith34@baker.edu', 'RGladney@actionmanagement.com', 'L.Gottler@actionmanagement.com', 'lisahairston@msn.com', 'wbigelow@actionmanagement.com', 'rmays@actionmanagement.com', 'kirk4you@msn.com', 'vmcclain@actionmanagement.com', 'lstrachota@actionmanagement.com', 'mmoore@actionmanagement.com', 'drose@gsworks.org');
        
        $users = $this->find('list', array(
            'conditions' => array(
                'User.username' => $list
            ),
            'contain'=>array(
            ),
            'fields'=>array('User.id'),
            'order'=>array('User.id ASC'),
            
        ));
        
        return $users;
    }
}