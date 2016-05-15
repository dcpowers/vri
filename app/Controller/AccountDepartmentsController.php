<?php
App::uses('AppController', 'Controller');
/**
 * GroupMemberships Controller
 *
 */
class AccountDepartmentsController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->allow(
        );
    }
}
