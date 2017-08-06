<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class TestSchedule extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

    public $actsAs = array('Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'AssignedTest',
        'BlindTest'
	);

    public $belongsTo = array(
        'Test',
		'User'
    );

    public function getAssignedTestingInfo($id=null, $blindTestId=null) {
        $data = $this->find('first', array(
            'conditions' => array(
                'TestSchedule.link_num' => $id
            ),
            'contain'=>array(
                'BlindTest'=>array(
                    'conditions' =>array(
                        'BlindTest.id' =>$blindTestId
                    )
                ),
                'Test'=>array(
                    'fields'=>array('Test.lft', 'Test.rght')
                )
            )
        ));

        return $data;
    }



}
