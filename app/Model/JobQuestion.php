<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class JobQuestion extends AppModel {

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
		'JobPosting',
        'JobQuestionDetail',
        'JobQuestionAnswer'
        
    );
    
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        )
    );
    
    public function get_questions($group_id = null){
        $data = $this->find('all', array(
            'conditions' => array(
                'JobQuestion.group_id' => $group_id
            ),
            'contain' => array(
            )
        ));
        
        return $data;
    }

}
