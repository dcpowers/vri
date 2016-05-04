<?php
// app/Model/User.php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $displayField = 'first_name';

    public $virtualFields = array(
        'fullname' => 'CONCAT(User.first_name, " ", User.last_name)'
    );

    public $actsAs = array('Containable', 'Multivalidatable');
    
    public $components = array('Auth');
    
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        )
    );
    
    public $validationSets = array( 
        'signUp' => array(
            'first_name' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Fill In Your Firstname'
            ),
            'last_name' => array(
                'rule' => 'notEmpty',
                'message' => 'Please Fill In Your Lastname'
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
            'password' => array(
                'min_length' => array(
                    'rule' => array('minLength', '6'),   
                    'message' => 'Password must have a mimimum of 6 characters',
                    'allowEmpty' => false,
                    'required' => true,
                    'last'=>false
                )
            ),
            'password_confirm' => array(
                 'equaltofield' => array(
                    'rule' => array('equaltofield','password'),
                    'message' => 'Both passwords must match.',
                    'required' => true,
                    'allowEmpty' => false
                )
            ),
        ), 
        
        
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
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        
        return true;
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