<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Asset extends AppModel {
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
    public $belongsTo = array(
        'AssetType' => array(
            'className' => 'AssetType',
            'foreignKey' => 'asset_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Manufacturer' => array(
            'className' => 'Manufacturer',
            'foreignKey' => 'manufacturer_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Vendor' => array(
            'className' => 'Vendor',
            'foreignKey' => 'vendor_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'AssignedTo' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => 'CONCAT( AssignedTo.last_name, ", " , AssignedTo.first_name ) as full_name',
            'order' => ''
        ),
        'Status' => array(
            'className' => 'Setting',
            'foreignKey' => 'is_active',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public $validationSets = array( 
        'asset' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        )
    );
    
    public function pickListActive(){
        $data = $this->find('list', array(
            'conditions' => array(
                'Asset.is_active' => 1
            ),
            'contain'=>array(),
            'fields'=>array('Asset.id', 'Asset.name'),
            'order'=>array('Asset.name')
        ));
        return $data; 
    }
}