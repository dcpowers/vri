<?php
App::uses('AppModel', 'Model');

/**
* Company Model
*
* @property Visitor $Visitor
*/

class TrainingQuiz extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
    public $displayField = 'id';

    public $validate = array(
        'training_id' => 'notEmpty'
    );

//The Associations below have been created with all possible keys, those that are not needed can be removed

    /** 
    * belongsTo associations
    *
    * @var array
    */
    public $belongsTo = array(  
        'Training'=>array(     
            'className'=>'Training',
            'foreignKey'=>'training_id'
        )
    );
}
