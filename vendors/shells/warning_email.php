<?php

class WarningEmailShell extends Shell{

    var $uses = array('User', 'Profile', 'House');

    function main(){
        
        $one_month_ago = date("Y-m-d", strtotime('-29 days')); ////because daysAsSql returns yesterday, count 29 instead of 30 days
        $two_months_ago= date("Y-m-d", strtotime('-59 days'));
        //pr($two_months_ago);die();

        App::import('Helper', 'Time');
        $time = new TimeHelper();
        $inactivity_warning_conditions = $time->daysAsSql($one_month_ago, $one_month_ago, "last_login", true);
        $inactivity_deactivation_conditions = $time->daysAsSql($two_months_ago, $two_months_ago, "last_login", true);
        
        //get users who have not accessed their account for the last month
        $inactivate_users = $this->User->find('all', array('conditions' => $inactivity_warning_conditions));
        //get users who have not accessed their account for the two last months
        $deactivated_users = $this->User->find('all', array('conditions' => $inactivity_deactivation_conditions));
        
        //get inactivate users emails and send warning email
        $inactivate_users_emails = array();
        for ($i=0; $i<count($inactivate_users); $i++){
            array_push($inactivate_users_emails, $inactivate_users[$i]['Profile']['email']);
        }
        //$this->email($inactivate_users_emails, 'warn');
        
        //get soon-to-be deactivated users emails, deactivate them and send relevant info email
        $deactivated_users_emails = array();
        for ($i=0; $i<count($deactivated_users); $i++){
            array_push($deactivated_users_emails, $deactivated_users[$i]['Profile']['email']);
        }
        $this->deactivate($deactivated_users);
        pr('end');die();
        //$this->email($deactivated_users_emails, 'deactivate');
                
    }


   function deactivate($deact_users){
    
        for ($i=0; $i<count($deact_users); $i++){
            
            $this->Profile->id = $deact_users[$i]['Profile']['id'];
            $this->Profile->saveField('visible', 0);
            
            if (isset($inv_users[$i]['House'][0]['id'])){
                $this->House->id = $inv_users[$i]['House'][0]['id'];
                $this->House->saveField('visible', 0);
            }
        }
   }


    function email($emails, $purpose){
        //import classes
        App::import('Core', 'Controller');
        App::import('Component', 'Email');
        //load classes, if loading model associations, components etc is needed
        $controller =& new Controller();
        $email =& new EmailComponent(null);
        $email->initialize($controller);

        for($i=0; $i<count($emails); $i++){
            $email->reset();
            $email->from = 'admin@roommates.edu.teiath.gr';
            if ($purpose == 'warn'){
                $email->subject = 'Προειδοποίηση για αδρανή λογαριασμό';
                $email->template = 'inactive_account';
            }else if ($purpose == 'deactivate'){
                $email->subject = 'Προειδοποίηση για απόκρυψη σπιτιού και προφίλ από τις αναζητήσεις λόγω αδρανούς λογαριασμού';
                $email->template = 'deactivated_account';
            }    
            $email->sendAs = 'both';
            $email->layout = 'default';
            $email->to = $emails[$i];
            $email->send();
        }
    }

}
?>
