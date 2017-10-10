<?php
App::uses('AppModel', 'Model');
/**
 * Article Model
 *
 * @property ArticlePivotDetail $ArticlePivotDetail
 */
class JobOffer extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
    public $actsAs = array('Containable');

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ApplyJob',
        'User'
        
	);
}