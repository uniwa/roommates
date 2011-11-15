<?php
class EmailShell extends Shell{

    var $uses = array('House', 'Preference', 'User', 'Profile');
    
    function main(){
        
        $today = date('Y-m-d', strtotime("+1 day")); //because daysAsSql returns yesterday, use strtotime
        $from = $today; 
        $to = $today;
        App::import('Helper', 'Time');
        $time = new TimeHelper();
        $conditions_created = $time->daysAsSql($from, $to, "created", true);
        $conditions_modified = $time->daysAsSql($from, $to, "modified", true);
        $conditions = '(' . $conditions_created . ') OR (' .$conditions_modified . ')'; 
        //echo($conditions);die();

        
        //get houses created or modified today
        $houses = $this->House->find('all', array('conditions' => $conditions));
        //pr($houses);die();  //House, HouseType, Floor, HeatingType, Municipality, User, Image 


        //get (only) users data and preferences 
        $users = $this->Profile->find('all', array('conditions' => array( 'User.role' => 'user', 'Profile.get_mail' => 1)));
        //pr($users);die(); //User, Preference, Profile 

        $email_users = array();

        for ($i=0; $i<count($users); $i++){

            $compatible_houses = array();
            
            for($j=0; $j<count($houses); $j++){
                
                if(    ( $this->compare_min($houses[$j]['House']['area'], $users[$i]['Preference']['area_min']))
                    && ( $this->compare_max($houses[$j]['House']['area'], $users[$i]['Preference']['area_max']))
                    && ( $this->compare_min($houses[$j]['House']['bedroom_num'], $users[$i]['Preference']['bedroom_num_min']))
                    && ( $this->compare_max($houses[$j]['House']['price'], $users[$i]['Preference']['price_max']))
                    && ( $this->compare_min($houses[$j]['House']['floor_id'], $users[$i]['Preference']['floor_id_min']) )
            
                    && ( $this->compare_pref_min($houses[$j]['House']['bathroom_num'], $users[$i]['Preference']['bathroom_num_min']))
                    && ( $this->compare_pref_min($houses[$j]['House']['construction_year'], $users[$i]['Preference']['construction_year_min']) )
                    && ( $this->compare_pref_min($houses[$j]['House']['rent_period'], $users[$i]['Preference']['rent_period_min']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['solar_heater'], $users[$i]['Preference']['pref_solar_heater']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['aircondition'], $users[$i]['Preference']['pref_aircondition']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['garden'], $users[$i]['Preference']['pref_garden']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['parking'], $users[$i]['Preference']['pref_parking']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['security_doors'], $users[$i]['Preference']['pref_security_doors']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['storeroom'], $users[$i]['Preference']['pref_storeroom']) )
                    
                    && ( $this->compare_equal($houses[$j]['House']['house_type_id'], $users[$i]['Preference']['pref_house_type_id']))
                    && ( $this->compare_equal($houses[$j]['House']['heating_type_id'], $users[$i]['Preference']['pref_heating_type_id']))
                    
                    && ( $this->compare_equal_null($houses[$j]['House']['municipality_id'], $users[$i]['Preference']['pref_municipality']))
                    && ( $this->shared_pay($houses[$j]['House']['shared_pay'], $users[$i]['Preference']['pref_shared_pay']) )
                    
                    && ( $this->compare_date_min($houses[$j]['House']['availability_date'], $users[$i]['Preference']['availability_date_min']) )
                    
                    && ( $this->compare_checkbox($houses[$j]['House']['furnitured'], $users[$i]['Preference']['pref_furnitured']) )
                    && ( $this->compare_checkbox($houses[$j]['House']['disability_facilities'], $users[$i]['Preference']['pref_disability_facilities']) )

                    && ( $this->has_photo($houses[$j]['House']['default_image_id'], $users[$i]['Preference']['pref_has_photo']))
                    
                )
                {
                    array_push($compatible_houses, $houses[$j]['House']['id']);
                }
            }
            // skip if no houses found
            if (empty($compatible_houses)) {
                continue;
            }
            $email_users[$users[$i]['Profile']['email']] = $compatible_houses;
        }

        if (count($this->args) === 0) {
            $this->email($email_users);
        }
        else {
            $email_users = array();
            $emails = explode(" ", $this->args[0]);
            foreach($emails as $email) {
                // generate 5 random id's per user
                $id[0] = rand(0, 100);
                $id[1] = rand(0, 100);
                $id[2] = rand(0, 100);
                $id[3] = rand(0, 100);
                $id[4] = rand(0, 100);
                $email_users[$email] = $id;
            }
            $this->email($email_users);
        }
    }

        //refers to the compulsory field for the owner's house (availability_date)
        //default in Preferences --> 0000-00-00
        private function compare_date_min($attr, $pref){
            if ($pref == "0000-00-00"){
                return true;
            }else{
                if( strtotime($attr) <= strtotime($pref)){
                    return true;    
                }else{
                    return false;    
                }
            }
        }

        //refers to optional checkbox fields for the owner's house (solar_heater, air_condition, garden, parking, security_doors, storeroom)
        //default in Preferences --> NULL
        //default in Houses --> NULL
        private function compare_checkbox_null($attr, $pref){
            if( isset($pref) && ($pref) ){
                if ( isset($attr) && ($attr) ){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }



        //refers to optional checkbox fields (furnitured, disability_facilities)  
        //default in Preferences --> 2, 0
        //default in Houses --> NULL
        private function compare_checkbox($attr, $pref){
            if($pref == 1){
                if (isset($attr) && ($attr) ){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;    
            }
        }


        //refers to optional checkbox field shared_pay  
        //default in Preferences --> NULL
        //default in Houses --> NULL
        private function shared_pay($attr, $pref){
            if ( isset($pref) && ($pref) ){
                if ( (!isset($attr)) || (!($attr)) ){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;    
            } 
        }

  
        //default in Preferences --> 0
        //default in Houses --> NULL
        private function has_photo($attr, $pref){
            if ($pref){
                if( isset($attr) && ($attr) ){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;    
            }
        }


        //refers to optional fields for the owner's house, that have to be greater or equal from the user's relevant preferences (rent_period_min, construction_year_min, bathroom_num_min)
        private function compare_pref_min($attr, $pref){
            if( isset($pref) ){
              if( isset($attr) && ($attr >= $pref) ){
                return true;    
              }else{
                return false;
              }
            }else{
                return true;    
            }
        }

        //refers to compulsory fields for the owner's house, that have to be less or equal from the user's relevant preferences (area_max, price_max)
        private function compare_max($attr, $pref){
            if( isset($pref) ){
                if ($attr <= $pref) return true;
                else return false;
            }else{   
                return true;
            }
        }

        //refers to compulsory fields for the owner's house, that have to be greater or equal from the user's relevant preferences (area_min, bedroom_num_min, floor_id_min)
        private function compare_min($attr, $pref){
            if( isset($pref) ){
                if ($attr >= $pref) return true;
                else return false;
            }else{   
                return true;
            }
        }

        //refers to compulsory fields for the owner's house, that have to be equal with the user's relevant preferences (house_type_id, heating_type_id)
        //default in Preferences --> 0
        private function compare_equal($attr, $pref){
            if( $pref == 0  || $attr == $pref ){
                return true;
            }else{
                return false;
            }
        }

        //refers to compulsory fields for the owner's house, that have to be equal with the user's relevant preferences (municipality_id)
        //default in Preferences --> NULL
        private function compare_equal_null($attr, $pref){
            if(isset($pref)){
                if($pref == $attr){
                    return true;    
                }else{
                    return false;
                }
            }else{
                return true;    
            }
        }


        function email($email_all){
            //import classes
            App::import('Core', 'Controller');
            App::import('Component', 'Email');
            //load classes, if loading model associations, components etc is needed
            $controller =& new Controller();
            $email =& new EmailComponent(null);
            $email->initialize($controller);

            $email_addr = array_keys($email_all);   //all users email addresses that will receive mail

            for($i=0; $i<count($email_addr); $i++){
                $email->reset();

                $email->from = 'admin@roommates.edu.teiath.gr';
                $email->subject = 'Ενημέρωση για νέα σπίτια που ταιριάζουν στις προτιμήσεις σας';
                $email->sendAs = 'both';
                $email->template = 'cron_house_match';

                $houses_ids = $email_all[$email_addr[$i]]; //houses ids
                $controller->set('house_count', count($houses_ids));

                //$links = array();
                $links = "";
                for($j=0; $j<count($houses_ids); $j++){
                    //array_push($links, 'http://roommates.edu.teiath.gr/houses/view/' . $houses_ids[$j]);
                    $house_link = "http://roommates.edu.teiath.gr/houses/view/{$houses_ids[$j]}";
                    $links .= "<a href=\"{$house_link}\">{$house_link}</a><br />";
                }
                //pr($links);die();
                //echo $houses_ids;
                //echo $email_addr[$i];
                $email->to = $email_addr[$i];
                $controller->set('links', $links);
                $email->send();
            }
        }

}
?>
