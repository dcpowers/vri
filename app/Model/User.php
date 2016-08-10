<?php
// app/Model/User.php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $displayField = 'first_name';

    public $virtualFields = array(
        #'fullname' => 'CONCAT(User.first_name, " ", User.last_name)',
        #'manager_name' => 'CONCAT(Supervisor.first_name, " ",Supervisor.last_name)',
        #'regional_admin_name' => 'CONCAT(RegionalAdmin.first_name, " ",RegionalAdmin.last_name)',
        #'coordinator_name' => 'CONCAT(Coordinator.first_name, " ",Coordinator.last_name)',
    );

    public $actsAs = array('Containable', 'Multivalidatable');
    
    public $components = array('Auth');
    
    public $profileUploadDir = 'img/profiles';
    
    public $belongsTo = array(
        'Role' => array(
            'className' => 'AuthRole',
            'foreignKey' => 'auth_role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Supervisor' => array(
            'className' => 'User',
            'foreignKey' => 'supervisor_id',
            'conditions' => '',
            'fields' => array('Supervisor.id', 'Supervisor.first_name', 'Supervisor.last_name', 'Supervisor.is_active'),
            'order' => ''
        ),
        'Status' => array(
            'className' => 'Setting',
            'foreignKey' => 'is_active',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public $hasMany = array(
        'DepartmentUser',
        'AccountUser',
        'Asset',
        'TrainingRecord',
        'TrainingExempt',
    );
    /* 
    public $validate = array(
        'username' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A username is required',
                'allowEmpty' => false
            ),
            'between' => array( 
                'rule' => array('between', 5, 15), 
                'required' => true, 
                'message' => 'Usernames must be between 5 to 15 characters'
            ),
             'unique' => array(
                'rule'    => array('isUniqueUsername'),
                'message' => 'This username is already in use'
            ),
            'alphaNumericDashUnderscore' => array(
                'rule'    => array('alphaNumericDashUnderscore'),
                'message' => 'Username can only be letters, numbers and underscores'
            ),
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A password is required',
                
            ),
            'min_length' => array(
                'rule' => array('minLength', '6'),  
                'message' => 'Password must have a mimimum of 6 characters',
                
            )
        ),
         
        'password_confirm' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Please confirm your password'
            ),
             'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Both passwords must match.'
            )
        ),
         
        'email' => array(
            'required' => array(
                'rule' => array('email', true),    
                'message' => 'Please provide a valid email address.'   
            ),
             'unique' => array(
                'rule'    => array('isUniqueEmail'),
                'message' => 'This email is already in use',
            ),
            'between' => array( 
                'rule' => array('between', 6, 60), 
                'message' => 'Usernames must be between 6 to 60 characters'
            )
        ),
        
         
         
        'password_update' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),   
                'message' => 'Password must have a mimimum of 6 characters',
                'allowEmpty' => true,
                'required' => false
            )
        ),
        'password_confirm_update' => array(
             'equaltofield' => array(
                'rule' => array('equaltofield','password_update'),
                'message' => 'Both passwords must match.',
                'required' => false,
            )
        )
 
         
    );
    */
    public $validate = array(
        'username' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A Username is required',
                'allowEmpty' => false
            ),
            'unique' => array(
                'rule'    => array('isUniqueUsername'),
                'message' => 'This Username is already in use'
            ),
        ),
        'first_name' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A Firstname is required',
                'allowEmpty' => false
            ),
        ),
        'last_name' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'A Lastname is required',
                'allowEmpty' => false
            ),
        ),
        
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A password is required',
                
            ),
            'min_length' => array(
                'rule' => array('minLength', '6'),  
                'message' => 'Password must have a mimimum of 6 characters',
                
            )
        ),
         
        'password_confirm' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Please confirm your password'
            ),
             'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Both passwords must match.'
            )
        ),
         
        'email' => array(
            'required' => array(
                'rule' => array('email', true),    
                'message' => 'Please provide a valid email address.'   
            ),
             'unique' => array(
                'rule'    => array('isUniqueEmail'),
                'message' => 'This email is already in use',
            ),
            'between' => array( 
                'rule' => array('between', 6, 60), 
                'message' => 'Usernames must be between 6 to 60 characters'
            )
        ),
        
        'password_update' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),   
                'message' => 'Password must have a mimimum of 6 characters',
                'allowEmpty' => true,
                'required' => false
            )
        ),
        'password_confirm_update' => array(
             'equaltofield' => array(
                'rule' => array('equaltofield','password_update'),
                'message' => 'Both passwords must match.',
                'required' => false,
            )
        )
 
         
    );
     
        /**
     * Before isUniqueUsername
     * @param array $options
     * @return boolean
     */
    function isUniqueUsername($check) {
 
        $username = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.id',
                    'User.username'
                ),
                'conditions' => array(
                    'User.username' => $check['username']
                )
            )
        );
 
        if(!empty($username)){
            if($this->data[$this->alias]['id'] == $username['User']['id']){
                return true; 
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
                    'User.email' => $check['email']
                )
            )
        );
 
        if(!empty($email)){
            if($this->data[$this->alias]['id'] == $email['User']['id']){
                return true; 
            }else{
                return false; 
            }
        }else{
            return true; 
        }
    }
     
    public function alphaNumericDashUnderscore($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];
 
        return preg_match('/^[a-zA-Z0-9_ \-]*$/', $value);
    }
     
    public function equaltofield($check,$otherfield) 
    { 
        //get name of field 
        $fname = ''; 
        foreach ($check as $key => $value){ 
            $fname = $key; 
            break; 
        } 
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname]; 
    } 
 
    /**
     * Before Save
     * @param array $options
     * @return boolean
     */
    function beforeSave($options = array()) {
        
        parent::beforeSave();
        if (isset($this->data[$this->alias]['password'])) {
            
            $passwordHasher = new BlowfishPasswordHasher();
            
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        
        return true;
    }

    public function pickList( ) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.is_active'=>1
            ),
            'order'=>$this->alias.'.first_name asc'
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] ));
        }
        return $dataArr;
    }

    public function pickListByRole( $role_ids = null ) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.auth_role_id'=>$role_ids
            ),
            'order'=>$this->alias.'.first_name asc'
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] ));
        }
        return $dataArr;
    }
    
    public function pickListByAccount( $account_id = null ) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.account_id'=>$account_id
            ),
            'order'=>$this->alias.'.first_name asc'
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] ));
        }
        return $dataArr;
    }
    
    public function pickList_all() {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
            ),
            'order'=>$this->alias.'.first_name asc'
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['first_name'])) . ' ' . ucwords( strtolower($rec[$this->alias]['last_name'] ));
        }
        return $dataArr;
    }
    
    public function getLetterArray($ids=null){
        $letter = array();
        
        $users = $this->find('all', array(
            'conditions' => array(
                'User.id' => $ids
            ),
            'contain' => array(
            ),
            'order'=>array('User.first_name ASC')
        ));
        
        $names = Set::extract( $users, '/User/first_name' );
        
        foreach($names as $name){
            $letter[] = strtoupper($name[0]);
        }
        
        return $letter;
        
    }
    
    public function uploadFile($file) {
        if (isset($file['name']) AND $file['name'] != '') {
            $error = 0;
            $max_filesize = 2000001;
            $img = "";
            
            $allowMime = array('image/jpeg', 'image/pjpeg', 'image/gif',
                'image/png', 'application/msword','application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation','application/pdf'
            );
                
            if($error == 0){
                $name = rand ( 10000 , 99999 );
                $uploadfile = AuthComponent::user('DetailUser.uploadDir'). "/" . $name;
                if (move_uploaded_file($file['tmp_name'], $uploadfile) == FALSE) {
                    $img = "";    
                }else{
                    $img = $name;
                }
            }
            
        }
        
        return $img;
    }
    
    
}