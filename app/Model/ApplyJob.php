<?php
App::uses('AppModel', 'Model');

class ApplyJob extends AppModel {
     public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'User',
        'JobPosting'=> array(
          'className' => 'JobPosting',
          'foreignKey' => 'job_posting_id',
          'dependent' => true
        ),
    );
    
    public $hasMany = array(
        'JobQuestionAnswer',
        'JobOffer',
        'CollaboraterNote'
        
    );
    
    public $hasAndBelongsToMany = array(
        
    ); 
}