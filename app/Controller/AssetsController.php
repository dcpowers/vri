<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AssetsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin
    
    var $uses = array(
        'Asset', 
        'Account', 
        'Setting',
        'Department',
        'User',
        'Manufacturer',
        'Vendor',
        'AssetType',
        'AssignedTo'
    );
    
    public $components = array( 'RequestHandler', 'Paginator');
    
    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Assets');
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->set('title_for_layout', 'Assets');
    }
    
    public function index($letter = null, $status = null, $viewBy = null) {
        $options = array();
        
        if(!empty($this->data)){
            foreach ($this->data as $k=>$v){
                foreach ($v as $kk=>$vv){
                    if(!empty($vv) && $k != 'Search'){
                        $url[$k.'.'.$kk]=$vv;
                        $cond = array('conditions'=>array($k.'.'.$kk=> $vv ));
                        
                        $options = array_merge_recursive($options,$cond);
                    }
                }
            }
        }
        
        if(is_null($status) || $status == 'All'){
            $option = array('conditions'=>array('Asset.is_active' => array(1,2)));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 'All');
        }else{
            $option = array('conditions'=>array('Asset.is_active' => $status));
            $options = array_merge_recursive($options,$option);
            $this->set('status', $status);
        }
        #pr($options);
        #exit;
        $this->Paginator->settings = array(
            'conditions' => array(     
                #'Account.id' => $search_ids,
            ),
            'contain'=>array(
                'Manufacturer'=>array(
                    'fields'=>array('Manufacturer.id', 'Manufacturer.name')
                ),
                'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name')
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
                'Status'=>array(),
            ),
            'limit' => 200,
            'maxLimit' => 200,
            'order'=>array('Asset.asset_type_id'=> 'asc', 'Asset.asset' => 'asc'),
        );
        
        $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        
        $result = $this->Paginator->paginate('Asset');
        
        $assets = array();
        
        $this->request->data['Search']['orderBy'] = (array_key_exists('Search', $this->request->data)) ? $this->request->data['Search']['orderBy'] : 'type';
        
        foreach($result as $data){
            switch($this->request->data['Search']['orderBy']){
                case 'manufacturer':
                    if(array_key_exists('name', $data['Manufacturer'])){
                        $indexName = $data['Manufacturer']['name'];
                        $keysort[$indexName] = $data['Manufacturer']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    
                    break;
                
                case 'account':
                    if(array_key_exists('name', $data['Account'])){
                        $indexName = $data['Account']['name'];
                        $keysort[$indexName] = $data['Account']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    break;
                
                case 'assignedTo':
                    if(array_key_exists('first_name', $data['AssignedTo'])){
                        $indexName = $data['AssignedTo']['first_name'].' '. $data['AssignedTo']['last_name'];
                        $keysort[$indexName] = $data['AssignedTo']['first_name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    break;
                
                case 'type':
                default:
                    if(array_key_exists('name', $data['AssetType'])){
                        $indexName = $data['AssetType']['name'];
                        $keysort[$indexName] = $data['AssetType']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    break;
                
            }
            
            $value[$indexName][] = $data;
            
            $assets = array_merge($assets,$value);
        }
        
        array_multisort($keysort, SORT_ASC, $assets);
        
        $this->set('assets', $assets);
        $this->setLists();
    }
    
    public function view($id=null, $pageStatus = null, $viewBy = null){
        $this->Asset->id = $id;
        if (!$this->Asset->exists()) {
            throw new NotFoundException(__('Invalid Asset Id'));
        }
        
        $asset = $this->request->data = $this->Asset->find('first', array(
            'conditions' => array(
                'Asset.id' => $id
            ),
            'contain'=>array(
               
            ),
            
        ));
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('assetTypeList', $this->AssetType->pickList());
        $this->set('manufacturerList', $this->Manufacturer->pickList());
        $this->set('vendorList', $this->Vendor->pickList());
        $this->set('accountList', $this->Account->pickListActive());
        $this->set('userList', $this->User->pickList());
        #pr($asset);
        #exit;
        
        $this->set('asset', $asset);
        
        $this->set('breadcrumbs', array(
            array('title'=>'Assets', 'link'=>array('controller'=>'Assets', 'action'=>'index')),
            array('title'=>'View/Edit: '.$asset['Asset']['asset'], 'link'=>array('controller'=>'Assets', 'action'=>'view', $asset['Asset']['id'])),
        ));
    }
    
    public function edit(){
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $error = false;
            $validationErrors = array();
            
            $this->request->data['Asset']['is_active'] = (empty($this->request->data['Asset']['is_active'])) ? 1 : $this->request->data['Asset']['is_active'] ;
            
            #pr($this->request->data);
            #exit;
            if ($this->Asset->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Asset: "'.$this->request->data['Asset']['asset'].'" has been saved', 
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );
                $this->redirect(array('controller'=>'Assets', 'action'=>'index'));
            } else {
                $this->Flash->alertBox(
                    'The Asset could not be saved. Please, try again.', 
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );    
                
                $this->set( compact( 'validationErrors' ) );
                
                $id = $this->request->data['Asset']['id'];
                
                $this->redirect(array('controller'=>'Assets', 'action'=>'view', $id));
            }
        }
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
    
    public function setLists(){
        $types = $this->AssetType->find('list', array(
            'conditions' => array(
                'AssetType.is_active'=>1
            ),
            'contain' => array(
            ),
            'fields'=>array('AssetType.id', 'AssetType.name')
        ));
        
        $manufactures = $this->Manufacturer->find('list', array(
            'conditions' => array(
                'Manufacturer.is_active'=>1
            ),
            'contain' => array(
            ),
            'fields'=>array('Manufacturer.id', 'Manufacturer.name'),
            'order'=>array('Manufacturer.name' => 'asc')
        ));
        
        $accounts = $this->Asset->find('list', array(
            'conditions' => array(
                #'Asset.is_active'=>1
            ),
            'contain' => array(
                'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name'),
                    'order'=>array('Account.name'=>'asc')
                )
            ),
            'group'=>array('Asset.account_id'),
            'fields'=>array('Account.id', 'Account.name'),
            
        ));
        
        $assignedToData = $this->Asset->find('all', array(
            'conditions' => array(
                #'Asset.is_active'=>1
            ),
            'contain' => array(
                'AssignedTo'=>array(
                    'fields'=>array('AssignedTo.id', 'AssignedTo.first_name'),
                    'order'=>array('AssignedTo.first_name'=>'asc')
                )
            ),
            'group'=>array('Asset.user_id'),
            'fields'=>array('AssignedTo.id', 'AssignedTo.first_name', 'AssignedTo.last_name'),
            
        ));
        
        foreach($assignedToData as $item){
            $assignedTo[$item['AssignedTo']['id']] = $item['AssignedTo']['first_name'].' '.$item['AssignedTo']['last_name'];
        }
        
        $this->set(compact('types', 'manufactures', 'accounts', 'assignedTo'));
    }
}