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

        //get (only) users data and preferences
        $users = $this->Profile->find('all', array('conditions' => array( 'User.role' => 'user', 'Profile.get_mail' => 1)));

        $email_users = $this->get_emails( $users );


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


        //return user email with matched house or profile id or both
        //users can be only in one category email_houses ,email_profiles, 
        //email_both. Return all categories
        private function get_emails( $users ){
        
            
            $email_houses = array();
            $email_profiles = array();
            //email both wil have nested houses and profiles arrays
            $email_both = array();

             for ($i=0; $i<count($users); $i++){

                $compatible_houses = array();
                $compatible_profiles = array();

                try{
                    //Get arrays with user's compatible Houses and Profiles 
                    $compatible_houses = $this->get_prefered_records( $users[$i], 'House' );
                    $compatible_profiles = $this->get_prefered_records( $users[$i], 'Profile' );
                }catch( Exception $ex ){
                    echo $ex->getMessage();
                }

                //filtering House ids
                $house_ids = array();
                foreach( $compatible_houses as $house ){
    
                    $house_ids[] = $house['House']['id'];
                }

                //filtering profiles ids
                $profile_ids = array();
                foreach( $compatible_profiles as $profile ){
    
                    $profile_ids[] = $profile['Profile']['id'];
                }

                //True set in both email
                if( !empty($profile_ids) && !empty($house_ids) ){

                    $email_both[$users[$i]['Profile']['email']] = array( 'houses'=>$house_ids, 
                        'profiles'=>$profile_ids );

                }else if( !empty( $house_ids) ){

                    $email_houses[$users[$i]['Profile']['email']] = $house_ids;

                } else if( !empty( $profile_ids) ){

                    $email_profiles[$users[$i]['Profile']['email']] = $profile_ids;
                }
             }

            return array( 'email_houses' => $email_houses ,'email_profiles'=>$email_profiles, "email_both"=>$email_both);
       }
     
        // return all prefered houses or profiles for each user
        //
        private function get_prefered_records( $user, $type ){

            $today = date('Y-m-d', strtotime("+1 day")); //because daysAsSql returns yesterday, use strtotime
            $from = $today;
            $to = $today;
            App::import('Helper', 'Time');
            $time = new TimeHelper();
            $conditions_created = $time->daysAsSql($from, $to, "created", true);
            $conditions_modified = $time->daysAsSql($from, $to, "modified", true);
            //this is standard conditions about Profiles and Houses records
            $standard_conditions = array( 'OR' => array( $conditions_created, $conditions_modified ));
           
            $conditions = array();
            if( $type === 'House' ){

                $conditions = array_merge( 

                            $this->getHousePrefs( $user ),

                            array(  

                                'House.user_id <>' => $user['User']['id']
                            )
                        );
            } else if( $type === 'Profile' ) {

                $conditions = array_merge( 

                            $this->getProfilePrefs( $user ),

                            array(  

                                'Profile.user_id <>' => $user['User']['id']
                            )
                        );
            } else {

                throw new Exception('False type parameter: Given type not recognized');
            }


            $conditions = array_merge( $conditions, $standard_conditions  );
            $records = 
                ($type === 'House')?$this->House->find('all', array( 'conditions' => $conditions, 'fields'=> 'House.id', 'recursive'=>0))
                :$this->Profile->find('all', array( 'conditions' => $conditions, 'fields'=> 'Profile.id', 'recursive'=>0));


            return $records;       
        }

        private function getHousePrefs( $user ){

            //HousesController needs Controller class
            App::import('Core', 'Controller');
            App::import('Controller', 'Houses');
            $House = new HousesController;

            $user_prefs = array( 'max_price'=>$user['Preference']['price_max'],
                'max_area'=>$user['Preference']['area_max'],
                'min_area'=> $user['Preference']['area_min'], 
                'municipality'=>$user['Preference']['pref_municipality'],
                'bedroom_num_min'=>$user['Preference']['bedroom_num_min'],
                'furnitured'=>$user['Preference']['pref_furnitured'],
                'floor_min'=>$user['Preference']['floor_id_min'],
                'bathroom_num_min'=>$user['Preference']['bathroom_num_min'],
                'construction_year_min'=>$user['Preference']['construction_year_min'],
                'rent_period_min'=>$user['Preference']['rent_period_min'],
                'solar_heater'=>$user['Preference']['pref_solar_heater'],
                'aircondition'=>$user['Preference']['pref_aircondition'],
                'garden'=>$user['Preference']['pref_garden'],
                'parking'=>$user['Preference']['pref_parking'],
                'security_doors'=>$user['Preference']['pref_security_doors'],
                'storeroom'=>$user['Preference']['pref_storeroom'],
                'house_type'=>$user['Preference']['pref_house_type_id'],
                'heating_type'=>$user['Preference']['pref_heating_type_id'],
                'no_shared_pay'=>$user['Preference']['pref_shared_pay'],
                'availability_date_min'=>$user['Preference']['availability_date_min'],
                'accessibility'=>$user['Preference']['pref_disability_facilities'] );

            
            $house_conditions = $House->getHouseConditions( $user_prefs );
            return $house_conditions;

        }


        private function getProfilePrefs( $user ){

            //HousesController needs Controller class
            App::import('Core', 'Controller');
            App::import('Controller', 'Houses');
            $House = new HousesController;

            $user_prefs = array( 'min_age'=>$user['Preference']['age_min'],
                'max_age'=>$user['Preference']['age_max'],
                'gender'=> $user['Preference']['pref_gender'], 
                'smoker'=>$user['Preference']['pref_smoker'],
                'pet'=>$user['Preference']['pref_pet'],
                'child'=>$user['Preference']['pref_child'],
                'couple'=>$user['Preference']['pref_couple'] );

            
            $profile_conditions = $House->getMatesConditions( $user_prefs, false);
            return $profile_conditions;

        }
   


        //email all is array => email_houses, email_profiles, email_both
        function email($email_all){
            //import classes
            App::import('Core', 'Controller');
            App::import('Component', 'Email');
            //load classes, if loading model associations, components etc is needed
            $controller =& new Controller();
            $email =& new EmailComponent(null);
            $email->initialize($controller);

            //array with addresses and house profile ids
            if( !empty($email_all['email_houses']) ){
                var_dump("House");
                $this->send_mail_to($email_all['email_houses'], 'houses', 
                    $controller, $email, 'Τελευταίες καταχωρίσεις σπιτιών', 'cron_house_match' );
            }

            if( !empty($email_all['email_profiles'])){
                var_dump("profiles");
                $this->send_mail_to($email_all['email_profiles'], 'profiles', 
                    $controller, $email, 'Τελευταίες καταχωρίσεις προφίλ', 'cron_profile_match' );
            }


            if( !empty($email_all['email_both']) ){
                var_dump("both");
                var_dump($email_all['email_both']);
                $this->send_mail_to($email_all['email_both'], 'both', 
                    $controller, $email, 'Τελευταίες καταχωρίσεις σπιτιών και προφίλ', 'cron_both_match' );
            }

            die();
            
        }

        //Send mail to users with different tenplate for each category
        //type for ids match
        //email_all an array of mails for one category
        //subject and template for email configuration
        //cotroller email objects
        private function send_mail_to( $email_all, $type, &$controller, &$email, $subject, $template ){

            
                $email_addr = array_keys($email_all);

                //send mail for each address
                for($i=0; $i < count($email_addr); $i++){
                    $email->reset();

                    $email->from = 'admin@roommates.edu.teiath.gr';
                    $email->subject = $subject;
                    $email->sendAs = 'both';
                    $email->template = $template;
                    $email->layout = 'default';

                    //for both
                    if( $type === 'both'){

                        $house_ids = $email_all[$email_addr[$i]]['houses'];
                        $profile_ids = $email_all[$email_addr[$i]]['profiles'];
                        $controller->set('houses_count', count($house_ids));
                        $controller->set('profiles_count', count($profile_ids));

                        $house_links = "";
                        for($j=0; $j<count($house_ids); $j++){
                            $link = "http://roommates.edu.teiath.gr/houses/view/{$house_ids[$j]}";
                            $house_links .= "<a href=\"{$link}\">{$link}</a><br />";
                        }

                        var_dump($house_links);
                        $profile_links = "";
                        for($j=0; $j<count($profile_ids); $j++){
                            $link = "http://roommates.edu.teiath.gr/profiles/view/{$profile_ids[$j]}";
                            $profile_links .= "<a href=\"{$link}\">{$link}</a><br />";
                        }
                
                        var_dump($profile_links);
                        $email->to = $email_addr[$i];
                        $controller->set('house_links', $house_links);
                        $controller->set('profile_links', $profile_links);
                        //$email->send();

                    } else {

                        $ids = $email_all[$email_addr[$i]]; 
                        $controller->set('count', count($ids));
                    
                        
                        $links = "";
                        for($j=0; $j<count($ids); $j++){
                            $link = "http://roommates.edu.teiath.gr/".$type."/view/{$ids[$j]}";
                            $links .= "<a href=\"{$link}\">{$link}</a><br />";
                        }
                        var_dump($links);
                        $email->to = $email_addr[$i];
                        $controller->set('links', $links);
                        //$email->send();
                    }
            } 
        }

}
?>
