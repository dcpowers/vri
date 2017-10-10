<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class JobTalentpattern extends AppModel {

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
		'JobPosting'
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
        ),
    );
    
    public function get_talent_patterns($group_id = null){
        $data = $this->find('all', array(
            'conditions' => array(
                'JobTalentpattern.group_id' => $group_id
            ),
            'contain' => array(
            )
        ));
        
        return $data;
    }
    
    public function pick_list($i1=null, $i2=null, $b1=null, $b2=null){
        $data = $this->find('list', array(
            'conditions'=>array(
                'JobTalentpattern.interest1' => $i1,
                'JobTalentpattern.interest2' => $i2,
                'JobTalentpattern.behavior1' => $b1,
                'JobTalentpattern.behavior2' => $b2
            ),
            'fields'=>array('JobTalentpattern.id')
        ));
        return $data;
    }
}
