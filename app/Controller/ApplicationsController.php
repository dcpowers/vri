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
                'Application.parent_id' => null,
                'Application.show_main_menu' => 1,
                'Application.is_active' => 1,
                'Application.min_permission_level <=' => $this->Auth->user('Role.permission_level')
            ),
            'contain'=>array(),
            'fields'=>array('Application.lft', 'Application.rght'),
            'order'=>array('Application.lft' => 'ASC')
        ));
        
        foreach($parent as $item){
            $value = $this->Application->find('threaded', array(
                'conditions' => array(
                    'Application.lft >=' => $item['Application']['lft'], 
                    'Application.rght <=' => $item['Application']['rght'],
                    'Application.is_active' => 1,
                    'Application.min_permission_level <=' => $this->Auth->user('Role.permission_level')
                ),
                'contain'=>array(),
            ));
            $apps = array_merge($apps, $value);
        }
        
        
        return $apps;
    }
    
    public function getDashboard() {
        $apps = array();
        
        $parent = $this->Application->find('all', array(
            'conditions' => array(
                'Application.show_dashboard' => 1,
                'Application.is_active' => 1,
                'Application.min_permission_level <=' => $this->Auth->user('Role.permission_level')
            ),
            'fields'=>array('Application.lft', 'Application.rght'),
            'order'=>array('Application.order' => 'ASC')
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