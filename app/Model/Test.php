<?php
App::uses('AppModel', 'Model');
/**
 * Test Model
 *
 */
class Test extends AppModel {
/**
 * Display field
 *
 * @var string
 */
    public $actsAs = array('Tree', 'Containable');
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        // For CakePHP 2.1 and up
        $this->Auth->allow('add');
    }
    public $hasMany = array(
        'AssignedTest',
        'Report'=>array(
            'className'    => 'Report.Report',
            'foreignKey'   => 'test_id',
        ),
        'TestSchedule',
        'Report.ReportSwitch'
        
    );
    
    public $belongsTo = array( 
        'TestCategoryType'=>array(
            'foreignKey'   => 'category_type',
        ),
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => array('Group.name'),
            'order' => ''
        ),
    );
    
    public function parentNode() {
        return null;
    }
    
    public function buildTest($id=null, $done=null) {
        if(!is_null($done)){ $done = unserialize($done); }
        
        $this->id = $id;
        if (!$this->exists()) {
            throw new NotFoundException(__('Invalid Test'));
        }
        
        $parent = $this->find('first', array(
            'conditions' => array(
                'Test.id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array(
                'Test.lft',
                'Test.rght',
                'Test.name',
                'Test.description',
                'Test.introduction',
                'Test.has_subCategories',
                'Test.report_function'
            )
        ));
        
        $data = $this->find('threaded', array(
            'conditions' => array(
                'Test.lft >=' => $parent['Test']['lft'], 
                'Test.rght <=' => $parent['Test']['rght']
            ),
            'contain'=>array(
            ),
            //'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.introduction',)
        ));
        
        //get to looping to set up test structure
        $testArray = array();
        $numQuestions = 0;
        $testLength = 0;
        
        foreach($data as $test){
            $testCount = 0;
            
            if($test['Test']['has_subCategories'] == 1){
                $test = $this->processData($test);
                
            }else{
                $test= $this->processData($test);
                
                //if done is null they haven't ansered anything 
                //if it isn't null they started already unset the introduction
                if(!is_null($done)){
                    unset($test['Test']['introduction']);
                }
                
                $testArray['Test'] = $test['Test'];
                
                $catCount = 0;
                foreach($test['children'] as $category){
                    $category = $this->processData($category);
                    
                    $testArray['Test']['Category'][$catCount] = $category['Test'];
                    
                    //count questions for a total
                    $testLength += $this->childCount($category['Test']['id'], true); // will output 2
                    
                    $questionCount = 0;
                    foreach($category['children'] as $question){
                        
                        if($numQuestions == 20){ break; }
                        
                        $question = $this->processData($question);
                        
                        if(!isset($done[$test['Test']['id']][$category['Test']['id']][$question['Test']['id']])){
                            
                            $testArray['Test']['Category'][$catCount]['Question'][$questionCount] = $question['Test'];
                            $answerCount = 0;
                            $numQuestions++;
                            foreach($question['children'] as $answers){
                                $answers = $this->processData($answers);
                                
                                $testArray['Test']['Category'][$catCount]['Question'][$questionCount]['Answers'][$answerCount] = $answers['Test'];
                                $answerCount++;
                            }
                            $questionCount++;
                        }
                    }
                    $catCount++;
                }
            }
            $testCount++;
        }
        
        //Final Thing Unset any empty category
        foreach($testArray['Test']['Category'] as $key=>$cat){
            if(empty($cat['Question'])){
                unset($testArray['Test']['Category'][$key]);
            }
        }
        
        if($numQuestions == 0){
            //Update and set averages per category in t_marks
            //Avg the categories
            
            $newData = $this->interestReportCal($id,$done);
            
            $returnData['t_marks'] = $newData[0];
            
            //update completion date and complete = 100
            $returnData['complete'] = 100;
            $returnData['completion_date'] = date(DATE_MYSQL_DATE);
            return array($returnData, 0, $newData[1]);
            
        }
        
        return array($testArray, $testLength);
    }
    
    public function setDepth(&$a, &$done, $depth = -1, &$q_count = 0, &$total_count = 0){
        
        if (($depth > -1) && !($depth % 2)){
            unset(
                $a['Test']['old_id'], 
                $a['Test']['parent_id'], 
                $a['Test']['lft'], 
                $a['Test']['rght'], 
                $a['Test']['conclusion'], 
                $a['Test']['amount_sold'], 
                $a['Test']['is_active'], 
                $a['Test']['schedule_type'], 
                $a['Test']['viewing'], 
                $a['Test']['access_code'], 
                $a['Test']['allow_benchmarking'], 
                $a['Test']['report_introduction'], 
                $a['Test']['report_conclusion'], 
                $a['Test']['allow_personnel_reports'], 
                $a['Test']['has_subCategories'], 
                $a['Test']['retail'], 
                $a['Test']['wholesale'], 
                $a['Test']['comp_id'], 
                $a['Test']['self_assign'], 
                $a['Test']['created'], 
                $a['Test']['modified'],
                $a['Test']['description']
            );
            $a['Test']['depth'] = $depth / 2;
            
            if ((($depth / 2) == 0)){
                $a['Test']['type'] = 'test';
            }
            
            if ((($depth / 2) == 1)){
                $a['Test']['type'] = 'category';
            }
            
            if(empty($a['children']) && $a['Test']['depth'] == 3 ){
                $a['Test']['type'] = 'answer';
                unset($a['children']);
            }
            
            if ((($depth / 2) == 4)){
                $a['Test']['type'] = 'answer';
                unset($a['children']);
            }
            
            if ((($depth / 2) == 2) && empty($a['children'][0]['children'])){
                $q_count++;
                $total_count++;
                $a['Test']['type'] = 'question';
            }else if(($depth / 2) == 2){
                $a['Test']['type'] = 'sub_category';
            }
            
            if(!empty($a['children']) && $a['Test']['depth'] == 3 ){
                $q_count++;
                $total_count++;
                $a['Test']['type'] = 'question';
            }
        }
        
        foreach($a as $key=>$value){
            if(isset($value['Test']['id']) && in_array($value['Test']['id'], $done)){
                $total_count++;
                unset($a[$key], $value);
            }
            
            if (isset($value) && is_array($value)){
               $this->setDepth($a[$key], $done, $depth+1, $q_count, $total_count);
            }
        }
        
        return array($a, $q_count, $total_count);
    }
    
    public function setQuestions(&$a, &$new = array()){
        foreach($a as $key=>$value){
            if(!empty($value['answerId'])){
                $new[] = $key;
            }
            
            if (is_array($value)){
               $this->setQuestions($a[$key], $new);
            }
        }
        
        return $new;
    }
    
    public function showTest(&$a, &$new = array()){
        foreach($a as $key=>$value){
            pr($value);
            exit;
            
            switch($a['Test']['type']){
                
            }
            if (is_array($value)){
               $this->setQuestions($a[$key], $new);
            }
        }
        
        return $new;
    }
    
    public function createTest($id=null, $done=null) {
        if(!is_null($done)){ $done = unserialize($done); }
        
        $this->id = $id;
        if (!$this->exists()) {
            throw new NotFoundException(__('Invalid Test'));
        }
        
        $exempt_ids = array();
        if(is_array($done)){
            $res = Hash::flatten($done);
            
            $values = array_keys($res);
            foreach($values as $value){
                
                $pieces = explode('.', $value);
                $array_count = count($pieces);
                $c = count($pieces);
                if($pieces[count($pieces) - 1] == 'answerId'){
                    $exempt_ids[] = $pieces[$array_count - 2];
                }
                
            }
        }
        
        $parent = $this->find('first', array(
            'conditions' => array(
                'Test.id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array(
                'Test.lft',
                'Test.rght',
                'Test.name',
                'Test.description',
                'Test.introduction',
                'Test.cal_function'
            )
        ));
        $data = $this->find('all', array(
            'conditions' => array(
                'Test.lft >=' => $parent['Test']['lft'], 
                'Test.rght <=' => $parent['Test']['rght'],
                'Test.id !=' =>$exempt_ids,
                'Test.category_type' => 3
            ),
            'contain'=>array(
            ),
            'order'=>array('Test.lft'),
            'limit'=>20
            //'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.introduction', 'Test.has_subCategories')
        ));
        
        if(count($data) == 0){
            if(empty($parent['Test']['cal_function'])){
                $t_marks = $this->general_avg($done);
            }else{
                $t_marks = $this->$parent['Test']['cal_function']($done);
            }
            return array('complete', $t_marks);
        }
        
        $testArray = array();
        
        foreach($data as $item){
            $parent = $this->getPath($item['Test']['id']);
            
            $count = count($parent) - 1;
            $answer = $this->find('all', array(
                'conditions' => array(
                    'Test.parent_id' => $item['Test']['id'], 
                ),
                'contain'=>array(
                ),
                'order'=>array('Test.lft'),
            ));
            $parent = array_merge($parent,$answer);
            
            $parents[] = $parent;
        }   
        $count = count($parents);
        
        for($i=0; $i<$count; $i++ ){
            foreach($parents[$i] as $key=>$item){
                $arr_1[] = $parents[$i][$key];
            }
        }
        $unique = array_unique($arr_1, SORT_REGULAR);
        
        $result = Hash::nest($unique);
        
        return $result;
    }
    
    public function loopIds($data){
        foreach($data as $key=>$item){
            pr($item);
            if(!isset($item['answerText'])){
                $this->loopIds($item);
            }else{
                $ids[] = $key;
            }
        }
        pr('id');
        pr($ids);
        exit;
        return $ids;
    }
    
    public function processData($data){
        
        unset(
            $data['Test']['old_id'], 
            $data['Test']['parent_id'], 
            $data['Test']['lft'], 
            $data['Test']['rght'], 
            $data['Test']['conclusion'], 
            $data['Test']['amount_sold'], 
            $data['Test']['is_active'], 
            $data['Test']['schedule_type'], 
            $data['Test']['viewing'], 
            $data['Test']['access_code'], 
            $data['Test']['allow_benchmarking'], 
            $data['Test']['report_introduction'], 
            $data['Test']['report_conclusion'], 
            $data['Test']['allow_personnel_reports'], 
            $data['Test']['has_subCategories'], 
            $data['Test']['retail'], 
            $data['Test']['wholesale'], 
            $data['Test']['comp_id'], 
            $data['Test']['self_assign'], 
            $data['Test']['created'], 
            $data['Test']['modified'],
            $data['Test']['description']
        );
        
        return $data;
    }
    
    public function grabValue($a=null){
        $this->virtualFields['answerText'] = 'Test.name';
        $this->virtualFields['answerValue'] = 'Test.description';
        $this->virtualFields['answerId'] = 'Test.id';
        
        $this->recursive = 0;
        $data = $this->find('first', array(
            'conditions' => array(
                'Test.id'=> $a
            ),
            'fields'=>array('answerId', 'answerText', 'answerValue')
        ));
        
        
        
        unset($data['Test']['id']);
        
        return $data;
    }
    
    public function interestReportCal($data=null){ 
        $data = Hash::flatten($data);
        
        $socialValue = array();
        $discValue = array();
        
        $discValue['total'] = 0;
        $socialValue['total'] = 0;
        
        foreach($data as $key=>$item){
            $pieces = explode('.', $key);
            $count = count($pieces);
            
            if($pieces[$count - 1] == 'answerValue'){
                if($pieces[1] == 313 || $pieces[1] == 314){
                    if (array_key_exists($item, $socialValue)) {
                        $socialValue[$item]++;
                    }else{
                        $socialValue[$item] = 1;
                    }
                    $socialValue['total']++;
                }else{
                    if (array_key_exists($item, $discValue)) {
                        $discValue[$item]++;
                    }else{
                        $discValue[$item] = 1;
                    }
                    $discValue['total']++;
                }
            }
        }
        $data = array();
        
        $socialValue['total'] = $socialValue['total'] - $socialValue[0];
        
        
        if(empty($discValue[5])){
            $data1[0]['avg'] = 0;
        }else{
            $data1[0]['avg'] = round( ($discValue[5] / $discValue['total'] ) * 100, 2 );
        }
        
        $data1[0]['name'] = 'Competitor'; //5
        $data1[0]['min_score'] = '5';
        $data1[0]['max_score'] = '5';
        $data1[0]['section_id'] = '210';
        
        if(empty($discValue[4])){
            $data1[1]['avg'] = 0;
        }else{
            $data1[1]['avg'] = round( ( $discValue[4] / $discValue['total'] ) * 100, 2 );
        }
        $data1[1]['name'] = 'Communicator'; //4
        $data1[1]['min_score'] = '4';
        $data1[1]['max_score'] = '4';
        $data1[1]['section_id'] = '210';
        
        if(empty($discValue[3])){
            $data1[2]['avg'] = 0;
        }else{
            $data1[2]['avg'] = round( ( $discValue[3] / $discValue['total'] ) * 100, 2 );
        }
        $data1[2]['name'] = 'Cooperator'; //3
        $data1[2]['min_score'] = '3';
        $data1[2]['max_score'] = '3';
        $data1[2]['section_id'] = '210';
        
        if(empty($discValue[2])){
            $data1[3]['avg'] = 0;
        }else{
            $data1[3]['avg'] = round( ( $discValue[2] / $discValue['total'] ) * 100, 2 );
        }
        $data1[3]['name'] = 'Coordinator'; //2
        $data1[3]['min_score'] = '2';
        $data1[3]['max_score'] = '2';
        $data1[3]['section_id'] = '210';
        
        $jpSaveInfo['competitor'] = $data1[0]['avg'];
        $jpSaveInfo['communicator'] = $data1[1]['avg'];
        $jpSaveInfo['cooperator'] = $data1[2]['avg'];
        $jpSaveInfo['coordinator'] = $data1[3]['avg'];
        
        $data2[4]['avg'] = $data1[0]['avg'] + $data1[1]['avg'];
        $data2[4]['name'] = 'Active'; //2
        $data2[4]['min_score'] = '6';
        $data2[4]['max_score'] = '6';
        $data2[4]['section_id'] = '210';
        
        $data2[5]['avg'] = $data1[2]['avg'] + $data1[3]['avg'];
        $data2[5]['name'] = 'Passive'; //2
        $data2[5]['min_score'] = '7';
        $data2[5]['max_score'] = '7';
        $data2[5]['section_id'] = '210';
        
        $data3[6]['avg'] = $data1[0]['avg'] + $data1[3]['avg'];
        $data3[6]['name'] = 'Task'; //2
        $data3[6]['min_score'] = '8';
        $data3[6]['max_score'] = '8';
        $data3[6]['section_id'] = '210';
        
        $data3[7]['avg'] = $data1[1]['avg'] + $data1[2]['avg'];
        $data3[7]['name'] = 'People'; //2
        $data3[7]['min_score'] = '9';
        $data3[7]['max_score'] = '9';
        $data3[7]['section_id'] = '210';
        
        array_multisort($data1, SORT_DESC);
        array_multisort($data2, SORT_DESC);
        array_multisort($data3, SORT_DESC);
        
        $data = array_merge($data,$data1);
        $data = array_merge($data,$data2);
        $data = array_merge($data,$data3);
        
        if(isset($socialValue[1])){ 
            $data4[8]['avg'] = round( ($socialValue[1] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[8]['avg'] = 0;
        }
        
        $data4[8]['name'] = 'Realistic';
        $data4[8]['min_score'] = '1';
        $data4[8]['max_score'] = '1';
        $data4[8]['section_id'] = '213';
        
        if(isset($socialValue[2])){ 
            $data4[9]['avg'] = round( ($socialValue[2] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[9]['avg'] = 0;
        }
        
        $data4[9]['name'] = 'Investigative';
        $data4[9]['min_score'] = '2';
        $data4[9]['max_score'] = '2';
        $data4[9]['section_id'] = '213';
        
        if(isset($socialValue[3])){ 
            $data4[10]['avg'] = round( ($socialValue[3] / $socialValue['total']) * 100, 2 );
        }else {      
            $data4[10]['avg'] = 0;
        }
        
        $data4[10]['name'] = 'Artistic';
        $data4[10]['min_score'] = '3';
        $data4[10]['max_score'] = '3';
        $data4[10]['section_id'] = '213';
        
        if(isset($socialValue[4])){ 
            $data4[11]['avg'] = round( ($socialValue[4] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[11]['avg'] = 0;
        }
        
        $data4[11]['name'] = 'Social';
        $data4[11]['min_score'] = '4';
        $data4[11]['max_score'] = '4';
        $data4[11]['section_id'] = '213';
        
        if(isset($socialValue[5])){ 
            $data4[12]['avg'] = round( ($socialValue[5] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[12]['avg'] = 0;
        }
        
        $data4[12]['name'] = 'Enterprising';
        $data4[12]['min_score'] = '5';
        $data4[12]['max_score'] = '5';
        $data4[12]['section_id'] = '213';
        
        if(isset($socialValue[6])){ 
            $data4[13]['avg'] = round( ($socialValue[6] / $socialValue['total']) * 100, 2 );
        }else {
            $data4[13]['avg'] = 0;
        }
        $data4[13]['name'] = 'Conventional';
        $data4[13]['min_score'] = '6';
        $data4[13]['max_score'] = '6';
        $data4[13]['section_id'] = '213';
        
        $jpSaveInfo['user_id'] = AuthComponent::user('id');
        $jpSaveInfo['realistic'] = $data4[8]['avg'];
        $jpSaveInfo['investigative'] = $data4[9]['avg'];
        $jpSaveInfo['artistic'] = $data4[10]['avg'];
        $jpSaveInfo['social'] = $data4[11]['avg'];
        $jpSaveInfo['enterprising'] = $data4[12]['avg'];
        $jpSaveInfo['conventional'] = $data4[13]['avg'];
        
        array_multisort($data4, SORT_DESC);
        $data = array_merge($data,$data4);
        
        return $data;
        
        #return array($data, $jpSaveInfo);
    }
    
    public function abcCalculation($data=null){
        $data = Hash::flatten($data);
        
        $socialValue = array();
        $discValue = array();
        
        $discValue['total'] = 0;
        $socialValue['total'] = 0;
        
        foreach($data as $key=>$item){
            $pieces = explode('.', $key);
            $count = count($pieces);
            
            if($pieces[$count - 1] == 'answerValue'){
                if($pieces[1] == 313 || $pieces[1] == 314){
                    if (array_key_exists($item, $socialValue)) {
                        $socialValue[$item]++;
                    }else{
                        $socialValue[$item] = 1;
                    }
                    $socialValue['total']++;
                }else{
                    if (array_key_exists($item, $discValue)) {
                        $discValue[$item]++;
                    }else{
                        $discValue[$item] = 1;
                    }
                    $discValue['total']++;
                }
            }
        }
        $data = array();
        
        //$socialValue['total'] = $socialValue['total'] - $socialValue[0];
        
        $data1[0]['avg'] = empty($discValue[5]) ? 0 : round( ($discValue[5] / $discValue['total'] ) * 100, 2 );
        $data1[0]['name'] = 'Competitor'; //5
        $data1[0]['min_score'] = '5';
        $data1[0]['max_score'] = '5';
        $data1[0]['section_id'] = '210';
        
        $data1[1]['avg'] = empty($discValue[4]) ? 0 : round( ( $discValue[4] / $discValue['total'] ) * 100, 2 );
        $data1[1]['name'] = 'Communicator'; //4
        $data1[1]['min_score'] = '4';
        $data1[1]['max_score'] = '4';
        $data1[1]['section_id'] = '210';
        
        $data1[2]['avg'] = empty($discValue[3]) ? 0 : round( ( $discValue[3] / $discValue['total'] ) * 100, 2 );
        $data1[2]['name'] = 'Cooperator'; //3
        $data1[2]['min_score'] = '3';
        $data1[2]['max_score'] = '3';
        $data1[2]['section_id'] = '210';
        
        $data1[3]['avg'] = empty($discValue[2]) ? 0 : round( ( $discValue[2] / $discValue['total'] ) * 100, 2 );
        $data1[3]['name'] = 'Coordinator'; //2
        $data1[3]['min_score'] = '2';
        $data1[3]['max_score'] = '2';
        $data1[3]['section_id'] = '210';
        
        $jpSaveInfo['competitor'] = $data1[0]['avg'];
        $jpSaveInfo['communicator'] = $data1[1]['avg'];
        $jpSaveInfo['cooperator'] = $data1[2]['avg'];
        $jpSaveInfo['coordinator'] = $data1[3]['avg'];
        
        array_multisort($data1, SORT_DESC);
        
        $data = array_merge($data,$data1);
        
        $data2[4]['avg'] = $data1[0]['avg'] + $data1[1]['avg'];
        $data2[4]['name'] = 'Active'; //2
        $data2[4]['min_score'] = '6';
        $data2[4]['max_score'] = '6';
        $data2[4]['section_id'] = '210';
        
        $data2[5]['avg'] = $data1[2]['avg'] + $data1[3]['avg'];
        $data2[5]['name'] = 'Passive'; //2
        $data2[5]['min_score'] = '7';
        $data2[5]['max_score'] = '7';
        $data2[5]['section_id'] = '210';
        
        array_multisort($data2, SORT_DESC);
        
        $data = array_merge($data,$data2);
        
        $data3[6]['avg'] = $data1[0]['avg'] + $data1[2]['avg'];
        $data3[6]['name'] = 'Task'; //2
        $data3[6]['min_score'] = '8';
        $data3[6]['max_score'] = '8';
        $data3[6]['section_id'] = '210';
        
        $data3[7]['avg'] = $data1[1]['avg'] + $data1[3]['avg'];
        $data3[7]['name'] = 'People'; //2
        $data3[7]['min_score'] = '9';
        $data3[7]['max_score'] = '9';
        $data3[7]['section_id'] = '210';
        
        array_multisort($data3, SORT_DESC);
        
        $data = array_merge($data,$data3);
        
        return $data;
    }
    
    public function proscreenReportCal($data=null){
        #pr($testId);
        #pr($data);
        
        foreach($data as $key=>$item){
            foreach($item as $cat_id=>$data){
                
                $count = 0;
                $total = 0;
                $avg = 0;
                
                foreach($data as $value){
                    $total += $value['answerValue'];
                    $count++;            
                }
                $avg = $total/$count;
                
                switch($cat_id){
                    case 246:
                        $returnData[0]['name'] = 'General Work Experience';
                        $returnData[0]['avg'] = $avg;
                        $returnData[0]['section'] =  364;
                        break;
                    
                    case 247:
                        $returnData[1]['name'] = 'Work Ethic';
                        $returnData[1]['avg'] = $avg;
                        $returnData[1]['section'] =  365;
                        break;
                    
                    case 299:
                        $returnData[2]['name'] = 'Integrity';
                        $returnData[2]['avg'] = $avg;
                        $returnData[2]['section'] =  366;
                        break;
                    
                    case 300:
                        $returnData[3]['name'] = 'Team Work';
                        $returnData[3]['avg'] = $avg;
                        $returnData[3]['section'] =  367;
                        break;
                    
                    case 301:
                        $returnData[4]['name'] = 'Reliability';
                        $returnData[4]['avg'] = $avg;
                        $returnData[4]['section'] =  368;
                        break;
                }
                
            }       
        }
        return $returnData;
    }
    
    public function nurseRetentionCal($data=null){
        //pr($data);
        $cal_answers = array(3434, 3436, 3437, 3438, 3439, 3441, 3442, 3443, 3444, 3445, 3446, 3447);
        $total = 0;
        $t_mark = array();
        $t_mark[28][253]['total'] = 0;
        foreach($data as $test_id=>$test){
            foreach($test as $cat_id=>$question){
                foreach($question as $key=>$value){
                    if(in_array($key, $cal_answers)){
                        $t_mark[28][253]['total'] += $value['answerValue'];
                    }
                }
            }
        }
        
        $returnData = $t_mark;
        return $returnData;
    }
    
    public function smarts($data=null){
        foreach($data as $test_id=>$test){
            foreach($test as $cat_id=>$item){
                $count = 0;
                $total = 0;
                foreach($item as $value){
                    $total += $value['answerValue'];
                    $count++;
                }
                
                switch($cat_id){
                    case 255:
                        $result[$test_id][$cat_id]['section'] = 375;
                        break;
                    
                    case 256:
                        $result[$test_id][$cat_id]['section'] = 376;
                        break;
                    
                    case 257:
                        $result[$test_id][$cat_id]['section'] = 377;
                        break;
                    
                    /*
                    case 258:
                        $result[$test_id][$cat_id]['section'] =  ;
                        break;
                    */
                    
                    case 259:
                        $result[$test_id][$cat_id]['section'] = 378;
                        break;
                    
                    case 260:
                        $result[$test_id][$cat_id]['section'] = 379;
                        break;
                    
                    case 261:
                        $result[$test_id][$cat_id]['section'] = 380;
                        break;
                    
                    case 262:
                        $result[$test_id][$cat_id]['section'] = 381;
                        break;
                    
                    case 263:
                        $result[$test_id][$cat_id]['section'] = 382;
                        break;
                    
                }
                $result[$test_id][$cat_id]['total'] = $total/$count;
            }
        }
        
        $returnData = $result;
        return $returnData;
    }
    
    public function general_avg($data=null){
        $data = Hash::flatten($data);
        
        foreach($data as $key=>$item){
            $pieces = explode('.', $key);
            $count = count($pieces);
            if($pieces[$count - 1] == 'answerValue'){
                unset($pieces[$count -1], $pieces[$count - 2]);
                $array = implode('.',$pieces);
                
                if(is_numeric($item)){
                    if(isset($val[$array.'.total'])){
                        $val[$array.'.total'] += $item;
                        $val[$array.'.count']++;
                        
                        $avg[$array.'.average'] = round($val[$array.'.total'] /  $val[$array.'.count'], 2);
                    }else{
                        $val[$array.'.total'] = $item;
                        $val[$array.'.count'] = 1;
                        
                        $avg[$array.'.average'] = round($val[$array.'.total'] /  $val[$array.'.count'], 2);
                    }
                }
            }
        }
        
        $avg = Hash::expand($avg);
        
        return $avg;
    }
    
    public function mgmnt_profiler($data=null){
        echo "---";
        pr($data);
        exit;
        foreach($data as $test_id=>$test){
            foreach($test as $cat_id=>$item){
                $count = 0;
                $total = 0;
                foreach($item as $value){
                    $total += $value['answerValue'];
                    $count++;
                }
                $result[$test_id][$cat_id]['total'] = $total/$count;
            }
        }
        
        $returnData = $result;
        return array($returnData, null);
    }
    
    public function getSelfAssignedTest(){
        $test_list = $this->find('list', array(
            'conditions'=>array(
                'Test.self_assign'=>1
            ),
            'contain'=>array(
            ),
        ));
        
        return $test_list;
    }
    
    //This is for report - Pro-Screen
    public function getQuestions($data){
        
        $conditions = array(
            'conditions'=>array(
                'Test.id' => $data
            ),
            'contain'=>array(
            ),
            'fields'=>array('Test.name', 'Test.report_introduction')
        );
        
        $content = $this->find('first', $conditions);
        
        
        return $content;
    }
    
    public function pickList( $first_choice='- Select a Test -', $options=array() ) {
        $dataArr = array(0=>$first_choice);
        $options = array_merge_recursive( array('order'=>'name asc'), $options );
        
        $recs = $this->find('list', $options );
        
        return $recs;
    }
    
    public function fullList(  ) {
        $conditions = array(
            'conditions'=>array(
                'Test.parent_id IS NULL'
            ),
            'contain'=>array(
                'Group'
            ),
            'fields'=>array('Test.id','Test.name','Test.is_active','Test.schedule_type','Test.credits','Test.cal_function'),
            'order'=>'Test.name asc'
        );
        $recs = $this->find('all', $conditions );
        return $recs;
    }
    
    public function testDetails( $id = null  ) {
        
        $conditions = array(
            'conditions'=>array(
                'Test.id'=>$id
            ),
            'contain'=>array(
            ),
            'fields'=>array('Test.id','Test.lft','Test.rght','Test.name','Test.is_active','Test.schedule_type','Test.credits','Test.cal_function','Test.report_introduction'),
            'order'=>'Test.name asc'
        );
        $recs = $this->find('first', $conditions );
        
        return $recs;
    }
}
