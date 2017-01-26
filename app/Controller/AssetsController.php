<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AssetsController extends AppController {

    #public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );

    var $uses = array(
        'Asset',
        'Account',
        'AccountUser',
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

    public function index($status = null, $viewBy = null) {
        $options = array();

        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array(
                'OR'=>array(
                    'Asset.asset LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Asset.name LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Asset.tag_number LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Asset.model LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Asset.serial_number LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Asset.id' => $this->request->data['Search']['q'],
                )
            ));
            $options = array_merge_recursive($options,$option);
        }

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
            $this->request->data['Asset']['active'] = true;

        }else{
            $this->request->data['Asset']['active'] = false;
        }

		if(is_null($status)){
			$option = array('conditions'=>array('Asset.is_active' => 1));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 1);
		}else if($status == 'All'){
            $option = array('conditions'=>array('Asset.is_active' => array(1,2)));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 'All');
        }else{
            $option = array('conditions'=>array('Asset.is_active' => $status));
            $options = array_merge_recursive($options,$option);
            $this->set('status', $status);
        }

        $this->set('viewBy', $viewBy);

        $this->Paginator->settings = array(
            'conditions' => array(
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

        $manuClass= null;
        $accountClass= null;
        $typeClass= null;
        $assignedToClass= null;

        foreach($result as $data){
            switch($viewBy){
                case 'manufacturer':
                    if(array_key_exists('name', $data['Manufacturer'])){
                        $indexName = $data['Manufacturer']['name'];
                        $keysort[$indexName] = $data['Manufacturer']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }

                    $manuClass = 'active';

                    break;

                case 'account':
                    if(array_key_exists('name', $data['Account'])){
                        $indexName = $data['Account']['name'];
                        $keysort[$indexName] = $data['Account']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }

                    $accountClass = 'active';

                    break;

                case 'assignedTo':
                    if(array_key_exists('first_name', $data['AssignedTo'])){
                        $indexName = $data['AssignedTo']['first_name'].' '. $data['AssignedTo']['last_name'];
                        $keysort[$indexName] = $data['AssignedTo']['first_name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }

                    $assignedToClass = 'active';
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

                    $typeClass = 'active';

                    break;
            }

            $value[$indexName][] = $data;

            $assets = array_merge($assets,$value);
        }

        if(!empty($assets)){
            array_multisort($keysort, SORT_ASC, $assets);
        }

        $this->set('assets', $assets);
        $this->setLists();

        $this->set('accountClass', $accountClass);
        $this->set('typeClass', $typeClass);
        $this->set('manuClass', $manuClass);
        $this->set('assignedToClass', $assignedToClass);
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
                'AssetType'=>array(
                    'fields'=>array('AssetType.name')
                ),
                'Manufacturer'=>array(
                    'fields'=>array('Manufacturer.name')
                ),
                'Vendor'=>array(
                    'fields'=>array('Vendor.name')
                ),
                'AssignedTo'=>array(
                    'fields'=>array('AssignedTo.first_name', 'AssignedTo.last_name')
                ),
                'Status'=>array(
                    'fields'=>array('Status.name')
                ),
                'Account'=>array(
                    'fields'=>array('Account.name')
                ),
            ),

        ));

        $this->set('asset', $asset);
        $this->set('id', $id);
    }

	public function quickview($id=null, $pageStatus = null, $viewBy = null){
        $this->Asset->id = $id;
        if (!$this->Asset->exists()) {
            throw new NotFoundException(__('Invalid Asset Id'));
        }

        $asset = $this->request->data = $this->Asset->find('first', array(
            'conditions' => array(
                'Asset.id' => $id
            ),
            'contain'=>array(
                'AssetType'=>array(
                    'fields'=>array('AssetType.name')
                ),
                'Manufacturer'=>array(
                    'fields'=>array('Manufacturer.name')
                ),
                'Vendor'=>array(
                    'fields'=>array('Vendor.name')
                ),
                'AssignedTo'=>array(
                    'fields'=>array('AssignedTo.first_name', 'AssignedTo.last_name')
                ),
                'Status'=>array(
                    'fields'=>array('Status.name')
                ),
                'Account'=>array(
                    'fields'=>array('Account.name')
                ),
            ),

        ));

        $this->set('asset', $asset);
        $this->set('id', $id);
    }

    public function edit($id=null){
        $this->Asset->id = $id;
        if (!$this->Asset->exists()) {
            throw new NotFoundException(__('Invalid Asset Id'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['Asset']['is_active'] = (empty($this->request->data['Asset']['is_active'])) ? 1 : $this->request->data['Asset']['is_active'] ;

            if ($this->Asset->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Asset: "'.$this->request->data['Asset']['asset'].'" has been saved',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
                $this->redirect(array('controller'=>'Assets', 'action'=>'index'));
            } else {
                $this->Flash->alertBox(
                    'The Asset could not be saved. Please, try again.',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }
        }
        $asset = $this->request->data = $this->Asset->find('first', array(
            'conditions' => array(
                'Asset.id' => $id
            ),
            'contain'=>array(
            ),
        ));

        $account_id = (!empty($asset['Asset']['account_id'])) ? $asset['Asset']['account_id'] : null ;

        $this->set('status', $this->Setting->pickList('status'));
        $this->set('assetTypeList', $this->AssetType->pickList());
        $this->set('manufacturerList', $this->Manufacturer->pickList());
        $this->set('vendorList', $this->Vendor->pickList());
        $this->set('accountList', $this->Account->pickListActive());
        $this->set('userList', $this->User->pickListByAccount($account_id));

        $this->set('asset', $asset);
        $this->set('id', $id);

    }

    public function add($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
			pr($this->request->data);
			exit;
            $this->request->data['Asset']['is_active'] = (empty($this->request->data['Asset']['is_active'])) ? 1 : $this->request->data['Asset']['is_active'] ;

            if ($this->Asset->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Asset: "'.$this->request->data['Asset']['asset'].'" has been saved',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
                $this->redirect(array('controller'=>'Assets', 'action'=>'index'));
            } else {
                $this->Flash->alertBox(
                    'The Asset could not be saved. Please, try again.',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }
        }

        $this->set('status', $this->Setting->pickList('status'));
        $this->set('assetTypeList', $this->AssetType->pickList());
        $this->set('manufacturerList', $this->Manufacturer->pickList());
        $this->set('vendorList', $this->Vendor->pickList());
        $this->set('accountList', $this->Account->pickListActive());
        $this->set('userList', $this->User->pickList());
    }

    public function delete($id = null) {
        $this->Asset->id = $id;

        if($this->Asset->delete()){
            $this->Flash->alertBox(
                'The Asset Has Been Delete',
                array( 'params' => array( 'class'=>'alert-success' ))
            );
            $this->redirect(array('controller'=>'Assets', 'action'=>'index'));
        }else{
            $this->Flash->alertBox(
                'The Asset Could Not Be Deleted. Please, try again.',
                array( 'params' => array( 'class'=>'alert-success' ))
            );
            $this->redirect(array('controller'=>'Assets', 'action'=>'view', $id));
        }
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

    public function userList($id = null){
        $data = $this->AccountUser->pickList($id);

        $this->set(compact('data'));
    }

    public function manage($item=null, $action = null, $id=null){
        $model = ucwords(strtolower($item));

        switch($action){
            case "edit":
            case "add":

                if ($this->request->is('post') || $this->request->is('put')) {
                    if ($this->$model->saveAll($this->request->data)) {
                        $this->Flash->alertBox(
                            'The '. $model .' Has Been Saved',
                            array( 'params' => array( 'class'=>'alert-success' ))
                        );
                        $this->redirect(array('controller'=>'Assets', 'action'=>'manage', $item));
                    } else {
                        $this->Flash->alertBox(
                            'The '. $model .' Could Not Be Saved. Please, Try Again.',
                            array( 'params' => array( 'class'=>'alert-danger' ))
                        );
                        $this->redirect(array('controller'=>'Assets', 'action'=>'manage', $item, $action, $id));
                    }
                }

                $this->$model->id = $id;

                if ($this->$model->exists()) {
                    $this->request->data = $this->$model->find('first', array(
                        'conditions' => array(
                            $model.'.id' => $id
                        ),
                        'contain'=>array(
                        ),
                    ));
                }
                break;

            case "delete":
                $this->$model->id = $id;

                if($this->$model->delete()){
                    $this->Flash->alertBox(
                        'The '.$model.' Has Been Delete',
                        array( 'params' => array( 'class'=>'alert-success' ))
                    );
                }else{
                    $this->Flash->alertBox(
                        'The '.$model.' Could Not Be Deleted. Please, try again.',
                        array( 'params' => array( 'class'=>'alert-success' ))
                    );

                }

                $this->redirect(array('controller'=>'Assets', 'action'=>'manage', $item));
                break;

            default;
                $this->Paginator->settings = array(
                    'conditions' => array(
                    ),
                    'contain'=>array(
                    ),
                    'limit' => 200,
                    'maxLimit' => 200,
                    'order'=>array($model.'.name'=> 'asc'),
                );
                $this->set('data', $this->Paginator->paginate($model));
                break;
        }

        $this->set('action', $action);
        $this->set('item', $item);
        $this->set('model', $model);

    }
}