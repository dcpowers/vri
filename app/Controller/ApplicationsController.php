<?php
// app/Controller/AppsController.php
App::uses('AppController', 'Controller');

class ApplicationsController extends AppController {
    public $uses = array(
        'Application', 
    );
    
    public $helpers = array('Session');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function getLeftMenu() {
        $apps = array();
        
        $parent = $this->Application->find('all', array(
            'conditions' => array(
                'Application.show_main_menu' => 1,
                'Application.is_active' => 1,
                'Application.min_permission_level <=' => $this->Auth->user('Role.permission_level')
            ),
            'fields'=>array('Application.lft', 'Application.rght')
        ));
        foreach($parent as $item){
            $value = $this->Application->find('threaded', array(
                'conditions' => array(
                    'Application.lft >=' => $item['Application']['lft'], 
                    'Application.rght <=' => $item['Application']['rght']
                ),
                'fields'=>array(
                    'Application.name',
                    'Application.controller',
                    'Application.action',
                    'Application.iconCls',
                )
            ));
            
            $apps = array_merge($apps, $value);
        }
        
        return $apps;
    }
}