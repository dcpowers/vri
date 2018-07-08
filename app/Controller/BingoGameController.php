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
		'BingoGameBall',
		'AccountUser',
		'Award',
		'Application',
		'EmailList'
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
		if(empty($bingo)){
			$bingo['BingoGame']['start_date'] = 'Not Started';
		}
		#exit;
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

	public function bingo($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {

			$user = $this->User->find('first', array(
	    		'conditions' => array(
	        		'User.id' => $this->request->data['BingoGame']['user_id']
				),
				'contain'=>array(
					'AccountUser'=>array(),
					'DepartmentUser'=>array(),
				),

			));

			$account_id = Set::extract( $user, '/AccountUser/account_id' );
			$dept_id = Set::extract( $user, '/DepartmentUser/department_id' );

			$this->request->data['Award']['verified_by'] = AuthComponent::user('id');
			$this->request->data['Award']['verified_date'] = date('Y-m-d h:i:s',strtotime('now'));
    		$this->request->data['Award']['date'] = date('Y-m-d', strtotime('now'));
			$this->request->data['Award']['account_id'] = $account_id[0];
			$this->request->data['Award']['department_id'] = $dept_id[0];
			$this->request->data['Award']['award_type_id'] = 2;
			$this->request->data['Award']['user_id'] = $this->request->data['BingoGame']['user_id'];
			$this->request->data['Award']['amount'] = $this->request->data['BingoGame']['amount'];

			$this->request->data['BingoGame']['end_date'] = date('Y-m-d', strtotime('now'));

			if ($this->BingoGame->save($this->request->data['BingoGame'])) {
                if (env('SERVER_NAME') == 'vrifm.com'){
					$this->send_mail($this->request->data['BingoGame']['id']);
				}
				$this->Award->create();
				$this->Award->save($this->request->data['Award']);
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
			),
		));

		$this->set('amount',$bingo['BingoGame']['amount']);
		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $this->set('users', $this->AccountUser->pickListActive($account_ids));
		$this->set('bingo_game_id', $id);
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

        //Amount Amount
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
			'order'=>array('BingoGame.start_date' => 'DESC'),
		));

		#pr($dashboardData);
		#exit;

		return $dashboardData;
		exit;

	}

	public function safety(){
		$account_id = AuthComponent::user('AccountUser.0.account_id');

		#pr(AuthComponent::user('id'));
        $current_amount = $this->getCurrentAmount();

		$current_bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.account_id' => $account_id,
				'BingoGame.end_date' =>null
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
		));

		$last_bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.account_id' => $account_id,
				'BingoGame.user_id !=' =>null
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
			'order'=>array('BingoGame.end_date' => 'DESC'),
		));
		#pr($last_bingo);
		#exit;
		$this->BingoGame->virtualFields = array(
    		'the_sum' => 'SUM(BingoGame.amount)'
		);

		$totalAmount = $this->BingoGame->find('all', array(
            'conditions' => array(
                #'BingoGame.account_id' => $account_id,
				'BingoGame.end_date !=' =>null
            ),
			'contain'=>array(
			),
			'fields'=>array('BingoGame.the_sum'),
		));
		#pr($totalAmount);
		#exit;
		unset($this->BingoGame->virtualFields);


		$ball = (!empty($current_bingo['BingoGameBall'][0]['Ball']['ball'])) ? $current_bingo['BingoGameBall'][0]['Ball']['ball'] : null ;
		$ballDate = (!empty($current_bingo['BingoGameBall'][0]['Ball']['ball'])) ? CakeTime::format($current_bingo['BingoGameBall'][0]['date'], '%b %e, %Y') : 'No Balls Drawn' ;
        $current_amount = (!empty($current_bingo['BingoGame']['amount'])) ? $current_bingo['BingoGame']['amount'] : '0';

		$winner = (!empty($last_bingo['Winner']['first_name'])) ? $last_bingo['Winner']['first_name'].' '.$last_bingo['Winner']['last_name']  : null ;
		$amount = (!empty($last_bingo['BingoGame']['amount'])) ? $last_bingo['BingoGame']['amount'] : '0' ;
		$date = (!empty($last_bingo['BingoGame']['end_date'])) ? CakeTime::format($last_bingo['BingoGame']['end_date'], '%b %e, %Y') : null ;

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
		
		if(!empty($accident)){
			$diff = floor((strtotime('now') - strtotime($accident['Accident']['date'])) /86400);
        	if($diff == 0){$diff = 'Today';}
        	if($diff == 1){$diff = 'Yesterday';}
        	if($diff >= 2){$diff = $diff.' Days Ago';}
		} else {
			$diff = null;
		}
		/*
		$diff = CakeTime::timeAgoInWords(
			$accident['Accident']['date'],
    		array('format' => 'F jS, Y', 'end' => '+10 year')
		);
        */
        $info['accident_days'] = $diff;
		$info['ball'] = $ball;
		$info['date'] = $date;
		$info['ballDate'] = $ballDate;
		$info['winner'] = $winner;
		$info['amount'] = $amount;
		$info['current_amount'] = $current_amount;
		$info['totalAmount'] = $totalAmount[0]['BingoGame']['the_sum'];
		$info['currentGame'] = (!empty($current_bingo['BingoGame']['id'])) ? $current_bingo['BingoGame']['id'] : null ;

		unset($diff, $ball, $date, $ballDate, $winner, $amount, $totalAmount, $bingo, $current_amount);
		return $info;
		exit;
	}

	public function getCurrentAmount(){
		$account_id = AuthComponent::user('AccountUser.0.account_id');

		$userList = $this->User->find('list', array(
            'conditions' => array(
                'User.account_id' => $account_id,
                'User.is_active' => 1,
                'User.is_bingo' => 1
            ),
			'contain'=>array(
			),
		));
		$user_count = count($userList);
		$lastAccident = $this->Accident->find('first', array(
            'conditions' => array(
                'Accident.account_id' => $account_id,
            ),
			'contain'=>array(
			),
			'order'=>array('Accident.date' => 'DESC'),
			'fields'=>array('Accident.date')
		));

		$current_bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.end_date' => null
            ),
			'contain'=>array(
			),
			'fields'=>array('BingoGame.id', 'BingoGame.start_date')
		));

        $accident = (!empty($lastAccident['Accident']['date'])) ? $lastAccident['Accident']['date'] : 0 ;
        $bingo = (!empty($current_bingo['BingoGame']['start_date'])) ? $current_bingo['BingoGame']['start_date'] : 0 ;

		if($accident == 0 && $bingo == 0){
			$days = 0;
		}else{
			if($bingo >= $accident){
				$days = floor((strtotime('now') - strtotime($bingo)) /86400);
			}else{
				$days = floor((strtotime('now') - strtotime($accident)) /86400);
			}
		}

		$amount = round(($days * $user_count) * .10, 2);
		#pr($days);
		#pr($user_count);
		#pr($amount);
		#exit;
		if(!empty($current_bingo['BingoGame']['id'])){
			$this->request->data['BingoGame']['id'] = $current_bingo['BingoGame']['id'];
			$this->request->data['BingoGame']['amount'] = $amount;

			$this->BingoGame->saveAll($this->request->data);
		}

		return $amount;
	}

	public function send_mail($id = null){

        //get accident info
		$bingo = $this->BingoGame->find('first', array(
            'conditions' => array(
                'BingoGame.id' => $id
            ),
            'contain' => array(
                'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name', 'Account.abr')
                ),
                'Winner'=>array(
                    'fields'=>array('Winner.id', 'Winner.first_name', 'Winner.last_name')
                ),
            ),
        ));

		$from_name = AuthComponent::user('first_name').' '. AuthComponent::user('last_name');
		$winner = $bingo['Winner']['first_name'].' '.$bingo['Winner']['last_name'];
		$account = $bingo['Account']['name'] .' ( '. $bingo['Account']['abr'] .' )';
		$amount = $bingo['BingoGame']['amount'];

		$app_id = $this->Application->get_app_id($this->request->params['controller']);
		//Get Email List
		$email_list = $this->EmailList->pickList($app_id);

		//Email Link To user
        $email = new CakeEmail();
        $email->config('smtp');
        $email->sender('support@vrifm.com', 'VRI Support');

		$email->from(array(AuthComponent::user('email') => $from_name));
        $email->template('bingo', null);
        $email->to($email_list);

        $email->subject('A Bingo Has Been Called:');
        $email->emailFormat('html');

        #$this->set('user_email', $user_email);
        $this->set('winner', $winner);
        $this->set('account', $account);
        $this->set('id', $id);
        $this->set('amount', $amount);

		#$email->viewVars(array('user_email' => $user_email));
        $email->viewVars(array('winner' => $winner));
        $email->viewVars(array('account' => $account));
        $email->viewVars(array('id' => $id));
        $email->viewVars(array('amount' => $amount));

        $email->send();
	}
}
