<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccidentAreaLov extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';

    public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $hasMany = array(
        'AccidentArea',
    );

    public function pickList(){
        $dataArr = array();

        $items = $this->find('list', array(
            'conditions' => array(
                $this->alias.'.is_active'=>1
            ),
            'fields'=>array(
                $this->alias.'.id',
                $this->alias.'.name',
            )
        ));

        return $items;
    }
}
