<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Vendor extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
    public function beforeFilter() {
        parent::beforeFilter();
        
    }

    public function parentNode() {
        return null;
    }
    
    public $actsAs = array('Containable', 'Multivalidatable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    /**
     * belongsTo associations
     *
     * @var array
    */
    public $hasMany = array(
        'Asset'
    );
    
    public $validate = array( 
        'name' => array(
            'rule' => 'notBlank',
            'required'=>true,
            'message' => 'This field cannot be left blank'
        )
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
            $dataArr[$rec[$this->alias]['id']] = ucwords( strtolower($rec[$this->alias]['name']));
        }
        return $dataArr;
    }
}