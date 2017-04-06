<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Router', 'Routing');

class AssociateMailerShell extends AppShell {
    public $uses = array('Associate', 'Training.Trainings', 'Training.TrainingRecord' );

    public function run() {

        $this->Associate->bindModel(
            array('hasMany' => array(
                'TrainingRecord' => array(
                    'className' => 'Training.TrainingRecord'
                )
            ))
        );

        $associate_ids = Set::extract(
            $this->Associate->findAllNoSupervisors(),
            '/Associate/id'
        );

        $associates = $this->Associate->find('all',array(
            'conditions'=>array(
                'Associate.is_active'=> 1,
                'Associate.org_unit_id'=> 500
            ),
            'contain'=>array(
                'Supervisor'
            )
        ));

        $all_required_training = $this->Trainings->find('all',array(
            'conditions'=>array(
                'Trainings.is_required'=> 1,
                'Trainings.org_unit_id' => 500

            )

        ));

        $email = new CakeEmail();
        $email->config('default');
        $email->from(array('nick_robillard@goodyear.com' => 'SafeTrain'));

        $reminder_count = 0;

        foreach ( $associates as $associate ) {

            $expiring = $this->getExpiring( $associate['Associate']['id'], $all_required_training );
            //pr($expiring);

            $expired = $this->getExpired( $associate['Associate']['id'], $all_required_training );
            //pr($expired);

            $required = $this->getRequired( $associate['Associate']['id'], $all_required_training );

            $heading = $associate['Associate']['first_name'].' '.$associate['Associate']['last_name'].' ('.$associate['Associate']['userid'].")";
            $body = $heading ."\n";
            $body .= str_repeat( '-', strlen( $heading ) ) . "\n";

            if ( !empty( $required ) ) {
                $body .= "\nRequired HSE Training\n";
                $body .= "------------------\n";
                foreach( $required as $key=>$training ) {
                    $body .= 'TRN-' . str_pad( $training['id'], 4, 0, STR_PAD_LEFT ) .' '.$training['title'];
                    $body .= ' is required but not started'."!\n";
                }

                $body .= "\n";
            }

            if ( !empty( $expired ) ) {
                $body .= "\nExpired Training\n";
                $body .= "------------\n";
                foreach( $expired as $training ) {
                    $today      = new DateTime( date( 'Y-m-d', time() ) );
                    $expires    = new DateTime( date( 'Y-m-d', strtotime( $training[0][0]['expires_date'] ) ) );
                    $days       = $today->diff($expires)->days;

                    $body       .= 'TRN-' . str_pad( $training[0]['TrainingRecord']['training_id'], 4, 0, STR_PAD_LEFT ) .' '.$training[0]['Training']['title'];
                    $body       .= ' expired ' . $days  . ' days ago ';
                    $body       .= '(' . date(APP_DATE_FORMAT, strtotime( $training[0][0]['expires_date'] ) )  .")!\n";
                }
                $body .= "\n";
            }
            if ( !empty( $expiring ) ) {
                $body .= "\nExpiring Training\n";
                $body .= "-------------\n";
                foreach( $expiring as $training ) {
                    $today      = new DateTime( date( 'Y-m-d', time() ) );
                    $expires    = new DateTime( date( 'Y-m-d', strtotime( $training[0][0]['expires_date'] ) ) );
                    $days       = $today->diff($expires)->days;

                    $body       .= 'TRN-' . str_pad( $training[0]['TrainingRecord']['training_id'], 4, 0, STR_PAD_LEFT ) .' '.$training[0]['Training']['title'];
                    $body       .= ' will expire in ' .  $days . ' days';
                    $body       .= ' (' . date(APP_DATE_FORMAT, strtotime( $training[0][0]['expires_date'] ) )  .").\n";
                    unset( $days );
                }
                $body .= "\n";
            }
            $body .= "\n\n";

            //$training_url = Router::url( array('plugin'=>'training', 'controller'=>'training_records', 'action'=>'my_training') );
            $training_url = 'http://portal.goodyear.com/training/trainings';
            $body .= "The listed HSE training will be expiring or is expired and requires your attention.\n";
            $body .= "Visit $training_url to manage your HSE Training.\n";
            $body .= "Click on the renew button to complete and update your required training.";
            $body .= "\n\n";

            if ( !empty($required) OR !empty($expired) OR !empty($expiring) ) {

                //to trigger real emails the argument send_for_real has to be appended when running this task
                if (!empty($this->args[0]) AND $this->args[0] == 'send_for_real' ) {
                    //Send Email
                    $email->config('default');
                    $email->from(array('nick_robillard@goodyear.com' => 'SafeTrain'));
                    $email->to( array($associate['Associate']['email']=> $associate['Associate']['first_name'] . ' ' . $associate['Associate']['last_name'] ) );

                    //To Test uncomment the following line
                    //$email->bcc( array('nick_robillard@goodyear.com'=>'Nick Robillard' ) );

                    $email->subject( Configure::read('App.Name') . ' :: HSE Training Reminder :: Associate Report');
                    $email->send( $body );
                    Audit::log( 'HSE Training reminder Email Sent to ' .  $associate['Associate']['email'] . ', ' . $associate['Associate']['first_name'] . ' ' . $associate['Associate']['last_name'] , $email );
                    $email->reset();
                }
                $this->out( $body );
                $this->out('-----------------------------------------------------------------------------------------');
                unset( $body );
            }



        }
        $this->out( $reminder_count . ' total reminders.');


        if ( empty( $this->args[0] ) OR $this->args[0] != 'send_for_real' ) {
            $this->out( '' );
            $this->out( 'Notice: No emails were sent, to trigger real emails the argument send_for_real has to be appended when running this task.' );
            $this->out( '' );
            $this->out( '' );
        }

    }

    protected function getExpiring( $associate_id, $training_list ) {
        foreach($training_list as $training_item){
            $expired_signoffs[] = $this->TrainingRecord->find('all',array(
                'conditions'=>array(
                    'TrainingRecord.associate_id'=> $associate_id,
                    'TrainingRecord.training_id ' => $training_item['Trainings']['id']
                ),
                'fields' => array('max(TrainingRecord.expires) as expires_date', 'TrainingRecord.training_id', 'TrainingRecord.associate_id', 'Training.title'),
                'order'=>array('TrainingRecord.training_id ASC')
            ));
        }

        $expiring_training      = array();
        $expiring_date_range    = date('Y-m-d',strtotime('+45 days'));

        foreach($expired_signoffs as $expired){
            if(!empty($expired[0][0]['expires_date']) AND $expired[0][0]['expires_date'] >= date( DATE_MYSQL_DATE ) AND $expired[0][0]['expires_date'] <= $expiring_date_range){
                array_push($expiring_training,$expired);
            }
        }
        return $expiring_training;

    }

    protected function getExpired( $associate_id, $training_list ) {

        foreach($training_list as $training_item){
            $expired_signoffs[] = $this->TrainingRecord->find('all',array(
                'conditions'=>array(
                    'TrainingRecord.associate_id'=> $associate_id,
                    'TrainingRecord.training_id ' => $training_item['Trainings']['id']
                ),
                'fields' => array('max(TrainingRecord.expires) as expires_date', 'TrainingRecord.training_id', 'TrainingRecord.associate_id', 'Training.title'),
                'order'=>array('TrainingRecord.training_id ASC')
            ));
        }
        $expired_training = array();

        foreach($expired_signoffs as $expired){
            //Expired Training
            if(!empty($expired[0][0]['expires_date']) AND $expired[0][0]['expires_date'] <= date( DATE_MYSQL_DATE )){
                array_push($expired_training,$expired);
            }

        }

        return $expired_training;

    }

    protected function getRequired( $associate_id, $training_list ) {

        $required_training = array();

        foreach ( $training_list as $training_item ) {
            //Get required HSE Training, check that record exists
            $expired_signoffs = $this->TrainingRecord->find('count',array(
                'conditions'=>array(
                    'TrainingRecord.associate_id'=> $associate_id,
                    'TrainingRecord.training_id ' => $training_item['Trainings']['id']
                )
            ));

            if($expired_signoffs <= 0){

                $insert_array['id']     = $training_item['Trainings']['id'];
                $insert_array['title']  = $training_item['Trainings']['title'];

                array_push($required_training,$insert_array );
            }
        }
        return $required_training;
    }
}





