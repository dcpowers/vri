<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class TestRole extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
    public $actsAs = array('Containable');
    //public $table = array('pro_job_postings');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
        'AssignedTest'
    );
    
    public function pick_list(){
        $data = $this->find('list', array(
            'conditions' => array(
            ),
            'contain'=>array(
            ),
            
        ));
        
        return $data;
    }
}