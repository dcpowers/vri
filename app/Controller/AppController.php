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
App::uses('CakeEmail', 'Network/Email');
App::uses('Router', 'Routing');
App::uses('HttpSocket', 'Network/Http');
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
        'DebugKit.Toolbar',
        'Session',
        'Cookie',
        'RequestHandler',
        'Paginator',
        'Flash',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'Users',
                'action' => 'login',
                'plugin' => false,
                'admin' => false
            ),
            'loginRedirect' => array('controller' => 'Dashboard', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'login'),
            #'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authenticate' => array( 'Form' => array('passwordHasher' => 'Blowfish')),
            'authError' => 'You must sign in to access this page.',
            #'authorize' => array('Actions' => array('actionPath' => 'controllers')),
            'authorize'=>array('Controller'),
        )
    );

    public $helpers = array( 'Html', 'Form', 'Session', 'Time', 'Text' );

    // only allow the login controllers only
    public function beforeFilter() {
        #$this->Security->validatePost=false;
        #$this->Security->csrfCheck=false;
        #$this->Security->csrfUseOnce=false;
        $sslnotallowed_url  = array('beta_user','terms','privacy','security');

        if (env('SERVER_NAME') != 'vri') {
            #$this->Security->blackHoleCallback = 'forceSSL';
        }

        if(!in_array($this->params['action'],$sslnotallowed_url) && ( env('SERVER_NAME') != 'iwz-3.0' && env('SERVER_NAME') != 'iworkzone.biz')){
            #$this->Security->requireSecure('*');
        }

        if (isset($this->params['requested'])) $this->Auth->allow($this->action);

        $this->Auth->authorize = array('Controller');

        $this->Auth->allow('login', 'logout');

        $this->set('logged_in', $this->Auth->loggedin());
        $this->loggedIn = $this->Auth->loggedin();
        $this->set('current_user', $this->Auth->user());

        $this->disableCache();

        $url = Router::url(NULL, false); //complete url

        if (!preg_match('/login/i', $url) && !preg_match('//i', $url)){
            $this->Session->write('lastUrl', $url);
        }

    }

    function beforeRender() {
        $this->set('current_user', $this->Auth->User());
        $this->set('logged_in', $this->Auth->loggedin());
    }

    //controller authorization callback
    public function isAuthorized($user = null) {

        if (empty($this->request->params['admin'])) {
            return true;
        }

        // Only admins can access admin functions
        if (isset($this->request->params['admin'])) {
            return (bool)($user['role'] === 'admin');
        }

        // Default deny
        return false;
    }

    public function forceSSL() {
        return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
    }

    protected function pluginSetup() {
        if (isset($this->request->params['admin']) ) {
            Configure::write('App.Name', __('Portal Admin'));
            Configure::write('App.Interface', __('Admin'));
        }

        try {
            //load navmenu config
            Configure::load('app_navmenu');
        } catch (ConfigureException $e) { } //suppress exception
    }

    protected function currentUser() {
        $user = $this->Auth->user();

        return $user; # Return the complete user array
    }
}
