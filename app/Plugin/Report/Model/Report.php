<?php
App::uses('ReportAppModel', 'Report.Model');

/**
 * Training Model
 *
 */

class Report extends ReportAppModel {
/**
 * Display field
 *
 * @var string
 */
    public $actsAs = array('Tree');
    public $displayField = 'name';
    //public $table = pro_;
    
    public $hasMany = array(
        'ReportSwitch'=>array(
            'className'    => 'Report.ReportSwitch',
            'foreignKey'   => 'report_id',
        ),
    );
    
    public $belongsTo = array( 
        'Test' => array(
            'className' => 'Test',
            'foreignKey' => 'test_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function getReport($id){
        $parent = $this->find('first', array('conditions' => array('Report.id' => $id)));
            #$this->Group->recursive = 1;
        $content = $this->find('threaded', array(
            'conditions' => array(
                'Report.lft >=' => $parent['Report']['lft'], 
                'Report.rght <=' => $parent['Report']['rght']
            ),
            'order'=>array('Report.lft')
        ));
        return $content;
    }
    
    public function getIds($id){
        $content = $this->find('list', array(
            'conditions' => array(
                'Report.test_id' => $id 
            ),
            'fields'=>array('Report.id')
        ));
        
        return $content;
    }
}
