<?php
App::uses('AppModel', 'Model');

class TalentpatternUser extends AppModel {
    /**
     * belongsTo associations
     *
     * @var array
     */
     
    public $actsAs = array('Containable');
    public $belongsTo = array( 
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        
    );
    
    public function saveUserData($data=null){
        $data = unserialize($data);
        unset($data[4],$data[5],$data[6],$data[7]);
        
        $this->deleteAll(array('TalentpatternUser.user_id' => AuthComponent::user('id')), false);
        
        $jpSaveInfo['user_id'] = AuthComponent::user('id');
        
        foreach($data as $item){
            $name = strtolower($item['name']);
            $jpSaveInfo[$name] = $item['avg'];
        }
        $this->create();
        
        if ($this->saveall($jpSaveInfo)){
            return true;
        }else{
            return false;
        }
    }
    
    public function createTalentPatterns($users=null){
        
        $item = $this->find('all', array(
            'conditions' => array(
                'TalentpatternUser.user_id'=>$users, 
            ),
            'contain'=>array(
            )
        ));
        
        foreach($item as $key=>$val){
            unset(
                $item[$key]['TalentpatternUser']['id'],
                $item[$key]['TalentpatternUser']['user_id'],
                $item[$key]['TalentpatternUser']['created'],
                $item[$key]['TalentpatternUser']['modified']
            );
        }
        
        $numItems = count($item);
        $math = array();
        foreach($item as $val){
            foreach($val as $newItem){
                foreach($newItem as $key=>$newItem2){
                    if(isset($math[$key])){
                        $math[$key] +=  $newItem2 ;
                    }else{
                        $math[$key] = $newItem2 ;
                    }
                    $totals[$key] = $math[$key];        
                }
                
            }
        }
        foreach($totals as $key=>$total){
            $data[$key] = round($total / $numItems, 2);
        }
        return $data;
    }
    
    public function checkTalentPatterns($user=null){
        $conditions = array(
            'TalentpatternUser.user_id' => $user,
        );
        
        if ($this->hasAny($conditions)){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function grabMyResults(){
        $data = $this->find('first', array(
            'conditions' => array(
                'TalentpatternUser.user_id' => AuthComponent::user('id')
            ),
        ));
        unset(
            $data['TalentpatternUser']['id'],
            $data['TalentpatternUser']['user_id'],
            $data['TalentpatternUser']['created'],
            $data['TalentpatternUser']['modified']
        );
        #pr($data);
        #exit;
        
        return $data;
    }
    
    public function pick_list($i1=null, $i2=null, $b1=null, $b2=null){
        $data = $this->find('list', array(
            'conditions'=>array(
                'TalentpatternUser.interest1' => $i1,
                'TalentpatternUser.interest2' => $i2,
                'TalentpatternUser.behavior1' => $b1,
                'TalentpatternUser.behavior2' => $b2
            ),
            'fields'=>array('TalentpatternUser.user_id')
        ));
        
        return $data;
    }
    
    public function updateuser(){
        $info = $this->find('all', array(
            'conditions' => array(
                'OR'=>array(
                    'TalentpatternUser.behavior1 IS NULL',
                    'TalentpatternUser.behavior2 IS NULL',
                    'TalentpatternUser.interest1 IS NULL',
                    'TalentpatternUser.interest2 IS NULL'
                )
                
            ),
            'contain' => array(
            )
        ));
        
        $count = 0;
        foreach($info as $d){   
            
            $id = $d['TalentpatternUser']['id'];
            
            unset(
                $d['TalentpatternUser']['id'],
                $d['TalentpatternUser']['user_id'],
                $d['TalentpatternUser']['behavior1'],
                $d['TalentpatternUser']['behavior2'],
                $d['TalentpatternUser']['interest1'],
                $d['TalentpatternUser']['interest2'],
                $d['TalentpatternUser']['created'],
                $d['TalentpatternUser']['modified']
            );
            $i = 0;
            foreach($d['TalentpatternUser'] as $key=>$r){
                if($i<=5){
                    $interest[$key] = $r;
                }else{
                    $behavior[$key] = $r;
                }
                $i++;
            }
            
            arsort($interest);
            arsort($behavior);
            
            $interest = array_slice($interest, 0, 2);
            $behavior = array_slice($behavior, 0, 2);
            
            $i=0;
            foreach($interest as $key=>$p){
                if($i == 0){ $data['TalentpatternUser']['interest1'] = $key; }
                else { $data['TalentpatternUser']['interest2'] = $key;}
                $i++;
            }
            
            $i=0;
            foreach($behavior as $key=>$p){
                if($i == 0){ $data['TalentpatternUser']['behavior1'] = $key; }
                else { $data['TalentpatternUser']['behavior2'] = $key;}
                $i++;
            }
            $data['TalentpatternUser']['id'] = $id;
            
            $this->save($data);
            
            
        }
        return true;
    }
}