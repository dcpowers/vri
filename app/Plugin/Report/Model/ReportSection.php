<?php
App::uses('ReportAppModel', 'Report.Model');

/**
 * Training Model
 *
 */

class ReportSection extends ReportAppModel {
/**
 * Display field
 *
 * @var string
 */
    public $displayField = 'title';
    
    public function getSections($id){
        $content = $this->find('all', array(
            'conditions'=>array(
                'ReportSection.report_id' => $id
            ),
            'order'=>array('ReportSection.ordering ASC')
        )); 
        foreach($content as $key=>$item){        
            $content[$key] = $item['ReportSection'];
            $content[$key]['parent_id'] = $id;
            
        }   
        return $content;
    }
    
}
