<?php
// app/Model/App.php
App::uses('AppModel', 'Model');

class Application extends AppModel {
    public $actsAs = array('Tree', 'Containable');

	public $hasMany = array(
        'EmailList'
	);

	public function get_app_id($controller = null){
		$app_id = $this->find('list', array(
            'conditions' => array(
                'Application.controller' => $controller
            ),
            'contain' => array(
            ),
			'fields'=>array('Application.id', 'Application.id')
        ));

		return $app_id;
	}
}