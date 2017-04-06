<?php
    /**
    * 
    * Find Expired Training and email associate reminder
    * 
    * Conditions:
    * inspection_id != null
    * associate_id != null
    * notify_on <= TODAY
    * 
    */
    App::uses('CakeEmail', 'Network/Email');
    App::uses('Router', 'Routing');

    
    class SupervisorMailerShell extends AppShell {
        public $uses = array('Associate', 'Trainings.TrainingRecords' );

        public function run() {
            $this->out( 'Inspections SupervisorMailerShell Executing' );
            $this->out( date( APP_DATETIME_FORMAT ) );
            
            #$this->ScheduledInspection->recursive = -1;
            $t_records = $this->TrainingRecord->find( 'all', array(
                'conditions'=>array(
                    'TrainingRecord.inspection_id'=>null,
                    'ScheduledInspection.notify_daily'=>1,
                    'ScheduledInspection.associate_id >'=>0,
                    'ScheduledInspection.notify_supervisor_on <='=> date( DATE_MYSQL_DATE ),       
                    'ScheduledInspection.due_on >='=> date( DATE_MYSQL_DATE )
                ),
                'contain'=>array(
                    'Associate',
                    'Group'=>array(
                        'Supervisor'
                    ),
                    'InspectionTemplate'
                )
            ));
            
            $this->out( 'Matched ' . count( $schedules ) . ' scheduled inspections with query.' );

            $email = new CakeEmail();
            $email->config('default');
            $email->from(array('nick_robillard@goodyear.com' => 'SafeTrain'));

            $reminder_count = 0;
            //$url_link = Router::url( array('plugin'=>'inspections', 'controller'=>'scheduled_inspections', 'action'=>'index'), true );
            $url_link = 'http://portal.goodyear.com/inspections/scheduled_inspections'; 
            foreach ( $schedules as $schedule ) {
                //Continue to next if group has no supervisor for some reason.
                $super = $schedule['Group']['Supervisor'];
                if ( empty( $super['email'] ) ) {
                    //continue;
                }
                
                $body = '' . $super['first_name'] . ' ' . $super['last_name'] . ",\n\n";

                $body .= "The following Safety Inspection is due on ";
                $body .= date( 'l', strtotime( $schedule['ScheduledInspection']['due_on'] ) );
                $body .= " and has yet to be completed.\n";
                $body .= "\n";
                $body .= $schedule['ScheduledInspection']['name'] . "\n";
                $body .= __('Inspector') . ': ' .  $schedule['Associate']['first_name'] . ' ' . $schedule['Associate']['last_name']  . "\n";
                $body .= __('Due on') . ' ' . date( APP_DATE_FORMAT, strtotime( $schedule['ScheduledInspection']['due_on'] ) ) . "\n";
                $body .= "\n";
                $body .= $url_link;
                $body .= "\n\n";

                //to trigger real emails the argument send_for_real has to be appended when running this task
                if (!empty($this->args[0]) AND $this->args[0] == 'send_for_real' ) {
                    //Send Email
                    $email->config('default');
                    $email->from(array('nick_robillard@goodyear.com' => Configure::read('App.Name') ));
                    $email->to( array(
                        $super['email'] => $super['first_name'] 
                            . ' ' . $super['last_name']
                    ) );
                    
                    //To Test uncomment the following line
                    //$email->to( array('jonathan_cutrer@goodyear.com'=>'Jonathan Cutrer' ) );
                    
                    $email->cc( array('nick_robillard@goodyear.com'=>'Nick Robillard' ) );

                    $email->subject( Configure::read('App.Name') . ' :: ' . __('Scheduled Inspection Reminder') );
                    $email->send( $body ); 
                    $reminder_count++;
                    $email->reset();
                } 
                $this->out( $body );
                $this->out('-----------------------------------------------------------------------------------------');
                unset( $body );
            }


            $this->out( $reminder_count . ' total emails sent.');


            if ( empty( $this->args[0] ) OR $this->args[0] != 'send_for_real' ) {
                $this->out( '' );
                $this->out( 'Notice: No emails were sent, to trigger real emails the argument send_for_real has to be appended when running this task.' );
                $this->out( 'Notice: No emails were sent, to trigger real emails the argument send_for_real has to be appended when running this task.' );
                $this->out( '' );
                $this->out( '' );
            } 

        } 

}