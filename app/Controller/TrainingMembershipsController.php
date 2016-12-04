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
    
    
}