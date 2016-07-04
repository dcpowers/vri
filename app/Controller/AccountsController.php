<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AccountsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin
    
    var $uses = array(
        'User',
        'AccountDepartment', 
        'Account', 
        'Setting',
        'Department',
        'AuthRole'
    );
    
    public $components = array( 'RequestHandler', 'Paginator', 'Session');
    
    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );
    
    public $paginate = array(
        'order' => array(
            'Account.name' => 'asc'
        ),
        'limit'=>50
    );
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Accounts');
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->set('title_for_layout', 'Accounts');
    }
    
    public function index() {
        $options = array();
        
        if($this->Auth->user('Role.permission_level') == 50){
            $options = array('conditions'=>array('Account.regional_admin_id' => $this->Auth->user('id')));
        }
        
        #$this->User->virtualFields['Manager.manager_name'] = 'CONCAT(Manager.first_name, " " , Manager.last_name)';
        
        $this->Paginator->settings = array(
            'conditions' => array(     
                #'Account.id' => $search_ids,
            ),
            'contain'=>array(
                'Manager'=>array(
                    'fields'=>array('Manager.id', 'Manager.first_name', 'Manager.last_name')
                ),
                'Coordinator'=>array(
                    'fields'=>array('Coordinator.id', 'Coordinator.first_name', 'Coordinator.last_name')
                ),
                'RegionalAdmin'=>array(
                    'fields'=>array('RegionalAdmin.id', 'RegionalAdmin.first_name', 'RegionalAdmin.last_name')
                ),
                'Status'=>array(),
                'User'=>array(
                    'conditions'=>array('User.is_active' => 1),
                    'fields'=>array('User.id')
                )
            ),
            'limit' => 50,
            'order'=>array('Account.name'=> 'asc'),
        );
        $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        #pr($this->Paginator->settings);
        #exit;
        #$accounts = $this->Paginator->paginate('Account');
        #pr($accounts);
        #exit;
        
        $this->set('accounts', $this->Paginator->paginate('Account'));
        
    }
    
    public function view($id=null, $pageStatus = null, $viewBy = null){
        $this->Account->id = $id;
        if (!$this->Account->exists()) {
            throw new NotFoundException(__('Invalid Account Id'));
        }
        
        $deptClass= null;
        $superClass= null;
        $roleClass= null;
        
        $aStatusClass = null;
        $iStatusClass = null;
        $allStatusClass = null;
        
        $viewBy = (is_null($viewBy)) ? 'department' : $viewBy ;
        $this->set('viewBy', $viewBy);
        
        if(is_null($pageStatus) || $pageStatus == 1){
            $pageStatus = 1;
            $setStatus = 1;
            $aStatusClass = 'active';
        }else if($pageStatus == 2){
            $pageStatus = 2;
            $setStatus = 2;
            $iStatusClass = 'active';
        }
        
        if($pageStatus == 'all'){
            $pageStatus = array(1,2);
            $setStatus = 'all';
            $allStatusClass = 'active';
        }
        
        $this->set('pageStatus', $setStatus);
        
        $account = $this->request->data = $this->Account->find('first', array(
            'conditions' => array(
                'Account.id' => $id
            ),
            'contain' => array(
                'Manager'=>array(
                    'fields'=>array('Manager.id', 'Manager.first_name', 'Manager.last_name')
                ),
                'Coordinator'=>array(
                    'fields'=>array('Coordinator.id', 'Coordinator.first_name', 'Coordinator.last_name')
                ),
                'RegionalAdmin'=>array(
                    'fields'=>array('RegionalAdmin.id', 'RegionalAdmin.first_name', 'RegionalAdmin.last_name')
                ),
                'Status'=>array(),
                'Asset'=>array(
                    'Manufacturer'=>array(
                        'fields'=>array('Manufacturer.id', 'Manufacturer.name')
                    ),
                    'Vendor'=>array(
                        'fields'=>array('Vendor.id', 'Vendor.name')
                    ),
                    'AssignedTo'=>array(
                        'fields'=>array('AssignedTo.id', 'AssignedTo.first_name', 'AssignedTo.last_name')
                    ),
                    'AssetType'=>array(
                        'fields'=>array('AssetType.id', 'AssetType.name')
                    ),
                    'order'=>array('Asset.asset_type_id'=> 'asc', 'Asset.asset' => 'asc'),
                ),
                'AccountDepartment'=>array(
                    'Department'=>array(
                        'fields'=>array(
                            'Department.name'
                        )
                    )
                ),
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>$pageStatus
                    ),
                    'Status'=>array(
                        'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                    ),
                    'Supervisor'=>array(
                        'Status'=>array(
                            'fields'=>array('Status.name', 'Status.color')
                        )
                    ),
                    'Role'=>array(
                        'fields'=>array('Role.name', 'Role.lft')
                    ),
                    'Department'=>array(
                        'fields'=>array('Department.name', 'Department.abr')
                    ),
                    'fields'=>array(
                        'User.id',
                        'User.first_name',
                        'User.last_name',
                        'User.username',
                        'User.email',
                    ),
                    'order'=>array(
                        'User.first_name'=>'asc',
                        'User.last_name'=>'asc',
                    ),
                    
                )  
            ),
            
        ));
        
        $dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );
        
        $users = array();
        
        foreach($account['User'] as $data){
            switch($viewBy){
                case 'supervisor':
                    if(array_key_exists('first_name', $data['Supervisor'])){
                        $indexName = $data['Supervisor']['first_name'].' '. $data['Supervisor']['last_name'].'<small> [ '.$data['Supervisor']['Status']['name'].' ]</small>';
                        $keysort[$indexName] = $data['Supervisor']['first_name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $superClass = 'active';
                    break;
                
                case 'role':
                    $indexName = $data['Role']['name'];
                    $keysort[$indexName] = $data['Role']['lft'];
                    $roleClass = 'active';
                    break;
                
                case 'department':
                default:
                    if(array_key_exists('name', $data['Department'])){
                        $indexName = $data['Department']['name'].' ( '. $data['Department']['abr'] .' )';
                        $keysort[$indexName] = $data['Department']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $deptClass = 'active';
                    break;
                
            }
            
            $value[$indexName][] = $data;
            
            $users = array_merge($users,$value);
        }
        #pr($users);
        if(!empty($account['User'])){
            array_multisort($keysort, SORT_ASC, $users);
        }
        #pr($users);
        #exit;
        unset($account['User']);
        
        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));
        
        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);
        
        $this->set('account', $account);
        $this->set('employees', $users);
        
        $this->set('userList', $userList);
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('departments', $this->Department->pickList());
        
        //set all active classes
        $this->set('superClass', $superClass);
        $this->set('deptClass', $deptClass);
        $this->set('roleClass', $roleClass);
        
        $this->set('aStatusClass', $aStatusClass);
        $this->set('iStatusClass', $iStatusClass);
        $this->set('allStatusClass', $allStatusClass);
    }
    
    public function employeeView($id=null, $pageStatus = null, $viewBy = null){
        $this->Account->id = $id;
        if (!$this->Account->exists()) {
            throw new NotFoundException(__('Invalid Account Id'));
        }
        
        $deptClass= null;
        $superClass= null;
        $roleClass= null;
        
        $aStatusClass = null;
        $iStatusClass = null;
        $allStatusClass = null;
        
        $viewBy = (is_null($viewBy)) ? 'department' : $viewBy ;
        $this->set('viewBy', $viewBy);
        
        if(is_null($pageStatus) || $pageStatus == 1){
            $pageStatus = 1;
            $setStatus = 1;
            $aStatusClass = 'active';
        }else if($pageStatus == 2){
            $pageStatus = 2;
            $setStatus = 2;
            $iStatusClass = 'active';
        }
        
        if($pageStatus == 'all'){
            $pageStatus = array(1,2);
            $setStatus = 'all';
            $allStatusClass = 'active';
        }
        
        $this->set('pageStatus', $setStatus);
        
        $account = $this->request->data = $this->Account->find('first', array(
            'conditions' => array(
                'Account.id' => $id
            ),
            'contain' => array(
                'Manager'=>array(
                    'fields'=>array('Manager.id', 'Manager.first_name', 'Manager.last_name')
                ),
                'Coordinator'=>array(
                    'fields'=>array('Coordinator.id', 'Coordinator.first_name', 'Coordinator.last_name')
                ),
                'RegionalAdmin'=>array(
                    'fields'=>array('RegionalAdmin.id', 'RegionalAdmin.first_name', 'RegionalAdmin.last_name')
                ),
                'Status'=>array(),
                'AccountDepartment'=>array(
                    'Department'=>array(
                        'fields'=>array(
                            'Department.name'
                        )
                    )
                ),
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>$pageStatus
                    ),
                    'Status'=>array(
                        'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                    ),
                    'Supervisor'=>array(
                        'Status'=>array(
                            'fields'=>array('Status.name', 'Status.color')
                        )
                    ),
                    'Role'=>array(
                        'fields'=>array('Role.name', 'Role.lft')
                    ),
                    'Department'=>array(
                        'fields'=>array('Department.name', 'Department.abr')
                    ),
                    'fields'=>array(
                        'User.id',
                        'User.first_name',
                        'User.last_name',
                        'User.username',
                        'User.email',
                    ),
                    'order'=>array(
                        'User.first_name'=>'asc',
                        'User.last_name'=>'asc',
                    ),
                    
                )   
            ),
            
        ));
        
        $dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );
        
        $users = array();
        
        foreach($account['User'] as $data){
            switch($viewBy){
                case 'supervisor':
                    if(array_key_exists('first_name', $data['Supervisor'])){
                        $indexName = $data['Supervisor']['first_name'].' '. $data['Supervisor']['last_name'].'<small> [ '.$data['Supervisor']['Status']['name'].' ]</small>';
                        $keysort[$indexName] = $data['Supervisor']['first_name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $superClass = 'active';
                    break;
                
                case 'role':
                    $indexName = $data['Role']['name'];
                    $keysort[$indexName] = $data['Role']['lft'];
                    $roleClass = 'active';
                    break;
                
                case 'department':
                default:
                    if(array_key_exists('name', $data['Department'])){
                        $indexName = $data['Department']['name'].' ( '. $data['Department']['abr'] .' )';
                        $keysort[$indexName] = $data['Department']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $deptClass = 'active';
                    break;
                
            }
            
            $value[$indexName][] = $data;
            
            $users = array_merge($users,$value);
        }
        #pr($users);
        if(!empty($account['User'])){
            array_multisort($keysort, SORT_ASC, $users);
        }
        #pr($users);
        #exit;
        unset($account['User']);
        
        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));
        
        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);
        
        $status = $this->Setting->pickList('status');
        $departments = $this->Department->pickList();
        
        $this->set(compact(
            'account',
            'users',
            'userList',
            'status',
            'departments',
            'superClass',
            'deptClass',
            'roleClass', 
            'aStatusClass',
            'iStatusClass',
            'allStatusClass'
        ));
    }
    
    public function edit($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;
            $validationErrors = array();
            
            $this->request->data['Account']['is_active'] = (empty($this->request->data['Account']['is_active'])) ? 1 : $this->request->data['Account']['is_active'] ;
            
            $this->request->data['Account']['EVS'] = (empty($this->request->data['Account']['EVS'])) ? 0 : $this->request->data['Account']['EVS'] ;
            $this->request->data['Account']['CE'] = (empty($this->request->data['Account']['CE'])) ? 0 : $this->request->data['Account']['CE'] ;
            $this->request->data['Account']['Food'] = (empty($this->request->data['Account']['Food'])) ? 0 : $this->request->data['Account']['Food'] ;
            $this->request->data['Account']['POM'] = (empty($this->request->data['Account']['POM'])) ? 0 : $this->request->data['Account']['POM'] ;
            $this->request->data['Account']['LAU'] = (empty($this->request->data['Account']['LAU'])) ? 0 : $this->request->data['Account']['LAU'] ;
            $this->request->data['Account']['SEC'] = (empty($this->request->data['Account']['SEC'])) ? 0 : $this->request->data['Account']['SEC'] ;
            
            if(!empty($this->request->data['AccountDepartment'])){
                $this->AccountDepartment->deleteAll(array('AccountDepartment.account_id' => $this->request->data['Account']['id']), false);
                
                foreach($this->request->data['AccountDepartment'] as $item){
                    foreach($item as $key=>$val){
                        $this->request->data['AccountDepartment'][$key]['account_id'] = $this->request->data['Account']['id'];
                        $this->request->data['AccountDepartment'][$key]['department_id'] = $val;
                    }
                }
                unset($this->request->data['AccountDepartment']['department_id']);
            }
            #pr($this->request->data);
            #exit;
            if ($this->Account->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Account: "'.$this->request->data['Account']['name'].'" has been saved', 
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );
            } else {
                $this->Flash->alertBox(
                    'The Account could not be saved. Please, try again.', 
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );    
                
                $this->set( compact( 'validationErrors' ) );
                
                $id = $this->request->data['Account']['id'];
            }
            
            $this->redirect(array('controller'=>'Accounts', 'action'=>'view', $id));
        }
        
        $account = $this->request->data = $this->Account->find('first', array(
            'conditions' => array(
                'Account.id' => $id
            ),
            'contain' => array(
                'Manager'=>array(
                    'fields'=>array('Manager.id', 'Manager.first_name', 'Manager.last_name')
                ),
                'Coordinator'=>array(
                    'fields'=>array('Coordinator.id', 'Coordinator.first_name', 'Coordinator.last_name')
                ),
                'RegionalAdmin'=>array(
                    'fields'=>array('RegionalAdmin.id', 'RegionalAdmin.first_name', 'RegionalAdmin.last_name')
                ),
                'Status'=>array(),
                'AccountDepartment'=>array(
                    'Department'=>array(
                        'fields'=>array(
                            'Department.name'
                        )
                    )
                ),
                   
            ),
            
        ));
        
        $dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );
        
        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));
        
        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);
        
        $this->set('account', $account);
        $this->set('account', $account);
        
        $this->set('userList', $userList);
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('departments', $this->Department->pickList());
    }
    
    public function add($id=null){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;
            $validationErrors = array();
            
            $this->Group->validate = $this->Group->validationSets['subGroup']; 
            $this->Group->set($this->request->data['Group']);
            
            if(!$this->Group->validates()){
                $validationErrors['Group'] = $this->Group->validationErrors;
                $error = true;
            }
            
            if($error == false){
                if ($this->Group->saveall($this->request->data)) {
                    #Audit::log('Group record added', $this->request->data );
                    //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
                    $this->Session->setFlash(__('The Group: "'.$this->request->data['Group']['name'].'" has been saved'), 'alert-box', array('class'=>'alert-success'));
                    
                    $this->redirect(array('controller'=>'groups', 'action'=>'orgLayout', 'member'=>true));
                } else {
                    $this->Session->setFlash(__('The Group could not be saved. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
                }
            }else{
                $this->Session->setFlash(
                    __('Information not save! Please see errors below'), 
                    'alert-box', 
                    array('class'=>'alert-danger')
                );
                $this->set( compact( 'validationErrors' ) );
                
                $id =  $this->request->data['Group']['parent_id'];
            }
        }
        
        if(!empty($supervisorOf_id) || in_array(4,$role_ids) ){
            //get children ids of the super id
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;
            $group_ids = $this->Group->getChildren($group_id);
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);
            
            $search_ids = array();
            foreach($active_user_ids as $key=>$activeId){
                $search_ids[$key] = $activeId['pro_users']['id'];
            }
            
            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$search_ids, 
                ),
                'contain' => array(
                    
                ),
                'fields'=>array('User.id', 'User.fullname')
            ));
            
            //if they are already a supervisor, remove them from user list
            foreach($users as $key=>$user){
               $group = $this->Group->find('first', array(
                    'conditions' => array(
                        'Group.supervisor_id'=>$key
                    ),
                    'contain' => array(
                        
                    ),
                    'fields'=>array('Group.id')
                )); 
                if(!empty($group)){
                    unset($users[$key]);
                }
                
            }
            
            if(empty($users)){ $users = "No Users Found"; }
            
            $this->set( 'userList', $users );
            $this->set( 'id', $id );
            
            //grab list of states for form
            $states = $this->State->getListState();
            $this->set('states', $states);
    
            $this->set('breadcrumbs', array(
                array('title'=>'Account Settings', 'link'=>array('controller'=>'groups', 'action'=>'index', 'member'=>true ) ),
                array('title'=>'Organizational Layout', 'link'=>array('controller'=>'groups', 'action'=>'orgLayout', 'member'=>true ) ),
                array('title'=>'New Group', 'link'=>array('controller'=>'groups', 'action'=>'group_add', 'member'=>true, $id ) ),
            ));    
            //$this->layout = 'blank_nojs';
        }
    }
    
    public function delete($id = null, $empDelete = null) {
        $this->Group->id = $id;
        $empDelete = (is_null($empDelete)) ? 'No' : $empDelete;
        
        $parent = $this->Group->getPath($this->Group->id);
        
        //Check for top level. If is main account/iworkzone only can delete
        $parent_count = count($parent);
        if($parent_count >= 3){
            //Grap all children ids/group id
            $allChildren = $this->Group->children($id);
            $all_ids = set::extract($allChildren, '{n}.Group.id');
            $all_ids[] = $id;
            
            $parent_id = $parent[1]['Group']['id'];
            
            if($empDelete == 'No'){
                //Grab all users in group and children, update to parent id;
                $this->GroupMembership->updateAll(
                    array('GroupMembership.group_id' => $parent_id),
                    array('GroupMembership.group_id' => $all_ids)
                );            
            }else{
                //Grap all children ids/group id and delete
                
            }
            
            if($this->Group->delete()){
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
        }else{
            $this->Session->setFlash(
                __('You cannot delete this level of account'), 
                'alert-box', 
                array('class'=>'alert-danger')
            );
            
        }
        
        return $this->redirect(array('controller'=>'groups','action' => 'orgLayout', 'member'=>true));
    }
}