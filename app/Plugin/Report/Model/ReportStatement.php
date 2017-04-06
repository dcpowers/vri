<?php
App::uses('ReportAppModel', 'Report.Model');

/**
 * Training Model
 *
 */

class ReportStatement extends ReportAppModel {
    public $displayField = 'title';
    public $table = 'pro_report_statements';
    
    public function getStatements($data){
        $conditions = array(
            'conditions'=>array(
                'ReportStatement.min_score >=' => $data['min_score'],
                'ReportStatement.max_score <=' => $data['min_score'],
                'ReportStatement.reportsection_id' => $data['section_id']
            )
        );
        
        $content = $this->find('first', $conditions);
        
        return $content;
    }
    
    public function getAllStatements($id=null, $parent_id=null){
        $content = $this->find('all', array(
            'conditions'=>array(
                'ReportStatement.reportsection_id' => $id
            ),
        ));
        if(!empty($content)){
            foreach($content as $key=>$item){
                #pr($item);
                #pr($key);
                #exit;
                    
                $newContent[$key] = $item['ReportStatement'];
                $newContent[$key]['parent_id'] = $parent_id;
                
                unset(
                    $newContent[$key]['id'], 
                    $newContent[$key]['reportsection_id']
                );
            }
            
            
            return $newContent;
        }
    }
}
