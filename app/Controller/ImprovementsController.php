<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class ImprovementsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin
    
    var $uses = array(
        'Improvement', 
        'Setting',
    );
    
    public $components = array( 'RequestHandler', 'Paginator');
    
    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );
    
    public $paginate = array(
        'order' => array(
            'Improvement.name' => 'asc'
        ),
        'limit'=>50
    );
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Improvements');
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->set('title_for_layout', 'Improvements');
        
        $this->set('breadcrumbs', array(
            array('title'=>'Improvements', 'link'=>array('controller'=>'Improvements', 'action'=>'index')),
        ));
    }
    
    public function index() {
        $improvements['new'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active'=>1,
                'Improvement.accepted_date'=> null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.priority'=> 'asc'),
        ));
        
        $improvements['accepted'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active'=>1,
                'Improvement.accepted_date !='=> null,
                'Improvement.completed_date'=> null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.priority'=> 'asc'),
        ));
        
        $improvements['completed'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active' => 1,
                'Improvement.accepted_date !=' => null,
                'Improvement.completed_date !=' => null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.completed_date'=> 'DESC'),
        ));
        
        $improvements['not accepted'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active'=>2,
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.priority'=> 'asc'),
        ));
        
        $this->set('improvements', $improvements);
        
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
                'AccountDepartment'=>array(
                ),
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>$pageStatus
                    ),
                    'Status'=>array(
                        'fields'=>array('Status.name', 'Status.color')
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
        
        
        $this->set('breadcrumbs', array(
            array('title'=>'Accounts', 'link'=>array('controller'=>'Accounts', 'action'=>'index')),
            array('title'=>'View/Edit: '.$account['Account']['name'], 'link'=>array('controller'=>'Accounts', 'action'=>'view', $id)),
        ));
    }
    
    public function getDashboard() {
        $improvements['new'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active'=>1,
                'Improvement.accepted_date'=> null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.priority'=> 'asc'),
        ));
        
        $improvements['accepted'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active'=>1,
                'Improvement.accepted_date !='=> null,
                'Improvement.completed_date'=> null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.priority'=> 'asc'),
        ));
        
        $improvements['completed'] = $this->Improvement->find('all', array(
            'conditions' => array(
                'Improvement.is_active' => 1,
                'Improvement.accepted_date !=' => null,
                'Improvement.completed_date !=' => null
            ),
            'contain'=>array(
                'CreatedBy'=>array(
                    'fields'=>array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name')
                ),
                'Status'=>array(),
            ),
            'order'=>array('Improvement.completed_date'=> 'DESC'),
        ));
        
        return $improvements;
        
    }
    
    public function edit(){
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
            
            foreach($this->request->data['AccountDepartment'] as $item){
                foreach($item as $key=>$val){
                    $this->request->data['AccountDepartment'][$key]['account_id'] = $this->request->data['Account']['id'];
                    $this->request->data['AccountDepartment'][$key]['department_id'] = $val;
                }
            }
            unset($this->request->data['AccountDepartment']['department_id']);
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
                $this->redirect(array('controller'=>'Accounts', 'action'=>'index'));
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
                
                $this->redirect(array('controller'=>'Accounts', 'action'=>'view', $id));
            }
        }
    }
    
    public function add(){
        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;
            
            $this->request->data['Improvement']['created_by'] = AuthComponent::user('id');
            $this->request->data['Improvement']['created_date'] = date('Y-m-d', strtotime('now'));
            $this->request->data['Improvement']['is_active'] = 1;
            
            if(empty($this->request->data['Improvement']['idea'])){
                $error = true;
            }
            if($error == false){
                if ($this->Improvement->saveAll($this->request->data)) {
                    #Audit::log('Group record added', $this->request->data );
                    //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
                    $this->Flash->alertBox(
                        'Your Idea/Suggestion/Improvement has been submitted',
                        array(
                            'params' => array(
                                'class'=>'alert-success'
                            )
                        )
                    );
                }else{
                    $this->Flash->alertBox(
                        'Information not save! Please, try again.',
                        array(
                            'params' => array(
                                'class'=>'alert-danger'
                            )
                        )
                    );
                }
            }else{
                $this->Flash->alertBox(
                    'Information not save! Please, try again.',
                    array(
                        'params' => array(
                            'class'=>'alert-danger'
                        )
                    )
                );
            }
            
            $this->redirect(array('controller'=>'dashboard', 'action'=>'index'));
        }
        
        
    }
    
    public function delete($id = null, $empDelete = null) {
        $this->Improvement->id = $id;

        if (!$this->Improvement->exists()) {
            throw new NotFoundException(__('Invalid record'));
        }
        
        $this->request->data['Improvement']['id'] = $id;
        
        if ($this->Improvement->delete()) {
            #Audit::log('Group record added', $this->request->data );
            //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
            $this->Flash->alertBox(
                'Record Deleted',
                array(
                    'params' => array(
                        'class'=>'alert-success'
                    )
                )
            );
        }else{
            $this->Flash->alertBox(
                'Record not Deleted! Please, try again.',
                array(
                    'params' => array(
                        'class'=>'alert-danger'
                    )
                )
            );
        }
        
        $this->redirect(array('controller'=>'Improvements', 'action'=>'index'));
    }
    
    public function accept($id=null, $priority=null){
        $this->Improvement->id = $id;

        if (!$this->Improvement->exists()) {
            throw new NotFoundException(__('Invalid record'));
        }
        
        $this->request->data['Improvement']['id'] = $id;
        $this->request->data['Improvement']['priority'] = $priority;
        $this->request->data['Improvement']['accepted_date'] = date('Y-m-d', strtotime('now'));
        
        if ($this->Improvement->saveAll($this->request->data)) {
            #Audit::log('Group record added', $this->request->data );
            //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
            $this->Flash->alertBox(
                'Record Saved',
                array(
                    'params' => array(
                        'class'=>'alert-success'
                    )
                )
            );
        }else{
            $this->Flash->alertBox(
                'Record not save! Please, try again.',
                array(
                    'params' => array(
                        'class'=>'alert-danger'
                    )
                )
            );
        }
        
        $this->redirect(array('controller'=>'Improvements', 'action'=>'index'));
        
    }
    
    public function reject($id=null){
        $this->Improvement->id = $id;

        if (!$this->Improvement->exists()) {
            throw new NotFoundException(__('Invalid record'));
        }
        
        $this->request->data['Improvement']['id'] = $id;
        $this->request->data['Improvement']['is_active'] = 2;
        
        if ($this->Improvement->saveAll($this->request->data)) {
            #Audit::log('Group record added', $this->request->data );
            //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
            $this->Flash->alertBox(
                'Record Saved',
                array(
                    'params' => array(
                        'class'=>'alert-success'
                    )
                )
            );
        }else{
            $this->Flash->alertBox(
                'Record not save! Please, try again.',
                array(
                    'params' => array(
                        'class'=>'alert-danger'
                    )
                )
            );
        }
        
        $this->redirect(array('controller'=>'Improvements', 'action'=>'index'));
        
    }
}