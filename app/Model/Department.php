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
        'TrainingMembership',
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
    
    public function pickListById( $ids=null ) {
        $dataArr = array();
		
		$this->virtualFields['name'] = 'CONCAT(name, " ( " ,abr, " ) ")';
		
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.is_active'=>1,
                $this->alias.'.id'=>$ids
            ),
            'order'=>$this->alias.'.name asc',
            'fields'=>array($this->alias.'.id', $this->alias.'.name')
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('list', $find_options );
		
		return $recs;
	
    }
    
    public function getRecord( $id=null ) {
        $dataArr = array();
		
		$find_options = array(
            'conditions'=>array(
                $this->alias.'.id'=>$id
            ),
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('first', $find_options );
		
		return $recs;
	
    }
    
    
    public function pickListAll(){
        $data = $this->find('all', array(
            'conditions' => array(
                #'TrainingCategory.is_active' => 1
            ),
            'contain'=>array(),
            #'fields'=>array('TrainingCategory.id', 'TrainingCategory.name'),
            'order'=>array('Department.name')
        ));
        
        return $data; 
    }
}