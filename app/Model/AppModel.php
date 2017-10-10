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

	function scheduleTypeInt() {
        return array('Single'=>'Single', 'Group'=>'Group', 'MultiplePeople' => 'Multiple People', 'Blind' => 'Blind');
    }

	function jobStatusInt() {
        return array('0'=>null, '1' => 'Open', '2' => 'Draft', '3' =>'Closed');
    }

	function raceInt() {
        return array(
            '0'=>null,
            '1' => 'Hispanic or Latino',
            '2' => 'White',
            '3' => 'Black or African American',
            '4' => 'Native Hawaiian or Other Pacific Islander',
            '5' => 'Asian',
            '6' => 'Armerican Indian or Alaska Native',
            '7' => 'Other'
        );
    }

    function questionOpt() {
        return array(
            '0'=>null,
            '1' => 'Required',
            '2' => 'Preferred',
        );
    }

    function surName() {
        return array(
            '0'=>null,
            '1' => 'Mr.',
            '2' => 'Mrs',
            '3' => 'Ms.',
            '4' => 'Miss',
        );
    }

    function applicantFullStatus(){
        return array(
            '0' => array('name'=>'New', 'controller'=>'', 'action'=>'', 'class'=>'disabled'),
            '1' => array('name'=>'Reviewed', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '2' => array('name'=>'Schedule Phone Screen', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '3' => array('name'=>'Interviewed', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '4' => array('name'=>'Checking References', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '5' => array('name'=>'Put on hold', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '6' => array('name'=>'Make offer', 'controller'=>'Jobseekers', 'action'=>'make_offer', 'class'=>''),
            //'7' => array('name'=>'Not Hired Because...', 'controller'=>'', 'action'=>'', 'class'=>''),
            '8' => array('name'=>'Not a fit', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            '9' => array('name'=>'Declined Offer', 'controller'=>'Jobseekers', 'action'=>'update_applicant', 'class'=>''),
            //'10' => 'Hired'

        );
    }

    function applicantStatus(){
        return array(
            '0' => 'New',
            '1' => 'Reviewed',
            '2' => 'Schedule Phone Screen',
            '3' => 'Interviewed',
            '4' => 'Checking References',
            '5' => 'Put on hold',
            '6' => 'Make offer',
            '7' => 'Invited To Apply',
            '8' => 'Not a fit',
            '9' => 'Declined Offer',
            '10' => 'Accepted Offer',

        );
    }

    function applicantChangeStatus(){
        return array(
            '1' => 'Reviewed',
            '2' => 'Schedule Phone Screen',
            '3' => 'Interviewed',
            '4' => 'Checking References',
            '5' => 'Put on hold',
            //'7' => array('name'=>'Not Hired Because...', 'controller'=>'', 'action'=>'', 'class'=>''),
            '8' => 'Not a fit',
            '9' => 'Declined Offer',
            '10' => 'Accepted Offer',
        );
    }

    function miles(){
        return array(
            '20'=>'20 Mile Radius',
            '50'=>'50 Mile Radius',
            '100'=>'100 Mile Radius',
            '250'=>'250 Mile Radius',
            '3959'=>'Everywhere',
        );
    }

    function searchType(){
        return array(
            '0'=>'Strict',
            '1'=>'Loose'
        );
    }
}
