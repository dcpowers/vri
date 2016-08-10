<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingExempt extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $actsAs = array('Containable', 'Multivalidatable');
    
    public $belongsTo = array( 
        'Training' => array(
            'className' => 'Training',
            'foreignKey' => 'training_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ExemptBy' => array(
            'className' => 'User',
            'foreignKey' => 'exempt_by_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function findExempt($associate_id = null){
        if ( empty( $associate_id ) ) {
            throw new InvalidArgumentException('Invalid arguments calling ::findCompleted()' );
        }
        return $this->find('all', array(
            'conditions'=> array(
                'not'=> array(
                    'completed_on'=>null
                ),
                $this->alias.'.user_id' => $associate_id,
                'expires_on <' =>  date(DATE_MYSQL_DATETIME, strtotime( '+' . $days . ' days', time() ) )
            ),
            'order'=>'completed DESC'
        ));
        
    }
}
