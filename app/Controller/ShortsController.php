<?php
App::uses('AppController', 'Controller');
/**
 * AuthRoles Controller
 *
 * @property AuthRole $AuthRole
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ShortsController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $uses = array( 
        'short',
        'TestSchedule',
        'BlindTest'
    );
    
    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
    }
    
    public function retrieve_test(){
        $codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($codeset);
        
        $converted = $this->params['id'];
        $c = 0;
        
        for ($i = strlen($converted); $i; $i--) {
            $c += strpos($codeset, substr($converted, (-1 * ( $i - strlen($converted) )),1)) * pow($base,$i-1);
        }
        
        $info = $this->TestSchedule->getAssignedTestingInfo($c);
        
        //Create Record in blind test table
        $data = array(
            'test_id'=> $info['TestSchedule']['test_id'],
            'test_schedule_id'=> $info['TestSchedule']['id'],
            'start_date'=> date(DATE_MYSQL_DATE),
            'complete'=> 0,
        );
        $this->BlindTest->create();
        $this->BlindTest->save($data);
        
        $this->Session->write('BlindTest.id', $this->BlindTest->id );
        //Redirect
        $this->redirect(array('controller'=>'BlindTests', 'action'=>'test', 'member'=>false, $c)); 
    }
    
    public function build_url($n=null){
        $codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($codeset);
        $converted = "";

        while ($n > 0) {
            $converted = substr($codeset, ($n % $base), 1) . $converted;
            $n = floor($n/$base);
        }
        return $converted; // 4Q
        
    }
}