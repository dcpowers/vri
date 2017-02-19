<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class BingoGameBall extends AppModel {
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

	public $belongsTo = array(
        'Game' => array(
            'className' => 'BingoGame',
            'foreignKey' => 'bingo_game_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'Ball' => array(
            'className' => 'BingoBall',
            'foreignKey' => 'bingo_ball_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
	);
}