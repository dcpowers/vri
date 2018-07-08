<?php
App::uses('AppController', 'Controller');
    
    /**
    * GroupMemberships Controller
    *
    */
    
class TrainingMembershipsController extends AppController {
    public $uses = array(
        'User',
        'TrainingMembership',
    );
    
    public $profileUploadDir = 'img/profiles';
    
    public function isAuthorized($user = null) {
        return true;
    }
    
    #public $helpers = array('Session');
    
    public $components = array('Search.Prg', 'RequestHandler', 'Paginator');
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
        $link = array();
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.SiteName', 'Employees');
    }
    
    public function getAccountTraining($acct_id = null){
		$trn = $this->TrainingMembership->find('all', array(
            'conditions' => array(
                'TrainingMembership.account_id' => $acct_id
            ),
            'contain' => array(
                'Training'=>array(
                    'fields'=>array(
                        'Training.id',
                        'Training.name'
                    )
                ),
                'ReqDept'=>array(
                    'fields'=>array(
                        'ReqDept.name'
                    )
                ),
                'ReqUser'=>array(
                    'fields'=>array(
                        'ReqUser.first_name',
                        'ReqUser.last_name'
                    )
                )
			),
		));
		
		$trainings = array();
		if(!empty($trn)){
			foreach($trn as $key=>$trn){
				$index = $trn['Training']['name'];

                $tvalue[$index][] = $trn;
                $tkeysort[$index] = $trn['Training']['name'];

                $trainings = array_merge($trainings,$tvalue);
            }

            unset($trn['TrainingMembership']);

            array_multisort($tkeysort, SORT_ASC, $trainings);
        }
        
        $this->set(compact(
            'trainings'
        ));
        
	}
    
}