<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class TrainingsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'Training',
        'Setting',
        'TrainingCategory',
        'TrainingClassroom',
        'TrainingClassroomDetail',
        'TrnCat',
        'TrainingMembership',
        'Account',
        'AccountUser',
        'Department',
        'DepartmentUser',
        'AccountDepartment'
    );

    public $components = array( 'RequestHandler', 'Paginator');

    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    public $paginate = array(
        'order' => array(
            'Training.name' => 'asc'
        ),
        'limit'=>50
    );

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Training');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Training');
        /*
        $this->set('breadcrumbs', array(
            array('title'=>'Training', 'link'=>array('controller'=>'Trainings', 'action'=>'index')),
        ));
        */
    }

    public function index() {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        #pr($account_ids);
        #pr($department_ids);
        #pr($user_ids);
        #exit;
        $trainings = $this->TrainingMembership->find('all',array(
			'conditions'=>array(
                'OR' => array(
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => $account_ids,
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.department_id' => $department_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.user_id' => $user_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => null,
                            'TrainingMembership.department_id' => null,
                            'TrainingMembership.user_id' => null,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                )
            ),
            'contain'=>array(
                'Training'=>array(
                ),
                'Department'=>array(
                    'fields'=>array(
                        'Department.id',
                        'Department.name'
                    )
                ),
                'RequiredUser'=>array(
                    'fields'=>array(
                        'RequiredUser.id',
                        'RequiredUser.first_name',
                        'RequiredUser.last_name',
                    )
                )
            ),
            'order'=>array('Training.name'=> 'asc'),
            'group'=>array(
                'TrainingMembership.training_id',
                'TrainingMembership.account_id',
            )
        ));

		#pr($trainings);
		#exit;

        if(!empty($this->request->data['Search']['q'])){
            $training_ids = $this->Training->find('list', array(
                'conditions' => array(
                    'OR'=>array(
                        array('Training.name LIKE' => '%'.$this->request->data['Search']['q'].'%', ),
                        array('Training.description LIKE' => '%'.$this->request->data['Search']['q'].'%' )
                    )
                ),
                'contain'=>array(
                ),
                'fields'=>array('Training.id', 'Training.id')
            ));

            $options = array(
                'conditions'=>array(
                    'AND'=>array(
                        'TrainingMembership.training_id'=>$training_ids
                    )
                )
            );

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        }

		#$trainings = $this->Paginator->paginate('TrainingMembership');
        /*
		$data = array();

		$classRoom = array();

        foreach($trainings as $key=>$item){
            #pr($item);
            #exit;
            if(empty($item['Training']['name'])){
                unset($trainings[$key]);
                break;
            }

            $classRoom = $item['Training']['TrainingClassroom'];

            foreach($item['Training']['TrainingClassroom'] as $c_key=> $c){
                $totalCount = 0;
                $attendCount = 0;

                if(!empty($c['TrainingClassroomDetail'])){
                    foreach($c['TrainingClassroomDetail'] as $k=>$v){
                        $totalCount++;
                        if($v['did_attend'] == 1){
                            $attendCount++;
                        }
                    }

                    $trainings[$key]['Training']['TrainingClassroom'][$c_key]['total'] = $totalCount;
                    $trainings[$key]['Training']['TrainingClassroom'][$c_key]['attend'] = $attendCount;
                }


            }
            #unset($trainings[$key]['Training']['TrainingClassroom']);

            $training = $this->TrainingMembership->find('all', array(
                'conditions' => array(
                    'TrainingMembership.training_id' => $item['Training']['id'],
                    'TrainingMembership.account_id' => AuthComponent::user('AccountUser.0.account_id'),
                ),
                'contain'=>array(
                ),
            ));

            $this->request->data[$key]['Training']['department_id'] = array();
            $this->request->data[$key]['Training']['user_id'] = array();

            foreach($training as $trn){
                $this->request->data[$key]['Training']['is_required'] = $trn['TrainingMembership']['is_required'];
                $this->request->data[$key]['Training']['renewal'] = $trn['TrainingMembership']['renewal'];
                $this->request->data[$key]['Training']['training_id'] = $item['Training']['id'];
                $this->request->data[$key]['Training']['account_id'] = AuthComponent::user('AccountUser.0.account_id');

                if(!empty($trn['TrainingMembership']['department_id'])){
                    $this->request->data[$key]['Training']['department_id'][] = $trn['TrainingMembership']['department_id'];
                }

                if(!empty($trn['TrainingMembership']['user_id'])){
                    $this->request->data[$key]['Training']['user_id'][] = $trn['TrainingMembership']['user_id'];
                }
            }

        }
        */

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);
        #pr($trainings);
        #exit;

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
        #$this->set('classRoom', $classRoom);

        $this->set('trainings', $trainings);
        $this->set('settings', $this->TrainingMembership->required());

    }

	public function details($id=null) {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $trainings = $this->TrainingMembership->find('first', array(
            'conditions'=>array(
                'TrainingMembership.training_id' => $id,

            ),
            'contain'=>array(
                'Training'=>array(
                    'TrainingFile'=>array(

                    ),
					'TrainingClassroom'=>array(
                        'conditions'=>array(
                            'TrainingClassroom.account_id' => $account_ids
                        ),
                        'Instructor'=>array(),
                        'TrainingClassroomDetail'=>array(),
                        'order'=>array('TrainingClassroom.date' => 'DESC')
                    )
                ),
                'Department'=>array(
                    'fields'=>array(
                        'Department.id',
                        'Department.name'
                    )
                ),
                'CreatedBy'=>array(
                    'fields'=>array(
                        'CreatedBy.id',
                        'CreatedBy.first_name',
                        'CreatedBy.last_name',
                    )
                ),
                'RequiredUser'=>array(
                    'fields'=>array(
                        'RequiredUser.id',
                        'RequiredUser.first_name',
                        'RequiredUser.last_name',
                    )
                )
            ),
            'limit' => 15,
            'order'=>array('Training.name'=> 'asc'),
            'group'=>array(
                'TrainingMembership.training_id',
                'TrainingMembership.account_id',
            )
        ));

		$classRooms = $this->TrainingClassroom->find('all', array(
            'conditions'=>array(
                'TrainingClassroom.account_id' => $account_ids,
                'TrainingClassroom.training_id' => $id,
			),
            'contain'=>array(
                'Training'=>array(

                ),
				'TrainingClassroomDetail'=>array(

                ),
            ),
            'order'=>array('TrainingClassroom.date' => 'DESC'),
        ));


        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);
        #pr($trainings);
        #exit;

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
        #$this->set('classRoom', $classRoom);

        $this->set('trn', $trainings);
        $this->set('classRooms', $classRooms);
        $this->set('settings', $this->TrainingMembership->required());

    }

    public function library($cat=null) {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        #pr($training_ids);
        #exit;
        $this->Paginator->settings = array(
            'conditions' => array(

            ),
            'contain'=>array(
                'Author'=>array(
                    'fields'=>array('Author.id', 'Author.first_name', 'Author.last_name')
                ),
                'Account'=>array(
                    'fields'=>array(
                        'Account.id',
                        'Account.name',
                    )
                ),
                'TrainingMembership'=>array(
                    'Account'=>array(
                        'fields'=>array(
                            'Account.id',
                            'Account.name'
                        )
                    ),
                    'Department'=>array(
                        'fields'=>array(
                            'Department.id',
                            'Department.name'
                        )
                    )
                ),
                'TrnCat'=>array(
                    'TrainingCategory'=>array(
                        'fields'=>array(
                            'TrainingCategory.id',
                            'TrainingCategory.name',
                        )
                    ),
                    'fields'=>array(
                        'TrnCat.training_id',
                        'TrnCat.training_category_id',
                    )
                ),
                'TrainingFile'=>array(
                ),
                'TrainingMembership'=>array()
            ),
            'limit' => 100,
            'order'=>array('Training.name'=> 'asc'),
        );

        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array(
                'OR'=>array(
                    array('Training.name LIKE' => '%'.$this->request->data['Search']['q'].'%', ),
                    array('Training.description LIKE' => '%'.$this->request->data['Search']['q'].'%' )
                )
            ));

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$option);
        }

        if(!is_null($cat)){
            $training_ids = $this->TrnCat->getTrainingIds($cat);

            $options = array(
                'conditions'=>array(
                    'Training.id'=>$training_ids
                )
            );

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        }

        if(AuthComponent::user('Role.permission_level') <= 40){
            $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

            $options = array(
                'conditions'=>array(
                    'OR'=> array(
                        array(
                            'AND'=>array(
                                'Training.is_active'=>1,
                                'Training.is_public'=>1,
                            ),
                        ),
                        array(
                            'Training.account_id' => $account_ids
                        ),
                    ),
                )
            );

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        }

        #pr($this->Paginator->settings);
        #exit;

        $trainings = $this->Paginator->paginate('Training');
        #pr($trainings);
        #exit;
        $this->set('trainings', $trainings);
        $this->set('cat', $cat);
        $this->set('trnCat', $this->TrainingCategory->pickList());
    }

    public function view($id=null){
        $options = array();

        if(AuthComponent::user('Role.permission_level') <= 30){
            $option = array('conditions'=>array('TrainingRecord.User.account_id' => $this->Auth->user('account_id'), 'Role.lft >' => $this->Auth->user('Role.lft'), 'Role.rght <' => $this->Auth->user('Role.rght')));
            $options = array_merge_recursive($options,$option);
        }

        $training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $id,
            ),
            'contain'=>array(
                'Status'=>array(),
                'TrainingFile'=>array(),
                'TrnCat'=>array(
                    'TrainingCategory'=>array()
                ),
                'TrainingMembership'=>array()
            ),
        ));

        #pr($training);
        #exit;
        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('training', $training);
        $this->set('trnCat', $this->TrainingCategory->pickList());

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);


    }

    public function addToAccount($trn_id=null){
        $this->Training->id = $trn_id;
        if (!$this->Training->exists()) {
            throw new NotFoundException(__('Invalid Training'));
        }

        $c=0;
        $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
        $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
        $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
        $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
        $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];

        if(!empty($this->request->data['Training']['department_id'])){
            foreach($this->request->data['Training']['department_id'] as $dept){
                $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                $this->request->data[$c]['TrainingMembership']['department_id'] = $dept;
                $c++;
            }
        }

        if(!empty($this->request->data['Training']['user_id'])){
            foreach($this->request->data['Training']['user_id'] as $user){
                $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                $this->request->data[$c]['TrainingMembership']['user_id'] = $user;

                $c++;
            }
        }

        unset($this->request->data['Training']);

        if ($this->TrainingMembership->saveAll($this->request->data)) {
            $this->Flash->alertBox(
                'Training Has Been Added To Your Account',
                array( 'params' => array( 'class'=>'alert-success' ))
            );

            $this->redirect(array('controller'=>'Trainings', 'action'=>'index'));
        }else{
            $this->Flash->alertBox(
                'Training Could Not Be Added To Your Account',
                array( 'params' => array( 'class'=>'alert-danger' ))
            );

            $this->redirect(array('controller'=>'Trainings', 'action'=>'library'));
        }
    }

    public function editAccount($trn_id=null){
        $this->Training->id = $trn_id;
        if (!$this->Training->exists()) {
            throw new NotFoundException(__('Invalid Record Id'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $c = 0;

            $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
            $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
            $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
            $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
            $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];

            if(!empty($this->request->data['Training']['department_id'])){
                foreach($this->request->data['Training']['department_id'] as $dept){
                    $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                    $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                    $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                    $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                    $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                    $this->request->data[$c]['TrainingMembership']['department_id'] = $dept;
                    $c++;
                }
            }

            if(!empty($this->request->data['Training']['user_id'])){
                foreach($this->request->data['Training']['user_id'] as $user){
                    $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                    $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                    $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                    $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                    $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                    $this->request->data[$c]['TrainingMembership']['user_id'] = $user;

                    $c++;
                }
            }

            //delete all records, recreate
            $this->TrainingMembership->deleteAll(
                array(
                    'TrainingMembership.training_id' => $trn_id,
                    'TrainingMembership.account_id' => $this->request->data['Training']['account_id']
                ),
                false
            );

            unset($this->request->data['Training']);

            if ($this->TrainingMembership->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'Training Settings Have Been Updated',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            $this->redirect(array('controller'=>'Trainings', 'action'=>'index'));
        }

        $training = $this->TrainingMembership->find('all', array(
            'conditions' => array(
                'TrainingMembership.training_id' => $trn_id,
                'TrainingMembership.account_id' => AuthComponent::user('AccountUser.0.account_id'),
            ),
            'contain'=>array(
            ),
        ));

        //Get all
        #pr($training);
        #exit;
        $this->request->data['Training']['department_id'] = array();
        $this->request->data['Training']['user_id'] = array();

        foreach($training as $trn){
            $this->request->data['Training']['is_required'] = $trn['TrainingMembership']['is_required'];
            $this->request->data['Training']['renewal'] = $trn['TrainingMembership']['renewal'];
            $this->request->data['Training']['training_id'] = $trn_id;
            $this->request->data['Training']['account_id'] = AuthComponent::user('AccountUser.0.account_id');

            if(!empty($trn['TrainingMembership']['department_id'])){
                $this->request->data['Training']['department_id'][] = $trn['TrainingMembership']['department_id'];
            }

            if(!empty($trn['TrainingMembership']['user_id'])){
                $this->request->data['Training']['user_id'][] = $trn['TrainingMembership']['user_id'];
            }
        }

        #pr($this->request->data);
        #exit;

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
    }

    public function roster($trn_id = null){
        if ($this->request->is('post') || $this->request->is('put')) {
        }


        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
        $this->set('training_id', $trn_id);

    }

    public function createClassroom($trn_id = null, $name=null){

        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($this->request->data['TrainingClassroom']['date'])){
                $this->request->data['TrainingClassroom']['date'] = strtotime('now');
            }else{
                $this->request->data['TrainingClassroom']['date'] = date('Y-m-d', strtotime($this->request->data['TrainingClassroom']['date']));
            }

            if(empty($this->request->data['TrainingClassroomDetail']['user_id'])){
                 $this->Flash->alertBox(
                    'Please Build a Roster!',
                    array(
                        'params' => array(
                            'class'=>'alert-danger'
                        )
                    )
                );

                return $this->redirect(array('controller'=>'Trainings', 'action' => 'details', $trn_id ));

            }

            foreach($this->request->data['TrainingClassroomDetail']['user_id'] as $k=>$r){
                $this->request->data['TrainingClassroomDetail'][$k]['user_id'] = $r;
                $this->request->data['TrainingClassroomDetail'][$k]['did_attend'] = 1;
            }
            unset($this->request->data['TrainingClassroomDetail']['user_id'], $this->request->data['TrainingClassroomDetail']['did_attend']);

            if ($this->TrainingClassroom->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'Training Classroom Has Been Create',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            return $this->redirect(array('controller'=>'Trainings', 'action' => 'details', $trn_id ));
        }


        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);

        $this->set('training_id', $trn_id);
        $this->set('name', $name.' [ '.date('m/d/Y', strtotime('now')).' ]');
        $this->set('account_id', AuthComponent::user('AccountUser.0.account_id'));

    }

    public function upload(){
        $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
        $arr_ext = array('jpg', 'jpeg', 'gif'); //set allowed extensions

        //only process if the extension is valid
        if(in_array($ext, $arr_ext)){
        }
    }
}