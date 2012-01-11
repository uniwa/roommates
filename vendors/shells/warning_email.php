<?php

class WarningEmailShell extends Shell{

    var $uses = array('User', 'Profile', 'House');

    function main(){
        
        $one_month_ago = date("Y-m-d", strtotime('-1 month'));
        $two_months_ago= date("Y-m-d", strtotime('-2 months'));

        App::import('Helper', 'Time');
        $time = new TimeHelper();
        $inactivity_warning_conditions = "(last_login like '".$one_month_ago. "%')";
    $inactivity_deactivation_conditions = "(last_login like '".$two_months_ago. "%')";

        //get users who have not accessed their account for the last month
        $inactivate_users = 
        $this->User->find('all', 
            array('conditions' => $inactivity_warning_conditions));
        //get users who have not accessed their account for the two last months
        $deactivated_users =
        $this->User->find('all', 
            array('conditions' => $inactivity_deactivation_conditions));

        //get soon-to-be deactivated users emails, deactivate them and send relevant info email
        $deactivated_users_emails = array();
        for ($i=0; $i<count($deactivated_users); $i++){
        if (isset($deactivated_users[$i]['Profile']['email'])) {
                array_push($deactivated_users_emails, $deactivated_users[$i]['Profile']['email']);
        }
        }

        //get inactivate users emails and send warning email
        $inactivate_users_emails = array();
        for ($i=0; $i<count($inactivate_users); $i++){
        if (isset($inactivate_users[$i]['Profile']['email'])) {
            if ( in_array($inactivate_users[$i]['Profile']['email'], $deactivated_users_emails) ) {
                continue;
            }
                    array_push($inactivate_users_emails, $inactivate_users[$i]['Profile']['email']);
        } 
        }

    if (! empty($inactivate_users_emails)) {
            $this->email($inactivate_users_emails, 'warn');
    }
        
    if (! empty($deactivated_users_emails)) {
            $this->deactivate($deactivated_users);
            $this->email($deactivated_users_emails, 'deactivate');
    }
                
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
