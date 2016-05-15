<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Department extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
    public function beforeFilter() {
        parent::beforeFilter();
        
    }

    public $actsAs = array('Containable', 'Multivalidatable');
    
    public function parentNode() {
        return null;
    }
    
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $hasMany = array(
        'AccountDepartment',
        'DepartmentUser',
        'User'    
    );
    
    public function pickList( ) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.is_active'=>1
            ),
            'order'=>$this->alias.'.name asc'
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('all', $find_options );

        foreach ( $recs as $key=>$rec ) {
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['name'])) . ' ( ' . ucwords( strtolower($rec[$this->alias]['abr'] .' )'));
        }
        return $dataArr;
    }
}