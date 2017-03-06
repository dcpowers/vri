<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    public function currentUser1234() {
        $user = AuthComponent::user();

        return $user; # Return the complete user array
    }

	public function getUserIds(){
		switch(AuthComponent::user('Role.permission_level')){
			case 70:
			case 60:
				$user_ids = $this->User->adminPickList();
				return $user_ids;
				#exit;
				break;

			case 50:
			case 40:
			case 30:
				$account_ids = $this->Account->pickListActive();
				$user_ids = $this->AccountUser->getAccountIds($account_ids);
				return $user_ids;
				break;

			case 20:
			case 10:
				break;
		}
	}

    function statusInt() {
        return array(1 => 'Active', 2 => 'Inactive');
    }

    function yesNo() {
        return array(1 => 'Yes', 2 => 'No');
    }

    function required() {
        return array(1 => 'Yes', 0 => 'No');
    }

    function empPayStatus(){
        return array( 1 => 'Full Time', 2 => 'Part Time', 3=>'Not eligible for Safety Awards', 4 => 'Salary', 5 => 'PRN' );
    }
}
