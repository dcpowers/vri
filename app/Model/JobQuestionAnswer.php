<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class JobQuestionAnswer extends AppModel {

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
	public $belongsTo = array(
        'JobQuestionDetail' => array(
            'className' => 'JobQuestionDetail',
            'foreignKey' => 'job_question_detail_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ApplyJob' => array(
            'className' => 'ApplyJob',
            'foreignKey' => 'apply_job_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        
    );
    
   

}
