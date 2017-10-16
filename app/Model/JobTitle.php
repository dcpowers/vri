<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class JobTitle extends AppModel {
/**
 * Display field
 *
 * @var string
 */
    public function beforeFilter() {
        parent::beforeFilter();

        // For CakePHP 2.1 and up
        $this->Auth->allow('add');
    }

    public function parentNode() {
        return null;
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $actsAs = array('Containable', 'Multivalidatable');

    public $hasMany = array(
		#'Job',
    );
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Account',
    );
}
