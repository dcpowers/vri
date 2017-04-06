<?php
App::uses('ReportAppController', 'Report.Controller');
App::uses('CakePdf', 'CakePdf.Pdf');


/**
 * Report Controller
 * 
*/
class ReportController extends ReportAppController {
    /**
    * Scaffold
    *
    * @var mixed
    */
     
    public $scaffold = 'admin';
    //public $paginate = array( 'limit'=>100 ,'order'=>'Training.title');
      
    //public $components = array('Report.PieGraph');
    public $uses = array(
        'Report.ReportGraph', 
        'AssignedTest', 
        'Report.Report', 
        'Report.ReportSection', 
        'Report.ReportStatement', 
        'Report.ReportGraph',
        'Test',
        'BlindTest',
        'Group'
    );
     
    //Search Plugin
    public $presetVars = array( 
        array('field' => 'q', 'type' => 'value') 
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }
  
    public function pluginSetup() {
        //$this->layout = 'split';
    }
  
    /**
    * index action
    * 
    */
    public function index() {
        /************USE TO RECOVER TREE STRUCTURE*****************/
        set_time_limit(60*60*24);
        $verify = $this->Report->verify();
        $this->Report->recover('parent');
        echo 'recovery completed...';
      
        pr($verify);
        exit;
      
        $this->layout = 'member';
    }
  
    /**
      * view action
      * 
      * @param mixed $id
    */
    public function view( $id ) {
    }
      
    public function add() {
        // Handle Form POST
        if ( $this->request->is( 'post' ) ) {
          //Save Data
          $this->request->data['Training']['org_unit_id']     = AuthComponent::user('org_unit_id');
          $this->request->data['Training']['org_id']        = AuthComponent::user('org_id');
          
          $this->Training->create();
          if ( $this->Training->save( $this->request->data ) ) {
            $this->Session->setFlash(__('Training Created'));
            $this->redirect( array( 'action'=>index) );
          } else {
            $this->Session->setFlash(__('Could not create Training'));  
          }
        }
          
        $this->set('authors', $this->{$this->modelClass}->Author->pickList('- Select an Associate -') );
    }
  
    public function edit( $id ) {
        if ( empty($id) ) {
          throw new InvalidArgumentException('$id is required for edit action.');
        }
        
        // Handle Form POST
        // TODO For some unknown reason if( $this->request->is('post')) will not work here
        if (!$this->request->is('get')) {
          //Save Data
          $this->Training->id = $id;
          if ( $this->Training->save( $this->request->data ) ) {
            $this->Session->setFlash(__('Training Updated'));
            $this->redirect( array( 'action'=>'index' ) );
          }
          $this->Session->setFlash(__('Could not update Training'));
        }
        
        $this->request->data = $this->{$this->modelClass}->read(null, $id );
        $this->set('authors', $this->{$this->modelClass}->Author->pickList('- Select an Associate -') );
        
        $this->set('breadcrumbs', array(
          array('title'=>'Training','link'=>array( 'action'=>'index' ) ),
          array('title'=>'TRN-' . str_pad( $this->request->data['Training']['id'], 4, 0, STR_PAD_LEFT).' '.$this->request->data['Training']['title'],'link'=>array( 'action'=>'view', $id ) ),
          array('title'=>'Edit','link'=>array( 'action'=>'edit', $id ) )
        ));
    }
  
    public function delete( $id ) {
        if ( empty($id) ) {
          throw new InvalidArgumentException('$id required');
        }
        $this->{$this->modelClass}->delete( $id );
        $this->Session->setFlash(__('Training Deleted'));
        
        $this->redirect( array( 'controller'=>'trainings', 'action'=>'view_all') );
    }
  
    public function general($id=null) {
        $data = $this->AssignedTest->grabReportData($id);
          $folder = AuthComponent::user('DetailUser.uploadDir');
          $var = $data['Test']['report_function'].'_ind';
          $averages = unserialize($data['AssignedTest']['t_marks']);
          $answers = unserialize($data['AssignedTest']['t_ans']);
          $this->set( 'id', $id ); 
          
          $report = $this->Report->getReport($data['Test']['id']);
          
          foreach($report as $item){
              $ids = Set::extract( $item['children'], '{n}.Report.id' );
              $list['avg'] = Set::extract( $item['children'], '{n}.Report.min_score' );
              $list['stdev'] = Set::extract( $item['children'], '{n}.Report.max_score' );
          }
          
          $k = 0;
          
          foreach($ids as $id){
              $graphStdev[$id]['avg'] = $list['avg'][$k];
              $graphStdev[$id]['stdev'] = $list['stdev'][$k];
              $k++;
          }
          
          
          foreach($averages as $key=>$graph){
              if($key != 0){
                $name = 'proscreen_'.$key;
                $this->ReportGraph->bar_graph($graph['avg'],$name, $folder, $graphStdev[$graph['section']]['avg'], $graphStdev[$graph['section']]['stdev']);
              }
          }
          
          //Work Ethic extreme
          $extreme = array();
          $count  = 0;
          foreach($answers[23][247] as $key=>$item){
              if($item['answerValue'] <= 2){
                  $value = $this->Test->getQuestions($key);
                  
                  $extreme['Work Ethic'][$count]['question'] = $value[$key];
                  $extreme['Work Ethic'][$count]['answer'] = $item['answerText'];
                  $count++;
              }
          }
          
          //Integrity
          $count  = 0;
          foreach($answers[23][299] as $key=>$item){
              if($item['answerValue'] <= 3){
                  $value = $this->Test->getQuestions($key);
                  
                  $extreme['Integrity'][$count]['question'] = $value[$key];
                  $extreme['Integrity'][$count]['answer'] = $item['answerText'];
                  $count++;
              }
          }
          
          //Team work
          $count  = 0;
          foreach($answers[23][300] as $key=>$item){
              if($item['answerValue'] <= 3){
                  $value = $this->Test->getQuestions($key);
                  
                  $extreme['Team Work'][$count]['question'] = $value[$key];
                  $extreme['Team Work'][$count]['answer'] = $item['answerText'];
                  $count++;
              }
          }
          
          //Reliability
          $count  = 0;
          foreach($answers[23][301] as $key=>$item){
              if($item['answerValue'] <= 3){
                  $value = $this->Test->getQuestions($key);
                  
                  $extreme['Reliability'][$count]['question'] = $value[$key];
                  $extreme['Reliability'][$count]['answer'] = $item['answerText'];
                  $count++;
              }
          }
          
          $folder = Router::url('/', true).''.$folder;
          $this->set( 'averages', $averages );
          $this->set( 'extreme', $extreme );
          $this->set( 'answers', $answers );
          $this->set( 'userInfo', $data['User'] );
          $this->set( 'AssignedTest', $data['AssignedTest'] );
          $this->set( 'report', $report );
          $this->set( 'folder', $folder );
          
          $this->layout = 'member';
          $this->render('Report/23/report');
    }
  
    public function employeeClimateSurvey($id=null, $is_blind=null) {
        $this->set( 'id', $id);
        $this->set( 'is_blind', $is_blind);
        
        if($is_blind == 1){
            $data = $this->BlindTest->grabAllAssigned($id);
            $var = 'BlindTest';
        }else{
            $data = $this->AssignedTest->grabAllAssigned($id);
            $var = 'AssignedTest';
        }
        
        $test = $this->Test->testDetails($data[0][$var]['test_id']);
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
        if(!is_dir($folder)) {
            $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }
        
        foreach($data as $key=>$item){
            if($var == 'BlindTest'){
                $itemKey = $item['Group']['name'];
            }else{
                $itemKey = $item['User']['GroupMembership'][0]['Group']['name'];
            }
            //this is by role
            $roleList[$itemKey] = $itemKey;
            $newAnswer[$itemKey][] = Hash::flatten(unserialize($item[$var]['t_ans']));
            $newData[$itemKey][] = Hash::flatten(unserialize($item[$var]['t_marks']));
        }
        
        $roleCount = 0;
      
        foreach($newData as $role=>$q){
            $count  = count($q);
            foreach($q as $key=>$p){
                foreach($p as $keys=>$m){
                    $check = explode('.', $keys);
                    $countCheck = count($check);
                  
                    $id = $check[$countCheck - 2];
                  
                    $newKey = $id.'.category.catName';
                    $reportCat = $id.'.category.catReport';
                    $totalByCat = $id.'.category.catAvg';
                    $avgKey = $id.'.category.catRoleAverage.'.$role;
                    
                    $value = $this->Test->getQuestions($id);
                    
                    $byRole[$newKey] = $value['Test']['name'];
                    $byRole[$reportCat] = $value['Test']['report_introduction'];
                  
                    if(isset($total[$avgKey])){
                        $total[$avgKey] += $newData[$role][$key][$keys];
                        $byRole[$avgKey] = $total[$avgKey]/$count;
                    }else{
                        $total[$avgKey] = $newData[$role][$key][$keys];
                        $byRole[$avgKey] = $total[$avgKey]/$count;
                    }
                  
                    if(isset($average[$totalByCat])){
                        $average[$totalByCat] += $newData[$role][$key][$keys];
                    }else{
                        $average[$totalByCat] = $newData[$role][$key][$keys];
                    }
                    #pr($average);
                }
                
                $roleCount++;
            }
        }
      
        foreach($average as $key=>$t){
            $average[$key] = round($t/$roleCount, 2);
        }
      
        $report = array_merge($byRole,$average);
      
        unset($average);
        $roleCount = 0;
      
        foreach($newAnswer as $role=>$q){
            $count  = count($q);
            $arrayCounter = 0;
            foreach($q as $key=>$p){
                foreach($p as $keys=>$m){
                    $check = explode('.', $keys);
                    $countCheck = count($check);
                    $var = $check[$countCheck - 1];
                  
                    if($var == 'answerValue' && is_numeric($m)){
                        $id = $check[$countCheck - 2];
                        $catId = $check[$countCheck - 3];
                      
                        $newKey = $catId.'.category.questions.'.$id.'.name';
                        $avgKey = $catId.'.category.questions.'.$id.'.roleAverage.'.$role;
                        $reportCat = $catId.'.category.questions.'.$id.'.average';
                      
                        $value = $this->Test->getQuestions($id);
                        $byRole[$newKey] = $value['Test']['name'];
                      
                    }else if($var == 'answerValue'){
                        $id = $check[$countCheck - 2];
                        $catId = $check[$countCheck - 3];
                      
                        $newKey = $catId.'.category.questions.'.$id.'.name';
                        $avgKey = $catId.'.category.questions.'.$id.'.comment.'.$role;
                        $reportCat = $catId.'.category.questions.'.$id.'.average';
                      
                        $value = $this->Test->getQuestions($id);
                        $byRole[$newKey] = $value['Test']['name'];
                    }
                  
                    if($var == 'answerValue' && is_numeric($m)){
                        if(isset($total[$avgKey]) && $var == 'answerValue'){
                            $total[$avgKey] += $newAnswer[$role][$key][$keys];
                            $byRole[$avgKey] = $total[$avgKey]/$count;
                            #$total[$role][$keys] += $newAnswer[$role][$key][$keys];
                            #$byRole[$role][$link] = $total[$role][$keys]/$count;
                        }else if($var == 'answerValue'){
                            $total[$avgKey] = $newAnswer[$role][$key][$keys];
                            $byRole[$avgKey] = $total[$avgKey]/$count;
                            #$total[$role][$keys] = $newAnswer[$role][$key][$keys];
                            #$byRole[$role][$link] = $total[$role][$keys]/$count;
                        }
                  
                        if(isset($average[$reportCat]) && $var == 'answerValue' ){
                            $average[$reportCat] += $newAnswer[$role][$key][$keys];
                        }else if($var == 'answerValue'){
                            $average[$reportCat] = $newAnswer[$role][$key][$keys];
                        }
                    }else if($var == 'answerValue'){
                        $byRole[$avgKey] = $m;
                    }
                }
                
                $roleCount++;
            }
        }
        
        foreach($average as $key=>$t){
            $average[$key] = round($t/$roleCount, 2);
        }
      
        $report = array_merge($report, $byRole);
        $report = array_merge($report, $average);
        //Build Graaphs
        $report = Hash::expand($report);
        foreach($report as $key=>$item){
            $overallCat[$item['category']['catName']] = $item['category']['catAvg'];
            $name = str_replace(' ', '', strtolower($item['category']['catName']));
          
            $report[$key]['category']['catRoleGraph'] = $this->ReportGraph->create_multi_bar_graph($item['category']['catRoleAverage'],$name,$folder);
          
            foreach($item['category']['questions'] as $sKey=>$details){
                if(isset($details['roleAverage'])){
                    $report[$key]['category']['questions'][$sKey]['qRoleGraph'] = $this->ReportGraph->create_multi_bar_graph($details['roleAverage'],$sKey,$folder);
                }
            }
        }
        arsort($overallCat);
        $name = 'summary';
      
        $summaryGraph = $this->ReportGraph->create_multi_bar_graph($overallCat,$name,$folder);
        
        $groupName = $this->Group->getGroupInfo(AuthComponent::user('parent_group_ids.1'));
        
        $this->set( 'groupName', $groupName['Group']['name'] );
        $this->set( 'report', $report );
        $this->set( 'test', $test );
        $this->set( 'summaryGraph', $summaryGraph );
        
        $this->layout = 'member';
        $this->render('Report/4/report'); 
    }
    
    public function pro_screen_employee($id=null) {
        $data = $this->AssignedTest->grabReportData($id);
        $folder = AuthComponent::user('DetailUser.uploadDir');
        $var = $data['Test']['report_function'].'_ind';
        $averages = unserialize($data['AssignedTest']['t_marks']);
        $answers = unserialize($data['AssignedTest']['t_ans']);
        $this->set( 'id', $id ); 
      
        $report = $this->Report->getReport($data['Test']['id']);
      
        foreach($report as $item){
            $ids = Set::extract( $item['children'], '{n}.Report.id' );
            $list['avg'] = Set::extract( $item['children'], '{n}.Report.min_score' );
            $list['stdev'] = Set::extract( $item['children'], '{n}.Report.max_score' );
        }
        
        $k = 0;
      
        foreach($ids as $id){
            $graphStdev[$id]['avg'] = $list['avg'][$k];
            $graphStdev[$id]['stdev'] = $list['stdev'][$k];
            $k++;
        }
      
        foreach($averages as $key=>$graph){
            if($key != 0){
                $name = 'proscreen_'.$key;
                $this->ReportGraph->bar_graph($graph['avg'],$name, $folder, $graphStdev[$graph['section']]['avg'], $graphStdev[$graph['section']]['stdev']);
            }
        }
      
        //Work Ethic extreme
        $extreme = array();
        $count  = 0;
        foreach($answers[23][247] as $key=>$item){
            if($item['answerValue'] <= 2){
                $value = $this->Test->getQuestions($key);
              
                $extreme['Work Ethic'][$count]['question'] = $value[$key];
                $extreme['Work Ethic'][$count]['answer'] = $item['answerText'];
                $count++;
            }
        }
      
        //Integrity
        $count  = 0;
        foreach($answers[23][299] as $key=>$item){
            if($item['answerValue'] <= 3){
                $value = $this->Test->getQuestions($key);
              
                $extreme['Integrity'][$count]['question'] = $value[$key];
                $extreme['Integrity'][$count]['answer'] = $item['answerText'];
                $count++;
            }
        }
      
        //Team work
        $count  = 0;
        foreach($answers[23][300] as $key=>$item){
            if($item['answerValue'] <= 3){
                $value = $this->Test->getQuestions($key);
              
                $extreme['Team Work'][$count]['question'] = $value[$key];
                $extreme['Team Work'][$count]['answer'] = $item['answerText'];
                $count++;
            }
        }
      
        //Reliability
        $count  = 0;
        foreach($answers[23][301] as $key=>$item){
            if($item['answerValue'] <= 3){
                $value = $this->Test->getQuestions($key);
              
                $extreme['Reliability'][$count]['question'] = $value[$key];
                $extreme['Reliability'][$count]['answer'] = $item['answerText'];
                $count++;
            }
        }
      
      $folder = Router::url('/', true).''.$folder;
      $this->set( 'averages', $averages );
      $this->set( 'extreme', $extreme );
      $this->set( 'answers', $answers );
      $this->set( 'userInfo', $data['User'] );
      $this->set( 'AssignedTest', $data['AssignedTest'] );
      $this->set( 'report', $report );
      $this->set( 'folder', $folder );
      
      $this->layout = 'member';
      $this->render('Report/23/report');
    }
  
    public function smarts($id=null){
      
      $data = $this->AssignedTest->grabReportData($id);
      $folder = AuthComponent::user('DetailUser.uploadDir');
      $var = $data['Test']['report_function'].'_ind';
      #$cat_results = unserialize($data[0]['AssignedTest']['t_marks']);
      $answers = unserialize($data['AssignedTest']['t_marks']);
      
      $report = $this->Report->getReport($data['Test']['id']);
      foreach($report as $item){
          $ids = Set::extract( $item['children'], '{n}.Report.id' );
          $list['avg'] = Set::extract( $item['children'], '{n}.Report.min_score' );
          $list['stdev'] = Set::extract( $item['children'], '{n}.Report.max_score' );
      }
      
      $k = 0;
      
      foreach($ids as $id){
          $graphStdev[$id]['avg'] = $list['avg'][$k];
          $graphStdev[$id]['stdev'] = $list['stdev'][$k];
          $k++;
      }
      
      foreach($answers[34] as $key=>$section){
          if(isset($section['section'])){
              $name = 'smarts_'.$section['section'];
              $this->ReportGraph->bar_graph($section['total'],$name, $folder, $graphStdev[$section['section']]['avg'], $graphStdev[$section['section']]['stdev']);
          }
      }
      $report = $this->Report->getReport($data['Test']['id']);
      //pr($report);
      #exit;
      $folder = Router::url('/', true).''.$folder;
      $this->set( 'answers', $answers );
      $this->set( 'userInfo', $data['User'] );
      $this->set( 'AssignedTest', $data['AssignedTest'] );
      $this->set( 'report', $report );
      $this->set( 'folder', $folder );
      $this->set( 'id', $id );  
      
      
      $this->layout = 'member';
      $this->render('Report/34/report');
    }
  
    public function nurse_retention($id=null) {
      $data = $this->AssignedTest->grabReportData($id);
      $folder = AuthComponent::user('DetailUser.uploadDir');
      $var = $data['Test']['report_function'].'_ind';
      $cat_results = unserialize($data['AssignedTest']['t_marks']);
      $answers = unserialize($data['AssignedTest']['t_ans']);
      $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
      $graphData = $cat_results[28][253]['total'];
      
      $name = 'nurse_retention_'.$data['AssignedTest']['id'];
      $this->ReportGraph->percentile($graphData, $name, $folder);
      
      $report = $this->Report->getReport(362);
      
      $folder = Router::url('/', true).''.$folder;
      $this->set( 'answers', $answers );
      $this->set( 'userInfo', $data['User'] );
      $this->set( 'AssignedTest', $data['AssignedTest'] );
      $this->set( 'report', $report );
      $this->set( 'folder', $folder );
      $this->set( 'id', $id );  
      $this->set( 'score', $graphData );  
      
      $this->layout = 'member';
      $this->render('Report/28/report');
      #pr($cat_results);
      #pr($answers);
      #pr($data);
      #exit;
    }
  
    function iWRaCIP_individual($id=null){
      $data = $this->AssignedTest->grabReportData($id);
      $folder = AuthComponent::user('DetailUser.uploadDir');
      $var = $data['Test']['report_function'].'_ind';
      $answers = unserialize($data['AssignedTest']['t_marks']);
      
      //loop through answers to get the statements to view
      //behavior section
      for($i=0;$i<=3;$i++){
        $graphData[$answers[$i]['name']] = $answers[$i]['avg'];
      }
      $name = 'C4pie';
      $this->ReportGraph->pie_chart($graphData,$name);
        
      $name = 'C4line';
      $this->ReportGraph->line_chart($graphData,$name);
        
      $name = 'C4VertBar';
      $this->ReportGraph->verticle_bar_chart($graphData,$name);
        
      //Active/Passive
      for($i=4;$i<=5;$i++){
        $graphData2[$answers[$i]['name']] = $answers[$i]['avg'];
      }
        
      $name = 'AP';
      $this->ReportGraph->pie_chart($graphData2,$name);
        
      //Task/people
      for($i=6;$i<=7;$i++){
        $graphData3[$answers[$i]['name']] = $answers[$i]['avg'];
      }
        
      $name = 'TP';
      $this->ReportGraph->pie_chart($graphData3,$name);
        
      //Career section
      for($i=8;$i<=13;$i++){
        $graphData4[$answers[$i]['name']] = $answers[$i]['avg'];
      }
        
      $name = 'CIR';
      $this->ReportGraph->pie_chart($graphData4,$name);
        
      $addData[7]['name'] = $answers[1]['name'].'/'.$answers[1]['name'];
      $addData[7]['min_score'] = $answers[0]['max_score'];
      $addData[7]['max_score'] = $answers[1]['max_score'];
      $addData[7]['section_id'] = 210;
        
      if($answers[0]['avg'] != $answers[1]['avg']){
        unset($answers[1]);
      } 
          
      unset(
        $answers[2],
        $answers[3],
        $answers[5],
        $answers[7],
        $answers[11],
        $answers[12],
        $answers[13]
      );
        
      $answers = $answers + $addData;
      ksort($answers);
      #pr($answers);
      #exit;
      //get copy of report
      $report = $this->Report->getReport($data['Test']['id']);
      //loop through report and build graphs
      #pr($report);
      #exit;
      //$graphName = $this->ReportGraph->pie_chart();
      $folder = Router::url('/', true).''.$folder;
      #pr($folder);
      #exit;
      $this->set( 'folder', $folder );
      $this->set( 'answers', $answers );
      $this->set( 'report', $report );
      $this->set( 'id', $id );
      $this->layout = 'member';
      $this->render('Report/62/individual');
    }
  
    function iWRaCIP_employee($id=null, $test_id=null){
        $data = $this->AssignedTest->grabReportData($id);
        $answers = unserialize($data['AssignedTest']['t_marks']);
      
        foreach($answers as $key=>$answer){
            if($answer['section_id'] == 210){
                $answers[$key]['section_id'] = 268;
            }
      
            if($answer['section_id'] == 213){
                $answers[$key]['section_id'] = 269;
            }
        }
      
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
        if(!is_dir($folder)) {
            $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }
      
        for($i=0;$i<=3;$i++){
            $graphData[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
    
        $name = 'C4pie_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData,$name, $folder);
        
        $name = 'C4line_'.$data['AssignedTest']['id'];
        $this->ReportGraph->line_chart($graphData,$name, $folder);
        
        $name = 'C4VertBar_'.$data['AssignedTest']['id'];
        $this->ReportGraph->verticle_bar_chart($graphData,$name, $folder);
        
        //Active/Passive
        for($i=4;$i<=5;$i++){
          $graphData2[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'AP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData2,$name, $folder);
        
        //Task/people
        for($i=6;$i<=7;$i++){
          $graphData3[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'TP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData3,$name, $folder);
        
        //Career section
        for($i=8;$i<=13;$i++){
          $graphData4[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'CIR_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData4,$name, $folder);
        
        $addData[7]['name'] = $answers[1]['name'].'/'.$answers[1]['name'];
        $addData[7]['min_score'] = $answers[0]['max_score'];
        $addData[7]['max_score'] = $answers[1]['max_score'];
        $addData[7]['section_id'] = 268;
        
        if($answers[0]['avg'] != $answers[1]['avg']){
          unset($answers[1]);
        } 
          
        unset(
          $answers[2],
          $answers[3],
          $answers[5],
          $answers[7],
          $answers[11],
          $answers[12],
          $answers[13]
        );
        
        $answers = $answers + $addData;
        ksort($answers);
        //get copy of report
        $report = $this->Report->getReport(64);
        
        //loop through report and build graphs
        #pr($report);
        #exit;
        //$graphName = $this->ReportGraph->pie_chart();
        $folder = Router::url('/', true).''.$folder;
        $this->set( 'answers', $answers );
        $this->set( 'userInfo', $data['User'] );
        $this->set( 'AssignedTest', $data['AssignedTest'] );
        $this->set( 'report', $report );
        $this->set( 'folder', $folder );
        $this->set( 'id', $id );
        $this->layout = 'member';
        $this->render('Report/62/employer');
    
    }
  
    function iWRaCIP_coaching($id=null){
        $data = $this->AssignedTest->grabReportData($id);
        $folder = $data['Test']['id'];
        $var = $data['Test']['report_function'].'_ind';
        $answers = unserialize($data['AssignedTest']['t_marks']);
    
        foreach($answers as $key=>$answer){
            if($answer['section_id'] == 210){
                $answers[$key]['section_id'] = 296;
            }
      
            if($answer['section_id'] == 213){
                $answers[$key]['section_id'] = 297;
            }
        }
    
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
        if(!is_dir($folder)) {
            $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }   
        //loop through answers to get the statements to view
        //behavior section
        for($i=0;$i<=3;$i++){
            $graphData[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'C4pie_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData,$name, $folder);
    
        $name = 'C4line_'.$data['AssignedTest']['id'];
        $this->ReportGraph->line_chart($graphData,$name, $folder);
    
        $name = 'C4VertBar_'.$data['AssignedTest']['id'];
        $this->ReportGraph->verticle_bar_chart($graphData,$name, $folder);
    
        //Active/Passive
        for($i=4;$i<=5;$i++){
            $graphData2[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'AP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData2,$name, $folder);
    
        //Task/people
        for($i=6;$i<=7;$i++){
            $graphData3[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'TP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData3,$name, $folder);
    
        //Career section
        for($i=8;$i<=13;$i++){
            $graphData4[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'CIR_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData4,$name, $folder);
    
        $addData[7]['name'] = $answers[1]['name'].'/'.$answers[1]['name'];
        $addData[7]['min_score'] = $answers[0]['max_score'];
        $addData[7]['max_score'] = $answers[1]['max_score'];
        $addData[7]['section_id'] = 296;
    
        if($answers[0]['avg'] != $answers[1]['avg']){
            unset($answers[1]);
        } 
      
        unset(
            $answers[2],
            $answers[3],
            $answers[5],
            $answers[7],
            $answers[11],
            $answers[12],
            $answers[13]
        );
    
        $answers = $answers + $addData;
        ksort($answers);
        #pr($answers);
        #exit;
        
        //get copy of report
        $report = $this->Report->getReport(65);
        //loop through report and build graphs
        #pr($report);
        #exit;
        //$graphName = $this->ReportGraph->pie_chart();
    
        $folder = Router::url('/', true).''.$folder;
    
        $this->set( 'answers', $answers );
        $this->set( 'userInfo', $data['User'] );
        $this->set( 'AssignedTest', $data['AssignedTest'] );
        $this->set( 'report', $report );
        $this->set( 'folder', $folder );
        $this->set( 'id', $id );
        $this->layout = 'member';
        $this->render('Report/62/coaching');
    }
 
    function iWRaCIP_hiring($id=null, $test_id=null){
        $data = $this->AssignedTest->grabReportData($id);
        $answers = unserialize($data['AssignedTest']['t_marks']);
        
        #pr($data);
        #exit;
        foreach($answers as $key=>$answer){
            if($answer['section_id'] == 210){
                $answers[$key]['section_id'] = 358;
            }
          
            if($answer['section_id'] == 213){
                $answers[$key]['section_id'] = 351;
            }
        }
        
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
          
        if(!is_dir($folder)) {
              $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }
          
        for($i=0;$i<=3;$i++){
          $graphData[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'C4pie_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData,$name, $folder);
        
        $name = 'C4line_'.$data['AssignedTest']['id'];
        $this->ReportGraph->line_chart($graphData,$name, $folder);
        
        $name = 'C4VertBar_'.$data['AssignedTest']['id'];
        $this->ReportGraph->verticle_bar_chart($graphData,$name, $folder);
        
        $name = 'C4HorBar_'.$data['AssignedTest']['id'];
        $this->ReportGraph->horizontal_bar_chart($graphData,$name, $folder);
        
        //Active/Passive
        for($i=4;$i<=5;$i++){
          $graphData2[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'AP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData2,$name, $folder);
        
        //Task/people
        for($i=6;$i<=7;$i++){
          $graphData3[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'TP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData3,$name, $folder);
        
        //Career section
        for($i=8;$i<=13;$i++){
          $graphData4[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'CIR_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData4,$name, $folder);
        
        $addData[7]['name'] = $answers[1]['name'].'/'.$answers[1]['name'];
        $addData[7]['min_score'] = $answers[0]['max_score'];
        $addData[7]['max_score'] = $answers[1]['max_score'];
        $addData[7]['section_id'] = 268;
        
        if($answers[0]['avg'] != $answers[1]['avg']){
          unset($answers[1]);
        } 
          
        unset(
          $answers[2],
          $answers[3],
          $answers[5],
          $answers[7],
          $answers[11],
          $answers[12],
          $answers[13]
        );
        
        $answers = $answers + $addData;
        ksort($answers);
        //get copy of report
        $report = $this->Report->getReport(330);
        
        //loop through report and build graphs
        #pr($report);
        #exit;
        //$graphName = $this->ReportGraph->pie_chart();
        $folder = Router::url('/', true).''.$folder;
        $this->set( 'answers', $answers );
        $this->set( 'userInfo', $data['User'] );
        $this->set( 'AssignedTest', $data['AssignedTest'] );
        $this->set( 'report', $report );
        $this->set( 'folder', $folder );
        $this->set( 'id', $id );
        $this->layout = 'member';
        $this->render('Report/62/hiring');
    }
  
    public function buildGraphs($answers=null, $data=null){
        pr($data);
        exit;
        foreach($data as $item){
          $var = $item['graphing'];
          $graph = $this->ReportGraph->$var($answers,$item);
        }
        
        echo "nope";
        pr($data);
        exit;
    }
  
    public function buildTree($id){
        $parent = $this->Report->find('first', array('conditions' => array('Report.id' => $id)));
          #$this->Group->recursive = 1;
        $content = $this->Report->find('threaded', array(
          'conditions' => array(
            'Report.lft >=' => $parent['Report']['lft'], 
            'Report.rght <=' => $parent['Report']['rght']
          ),
        ));
        
        /*$content = $this->Report->find('all', array(
          'conditions'=>array(
            'Report.id' => $id
          ),
          
        ));*/
        pr($content);
        exit;
        foreach($content as $section){
          foreach($section['children'] as $key=>$item){
            #pr($item);
            $statements = $this->ReportStatement->getAllStatements($item['Report']['test_id'], $item['Report']['id']);
            
            if(!empty($statements)){
              /*if($this->Report->saveAll($statements)){
                echo "complete<br >";
               echo $item['Report']['id'] .'<br />';
              }else{
                echo "no<br />";
                echo $item['Report']['id'] .'<br />';
              } */
              
              
            }
            
          }
        }
        pr($content);
        exit;
    }
  
    function abc_report($id=null, $test_id=null){
        $data = $this->AssignedTest->grabReportData($id);
        $answers = unserialize($data['AssignedTest']['t_marks']);
      
        foreach($answers as $key=>$answer){
            if($answer['section_id'] == 210){
                $answers[$key]['section_id'] = 268;
            }
      
            if($answer['section_id'] == 213){
                $answers[$key]['section_id'] = 269;
            }
        }
        
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
        if(!is_dir($folder)) {
            $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }
      
        for($i=0;$i<=3;$i++){
            $graphData[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'C4pie_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData,$name, $folder);
    
        $name = 'C4line_'.$data['AssignedTest']['id'];
        $this->ReportGraph->line_chart($graphData,$name, $folder);
    
        $name = 'C4VertBar_'.$data['AssignedTest']['id'];
        $this->ReportGraph->verticle_bar_chart($graphData,$name, $folder);
        
        //Active/Passive
        for($i=4;$i<=5;$i++){
            $graphData2[$answers[$i]['name']] = $answers[$i]['avg'];
        }
        
        $name = 'AP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData2,$name, $folder);
        
        //Task/people
        for($i=6;$i<=7;$i++){
            $graphData3[$answers[$i]['name']] = $answers[$i]['avg'];
        }
    
        $name = 'TP_'.$data['AssignedTest']['id'];
        $this->ReportGraph->pie_chart($graphData3,$name, $folder);
        
        
        if($answers[0]['avg'] != $answers[1]['avg']){
            unset($answers[1]);
        } 
      
        unset(
            $answers[2],
            $answers[3],
            $answers[5],
            $answers[7]
        );
    
        $answers = $answers;
        ksort($answers);
    
        //get copy of report
        $report = $this->Report->getReport(404);
    
        //loop through report and build graphs
        #pr($report);
        #exit;
        //$graphName = $this->ReportGraph->pie_chart();
        $folder = Router::url('/', true).''.$folder;
        $this->set( 'answers', $answers );
        $this->set( 'userInfo', $data['User'] );
        $this->set( 'AssignedTest', $data['AssignedTest'] );
        $this->set( 'report', $report );
        $this->set( 'folder', $folder );
        $this->set( 'id', $id );
        $this->layout = 'member';
        $this->render('Report/404/report');
    
    }
  
    public function assessmentSummary($id=null){
        $data = $this->AssignedTest->grabReportData($id);
        $folder = AuthComponent::user('DetailUser.uploadDir');
        $var = $data['Test']['report_function'].'_ind';
        $averages = unserialize($data['AssignedTest']['t_marks']);
        $answers = unserialize($data['AssignedTest']['t_ans']);
        $this->set( 'id', $id );
      
        pr($answers);
        pr($averages);
        pr($data);
        exit;
    }
  
    public function groupSummary(){
    }
  
    public function evaluationSummary($id=null){
        $this->set( 'id', $id );
        
        $data = $this->AssignedTest->grabAllAssigned($id);
        
        $test = $this->Test->testDetails($data[0]['AssignedTest']['test_id']);
        $folder = 'groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number');
      
        if(!is_dir($folder)) {
            $dir = mkdir('groupFiles/'.AuthComponent::user('SupervisorOf.0.pin_number'), 0777);
        }
      
        //Grab Test Info
        foreach($data as $key=>$item){
            //this is by role
            $roleList[$item['TestRole']['name']] = $item['TestRole']['name'];
            $newAnswer[$item['TestRole']['name']][] = Hash::flatten(unserialize($item['AssignedTest']['t_ans']));
            $newData[$item['TestRole']['name']][] = Hash::flatten(unserialize($item['AssignedTest']['t_marks']));
          
            if($item['AssignedTest']['test_role_id'] == 5){
                $userData = $data[$key];
            }   
        }
      
        $roleCount = 0;
      
        foreach($newData as $role=>$q){
            $count  = count($q);
            foreach($q as $key=>$p){
                foreach($p as $keys=>$m){
                    $check = explode('.', $keys);
                    $countCheck = count($check);
                  
                    $id = $check[$countCheck - 2];
                  
                    $newKey = $id.'.category.catName';
                    $reportCat = $id.'.category.catReport';
                    $totalByCat = $id.'.category.catAvg';
                    $avgKey = $id.'.category.catRoleAverage.'.$role;
                    $value = $this->Test->getQuestions($id);
                  
                    $byRole[$newKey] = $value['Test']['name'];
                    $byRole[$reportCat] = $value['Test']['report_introduction'];
                  
                    if(isset($total[$avgKey])){
                        $total[$avgKey] += $newData[$role][$key][$keys];
                        $byRole[$avgKey] = $total[$avgKey]/$count;
                    }else{
                        $total[$avgKey] = $newData[$role][$key][$keys];
                        $byRole[$avgKey] = $total[$avgKey]/$count;
                    }
                  
                    if(isset($average[$totalByCat])){
                        $average[$totalByCat] += $newData[$role][$key][$keys];
                    }else{
                        $average[$totalByCat] = $newData[$role][$key][$keys];
                    }
                    #pr($average);
                }
                
                $roleCount++;
            }
        }
      
        foreach($average as $key=>$t){
            $average[$key] = round($t/$roleCount, 2);
        }
      
        $report = array_merge($byRole,$average);
      
        unset($average);
        $roleCount = 0;
      
        foreach($newAnswer as $role=>$q){
            $count  = count($q);
            $arrayCounter = 0;
            foreach($q as $key=>$p){
                foreach($p as $keys=>$m){
                    $check = explode('.', $keys);
                    $countCheck = count($check);
                    $var = $check[$countCheck - 1];
                  
                    if($var == 'answerValue' && is_numeric($m)){
                        $id = $check[$countCheck - 2];
                        $catId = $check[$countCheck - 3];
                      
                        $newKey = $catId.'.category.questions.'.$id.'.name';
                        $avgKey = $catId.'.category.questions.'.$id.'.roleAverage.'.$role;
                        $reportCat = $catId.'.category.questions.'.$id.'.average';
                      
                        $value = $this->Test->getQuestions($id);
                        $byRole[$newKey] = $value['Test']['name'];
                      
                    }else if($var == 'answerValue'){
                        $id = $check[$countCheck - 2];
                        $catId = $check[$countCheck - 3];
                      
                        $newKey = $catId.'.category.questions.'.$id.'.name';
                        $avgKey = $catId.'.category.questions.'.$id.'.comment.'.$role;
                        $reportCat = $catId.'.category.questions.'.$id.'.average';
                      
                        $value = $this->Test->getQuestions($id);
                        $byRole[$newKey] = $value['Test']['name'];
                    }
                  
                    if($var == 'answerValue' && is_numeric($m)){
                        if(isset($total[$avgKey]) && $var == 'answerValue'){
                            $total[$avgKey] += $newAnswer[$role][$key][$keys];
                            $byRole[$avgKey] = $total[$avgKey]/$count;
                            #$total[$role][$keys] += $newAnswer[$role][$key][$keys];
                            #$byRole[$role][$link] = $total[$role][$keys]/$count;
                        }else if($var == 'answerValue'){
                            $total[$avgKey] = $newAnswer[$role][$key][$keys];
                            $byRole[$avgKey] = $total[$avgKey]/$count;
                            #$total[$role][$keys] = $newAnswer[$role][$key][$keys];
                            #$byRole[$role][$link] = $total[$role][$keys]/$count;
                        }
                  
                        if(isset($average[$reportCat]) && $var == 'answerValue' ){
                            $average[$reportCat] += $newAnswer[$role][$key][$keys];
                        }else if($var == 'answerValue'){
                            $average[$reportCat] = $newAnswer[$role][$key][$keys];
                        }
                    }else if($var == 'answerValue'){
                        $byRole[$avgKey] = $m;
                    }
                }
                
                $roleCount++;
            }
        }
        
        foreach($average as $key=>$t){
            $average[$key] = round($t/$roleCount, 2);
        }
      
        $report = array_merge($report, $byRole);
        $report = array_merge($report, $average);
        //Build Graaphs
        $report = Hash::expand($report);
      
        foreach($report as $key=>$item){
            $overallCat[$item['category']['catName']] = $item['category']['catAvg'];
            $name = str_replace(' ', '', strtolower($item['category']['catName']));
          
            $report[$key]['category']['catRoleGraph'] = $this->ReportGraph->category_graph($item['category']['catRoleAverage'],$name,$folder);
          
            foreach($item['category']['questions'] as $sKey=>$details){
                if(isset($details['roleAverage'])){
                    $report[$key]['category']['questions'][$sKey]['qRoleGraph'] = $this->ReportGraph->category_graph($details['roleAverage'],$sKey,$folder);
                }
            }
        }
        arsort($overallCat);
        $name = 'summary';
      
        $summaryGraph = $this->ReportGraph->create_multi_bar_graph($overallCat,$name,$folder);
        #pr($report);
        #exit;
      
        $this->set( 'report', $report );
        $this->set( 'userInfo', $userData );
        $this->set( 'test', $test );
        $this->set( 'summaryGraph', $summaryGraph );
      
        $this->layout = 'member';
        $this->render('Report/general/evaluation');
    }
  
    public function store_for_later(){
        /*
        $query['Report']['parent_id'] = 368;
        $query['Report']['min_score'] = 4.1;
        $query['Report']['max_score'] = 5;
      
        $this->Report->create();
        $this->Report->save($query);
      
        $query['Report']['parent_id'] = 368;
        $query['Report']['min_score'] = 3.5;
        $query['Report']['max_score'] = 4;
      
        $this->Report->create();
        $this->Report->save($query);
      
        $query['Report']['parent_id'] = 368;
        $query['Report']['min_score'] = 2.5;
        $query['Report']['max_score'] = 3.49;
      
        $this->Report->create();
        $this->Report->save($query);
      
        $query['Report']['parent_id'] = 368;
        $query['Report']['min_score'] = 2.1;
        $query['Report']['max_score'] = 2.49;
      
        $this->Report->create();
        $this->Report->save($query);
      
        $query['Report']['parent_id'] = 368;
        $query['Report']['min_score'] = 1;
        $query['Report']['max_score'] = 2;
      
        $this->Report->create();
        $this->Report->save($query);
        */
    }
}