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
        
        $this->set('breadcrumbs', array(
            array('title'=>'Training', 'link'=>array('controller'=>'Trainings', 'action'=>'index')),
        ));
    }
    
    public function index() {
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
        
    }
}