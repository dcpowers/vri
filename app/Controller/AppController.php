<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $components = array(
        #'Security',
        #'Session',
        #'Cookie',
        #'RequestHandler', 
        'DebugKit.Toolbar',
        'Flash',
        'Auth' => array(
            'loginRedirect' => array(
                'plugin'=> false,
                'controller' => 'Dashboard',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'plugin'=> false,
                'controller' => 'users',
                'action' => 'login',
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
            'authError' => 'You must sign in to access this page.',
            'authorize' => array('Controller')
        ),
        
    );

    public function beforeFilter() {
        $this->Auth->allow('index', 'view');
    }
    
    function beforeRender() {
        #pr($this->Auth->User());
        #exit;
        $this->set('current_user', $this->Auth->User());
    }
    
    //controller authorization callback
    public function isAuthorized($user = null) {
 
        if (isset($this->request->params['admin']) ) {
            foreach( $user['AuthRole'] as $role ) {
                if ( $role['name'] == 'SuperAdmin' ) { 
                    return true; 
                }
            }
            $this->Session->setFlash(__('You are not authorized to access this resource.'));
            return false;
        }
        
        return true;
    }
}
