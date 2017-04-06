<?php
App::uses('ReportAppModel', 'Report.Model');

/**
 * Training Model
 *
 */

class ReportSwitch extends ReportAppModel {
    public $displayField = 'title';
    
    public $belongsTo = array( 
        'Report' => array(
            'className' => 'Report.Report',
            'foreignKey' => 'report_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Group',
        'Test' => array(
            'className' => 'Test',
            'foreignKey' => 'test_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function getReports($groupIds=null, $reportIds=null){
        #pr($groupIds);
        #pr($reportIds);
        
        $conditions = array(
            'conditions'=>array(
                'ReportSwitch.report_id' => $reportIds,
                'ReportSwitch.group_id' => $groupIds,
            ),
            'fields'=>array('Report.id','Report.action','Report.name', 'Report.is_user_report'),
            'group'=>array('Report.id')
        );
        
        $content = $this->find('all', $conditions);
        #pr($content);
        #exit;
        return $content;
    }
}
