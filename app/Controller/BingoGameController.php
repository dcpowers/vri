<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class BingoGameController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array(
        'User',
		'Accident',
		'BingoGame',
		'BingoBall',
		'BingoGameBall'
    );

    #public $helpers = array('Session');
    public function isAuthorized($user = null) {
        return true;
    }
    #public $components = array('RequestHandler', 'Paginator');

    public function pluginSetup() {
        $user = AuthComponent::user();
        $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
        $link = array();
        //These Two Lines are Required
        parent::pluginSetup();
        $this->set('title_for_layout', 'Bingo');
        #Configure::write('App.SiteName', 'Employees - iWorkZone');
    }

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function drawn($id = null) {
		$bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.id' => $id,
			),
			'contain'=>array(
				'BingoGameBall'=>array(
					'Ball'=>array(),
					'order'=>array('BingoGameBall.date' => 'DESC')
				),
			),
		));

		$balls = $this->BingoBall->find('all', array(
            'conditions' => array(
            ),
			'contain'=>array(
			),
		));

		$c = 0;
		foreach($balls as $v){
			$var = str_split($v['BingoBall']['ball']);
			$data[$var[0]][$v['BingoBall']['id']]['num'] = substr($v['BingoBall']['ball'], 1);
			$data[$var[0]][$v['BingoBall']['id']]['id'] = $v['BingoBall']['id'];

			$quickList[$v['BingoBall']['id']] = $v['BingoBall']['ball'];
			$c++;
		}
		#pr($data);
		#pr($amount);
		#pr($date);
		#pr($bingo);
		#exit;
		$drawn = Hash::extract($bingo, 'BingoGameBall.{n}.Ball.id');
		unset($bingo['BingoGameBall']);

		$this->set('bingo', $bingo);
		$this->set('drawn', $drawn);
		$this->set('balls', $data);
		$this->set('quickList', $quickList);
	}

	public function newdrawn($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['BingoGameBall']['date'] = date('Y-m-d', strtotime('now'));
			if ($this->BingoGameBall->saveAll($this->request->data)) {
            	$this->Flash->alertBox(
	            	'Bingo Has Been Updated',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
				$this->Flash->alertBox(
	            	'There Was An Error. Please, Try Again!',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'dashboard', 'action'=>'index'));
		}

		$bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.id' => $id,
			),
			'contain'=>array(
				'BingoGameBall'=>array(
					'Ball'=>array(),
					'order'=>array('BingoGameBall.date' => 'DESC')
				),
			),
		));

		$drawn = Hash::extract($bingo, 'BingoGameBall.{n}.Ball.id');
		unset($bingo['BingoGameBall']);

		$ballList = $this->BingoBall->find('list', array(
            'conditions' => array(
				'BingoBall.id !=' => $drawn
            ),
			'contain'=>array(
			),
			'fields'=>array('BingoBall.id', 'BingoBall.Ball')
		));

		$allballs = $this->BingoBall->find('list', array(
            'conditions' => array(
			),
			'contain'=>array(
			),
			'fields'=>array('BingoBall.id', 'BingoBall.Ball')
		));

		#pr($ballList);
		#pr($amount);
		#pr($date);
		#pr($bingo);
		#exit;

		$this->set('drawn', $drawn);
		$this->set('bingo', $bingo);
		$this->set('allballs', $allballs);
		$this->set('bingo_game_id', $id);
		$this->set('ballList', $ballList);
	}

	public function add() {
		$account_id = AuthComponent::user('AccountUser.0.account_id');

		$bingo = $this->BingoGame->find('first', array(
	    	'conditions' => array(
	        	'BingoGame.account_id' => $account_id,
				'BingoGame.end_date' =>null
	        ),
			'contain'=>array(
			),
		));

		if(empty($bingo)){
			$this->request->data['BingoGame']['start_date'] = date('Y-m-d', strtotime('now'));
			$this->request->data['BingoGame']['account_id'] = $account_id;

			if ($this->BingoGame->saveAll($this->request->data)) {
            	$this->Flash->alertBox(
	            	'A New Game Has Started',
		            array( 'params' => array( 'class'=>'alert-success' ))
		        );
	        }else{
				$this->Flash->alertBox(
	            	'There Was An Error. Please, Try Again!',
		            array( 'params' => array( 'class'=>'alert-danger' ))
		        );
	        }
		}else{
			$this->Flash->alertBox(
	        	'There Is Already A Game Started',
		        array( 'params' => array( 'class'=>'alert-danger' ))
		    );
		}

		$this->redirect(array('controller'=>'dashboard', 'action'=>'index'));

        $this->autoRender = false;
    }

	public function getDashboard() {
		$account_id = AuthComponent::user('AccountUser.0.account_id');

		$dashboardData = $this->BingoGame->find('all', array(
            'conditions' => array(
                'BingoGame.account_id' => $account_id,
			),
			'contain'=>array(
				'BingoGameBall'=>array(
					'Ball'=>array(),
					'order'=>array('BingoGameBall.date' => 'DESC')
				),
				'Winner'=>array(
					'fields'=>array(
						'Winner.first_name',
						'Winner.last_name'
					)
				)

			),
			#'order'=>array('BingoGame.end_date' => 'ASC'),
		));

		#pr($dashboardData);
		#exit;

		return $dashboardData;
		exit;

	}

	public function safety(){
		$account_id = AuthComponent::user('AccountUser.0.account_id');

		#pr(AuthComponent::user('id'));

		$bingo = $this->BingoGame->find('all', array(
            'conditions' => array(
                'BingoGame.account_id' => $account_id,
				#'BingoGame.end_date' =>null
            ),
			'contain'=>array(
				'BingoGameBall'=>array(
					'Ball'=>array(),
					'order'=>array('BingoGameBall.date' => 'DESC')
				),
				'Winner'=>array(
					'fields'=>array(
						'Winner.first_name',
						'Winner.last_name'
					)
				)

			),
			'order'=>array('BingoGame.end_date' => 'ASC'),
			'limit' => 2
		));

		$this->BingoGame->virtualFields = array(
    		'the_sum' => 'SUM(BingoGame.amount)'
		);

		$totalAmount = $this->BingoGame->find('all', array(
            'conditions' => array(
                #'BingoGame.account_id' => $account_id,
				#'BingoGame.end_date' =>null
            ),
			'contain'=>array(
			),
			'fields'=>array('BingoGame.the_sum'),
		));

		unset($this->BingoGame->virtualFields);


		$ball = (!empty($bingo[0]['BingoGameBall'][0]['Ball']['ball'])) ? $bingo[0]['BingoGameBall'][0]['Ball']['ball'] : null ;
		$ballDate = (!empty($bingo[0]['BingoGameBall'][0]['Ball']['ball'])) ? CakeTime::format($bingo[0]['BingoGameBall'][0]['date'], '%b %e, %Y') : 'No Balls Drawn' ;

		$winner = (!empty($bingo[1]['Winner']['first_name'])) ? $bingo[1]['Winner']['first_name'].' '.$bingo[1]['Winner']['last_name']  : null ;
		$amount = (!empty($bingo[1]['BingoGame']['amount'])) ? '$'.$bingo[1]['BingoGame']['amount'] : '$0.00' ;
		$date = (!empty($bingo[1]['BingoGame']['end_date'])) ? CakeTime::format($bingo[1]['BingoGame']['end_date'], '%b %e, %Y') : null ;

		#pr($winner);
		#pr($amount);
		#pr($date);
		#pr($bingo);
		#exit;

		$accident = $this->Accident->find('first', array(
            'conditions' => array(
                'Accident.account_id' => $account_id
            ),
			'contain'=>array(
			),
			'order'=>array('Accident.date' => 'Desc'),
		));

		$diff = CakeTime::timeAgoInWords(
			$accident['Accident']['date'],
    		array('format' => 'F jS, Y', 'end' => '+10 year')
		);

        $info['accident_days'] = $diff;
		$info['ball'] = $ball;
		$info['date'] = $date;
		$info['ballDate'] = $ballDate;
		$info['winner'] = $winner;
		$info['amount'] = $amount;
		$info['totalAmount'] = '$'.$totalAmount[0]['BingoGame']['the_sum'];
		$info['currentGame'] = (!empty($bingo[0]['BingoGame']['id'])) ? $bingo[0]['BingoGame']['id'] : null ;

		unset($diff, $ball, $date, $ballDate, $winner, $amount, $totalAmount, $bingo);
		return $info;
		exit;
	}

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

}