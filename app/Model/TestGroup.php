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

    public function fullList( $options = array() ) {
		$conditions = array(
            'conditions'=>array(
            	#'TestGroup.account_id !=' => 0,
            	'TestGroup.parent_id' => null
            ),
            'contain'=>array(
            ),
            'fields'=>array(
				'TestGroup.id',
				'TestGroup.name','TestGroup.is_active','TestGroup.schedule_type','TestGroup.credits','TestGroup.cal_function'),
            'order'=>'TestGroup.name asc'
        );
        #pr($conditions);
		$cond = array_merge($conditions, $options);

        #pr($cond);

		$recs = $this->find('all', $cond );
        return $recs;
    }


}
