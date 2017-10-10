<?php
App::uses('AppController', 'Controller');

/**
 * Apps Controller
 *
 */
class JobQuestionDetailsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
    public $scaffold = 'admin';

    //public $uses = array('Application','ApplicationSwitch');
    
    //beforeFilter callback
    public function beforeFilter( ) {
        parent::beforeFilter();

        
    }
}
