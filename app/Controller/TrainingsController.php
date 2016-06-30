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
        'TrainingCategory' 
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
        
        $this->Paginator->settings = array(
            'conditions' => array(     
                #'Account.id' => $search_ids,
            ),
            'contain'=>array(
                'UpdatedBy'=>array(
                    'fields'=>array('UpdatedBy.id', 'UpdatedBy.first_name', 'UpdatedBy.last_name')
                ),
                'Status'=>array(),
                'Account'=>array(),
                'Department'=>array(),
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
            ),
            'limit' => 100,
            'order'=>array('Training.name'=> 'asc'),
        );    
        
        $trainings = $this->Paginator->paginate('Training');
        #pr($trainings);
        #exit;
        $this->set('trainings', $trainings);
    }
    
    public function view($id=null){
        $training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(     
                'Training.id' => $id,
            ),
            'contain'=>array(
                'UpdatedBy'=>array(
                    'fields'=>array('UpdatedBy.id', 'UpdatedBy.first_name', 'UpdatedBy.last_name'),
                ),
                'Status'=>array(),
                'Account'=>array(),
                'Department'=>array(),
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
                'TrainingRecord'=>array(
                    'User'=>array(
                        'fields'=>array(
                            'User.id', 'User.first_name', 'User.last_name'
                        ),
                    )
                )
            ),
        ));
        
        #pr($training);
        #exit;
        $this->set('training', $training);
        $this->set('settings', $this->Training->yesNo());
        $this->set('trnCat', $this->TrainingCategory->pickList());
    }
}