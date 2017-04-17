<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class TestGroup extends AppModel {

/**
 * Display field
 *
 * @var string
 */
    public $useTable = 'tests';

	public $displayField = 'name';

    public $actsAs = array('Tree', 'Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'AssignedTest'
	);

    public $belongsTo = array(

    );

    public function fullList( $id = null ) {
        $conditions = array(
            'conditions'=>array(
                'TestGroup.account_id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array('TestGroup.id','TestGroup.name','TestGroup.is_active','TestGroup.schedule_type','TestGroup.credits','TestGroup.cal_function'),
            'order'=>'TestGroup.name asc'
        );
        $recs = $this->find('all', $conditions );
        return $recs;
    }


}
