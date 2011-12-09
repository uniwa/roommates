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

                //Categorize users
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

                                'House.user_id !=' => (int)$user['User']['id']
                            )
                        );
            } else if( $type === 'Profile' ) {

                $conditions = array_merge( 

                            $this->getProfilePrefs( $user ),

                            array(  

                                'Profile.user_id !=' => (int)$user['User']['id']
                            )
                        );
            } else {

                throw new Exception('False type parameter: Given type not recognized');
            }


            $conditions = array_merge( $conditions, $standard_conditions  );
            $records = 
                ($type === 'House')?$this->House->find('all', array( 'conditions' => $conditions, 'fields'=> 'House.id', 'recursive'=>0))
                :$this->Profile->find('all', array( 'conditions' => $conditions, 'fields'=> 'Profile.id', 'recursive'=>0));

            //var_dump("conditions");
            //var_dump($conditions);
            //var_dump("records");
            //var_dump($records);

            return $records;       
        }

        private function getHousePrefs( $user ){

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

            $house_conditions = $this->get_house_conditions( $user_prefs );
            return $house_conditions;

        }

        private function getProfilePrefs( $user ){

            $user_prefs = array( 'min_age'=>$user['Preference']['age_min'],
                'max_age'=>$user['Preference']['age_max'],
                'gender'=> $user['Preference']['pref_gender'], 
                'smoker'=>$user['Preference']['pref_smoker'],
                'pet'=>$user['Preference']['pref_pet'],
                'child'=>$user['Preference']['pref_child'],
                'couple'=>$user['Preference']['pref_couple'] );

            
            $profile_conditions = $this->get_mates_conditions( $user_prefs );
            return $profile_conditions;

        }


        private function get_house_conditions( $house_prefs ){

            $house_conditions = array();

            // primary conditions
            if(!empty($house_prefs['max_price']))
                $house_conditions['House.price <='] = $house_prefs['max_price'];

            if(!empty($house_prefs['min_area']))
                $house_conditions['House.area >='] = $house_prefs['min_area'];

            if(!empty($house_prefs['max_area']))
                $house_conditions['House.area <='] = $house_prefs['max_area'];

            if(!empty($house_prefs['municipality']))
                $house_conditions['House.municipality_id'] =
                                                    $house_prefs['municipality'];

            if (  $house_prefs['furnitured'] != 2 )
                $house_conditions['House.furnitured'] = $house_prefs['furnitured'];
            
            if($house_prefs['accessibility'] != 0 )
                $house_conditions['House.disability_facilities'] = 1;

            // secondary conditions
            if($house_prefs['house_type'] != 0)
                $house_conditions['House.house_type_id'] =
                                                        $house_prefs['house_type'];
            if( $house_prefs['heating_type'] != 0 )
                $house_conditions['House.heating_type_id'] =
                                                    $house_prefs['heating_type'];
            
            if(!empty($house_prefs['bedroom_num_min']))
                $house_conditions['House.bedroom_num >='] =
                                                    $house_prefs['bedroom_num_min'];

            if(!empty($house_prefs['bathroom_num_min']))
                $house_conditions['House.bathroom_num >='] =
                                                $house_prefs['bathroom_num_min'];

            if(!empty($house_prefs['construction_year_min']))
                $house_conditions['House.construction_year >='] =
                                            $house_prefs['construction_year_min'];

            if($house_prefs['floor_min'] != 0)
                $house_conditions['House.floor_id >='] = $house_prefs['floor_min'];

            if(!empty($house_prefs['rent_period_min']))
                $house_conditions['House.rent_period >='] =
                                                    $house_prefs['rent_period_min'];
            if(!empty($house_prefs['solar_heater']))
                $house_conditions['House.solar_heater'] = 1;

            if(!empty($house_prefs['aircondition']))
                $house_conditions['House.aircondition'] = 1;

            if(!empty($house_prefs['garden']))
                $house_conditions['House.garden'] = 1;

            if(!empty($house_prefs['parking']))
                $house_conditions['House.parking'] = 1;

            if(!empty($house_prefs['no_shared_pay']))
                $house_conditions['House.shared_pay'] = 0;

            if(!empty($house_prefs['security_doors']))
                $house_conditions['House.security_doors'] = 1;

            if(!empty($house_prefs['storeroom']))
                $house_conditions['House.storeroom'] = 1;

            
            if($house_prefs['availability_date_min'] != '0000-00-00'){

                $year  = $house_prefs['availability_date_min']['year'];
                $month = $house_prefs['availability_date_min']['month'];
                $day   = $house_prefs['availability_date_min']['day'];

                $house_conditions['House.availability_date >='] =
                                                $year . '-' . $month . '-' . $day;
            }

            $house_conditions['House.visible'] = 1;

            $house_conditions['User.banned !='] = 1;

            return $house_conditions;
        }

        private function get_mates_conditions( $mates_prefs){


            $mates_conditions = array();

            if(!empty($mates_prefs['min_age'])) {
                $mates_conditions['Profile.dob <='] = $this->age_to_year($mates_prefs['min_age']);
            }
            if(!empty($mates_prefs['max_age'])) {
                $mates_conditions['Profile.dob >='] = $this->age_to_year($mates_prefs['max_age']);
            }
            if($mates_prefs['gender'] < 2 && $mates_prefs['gender'] != null) {
                $mates_conditions['Profile.gender'] = $mates_prefs['gender'];
            }
            if($mates_prefs['smoker'] < 2 && $mates_prefs['smoker'] != null) {
                $mates_conditions['Profile.smoker'] = $mates_prefs['smoker'];
            }
            if($mates_prefs['pet'] < 2 && $mates_prefs['pet'] != null) {
                $mates_conditions['Profile.pet'] = $mates_prefs['pet'];
            }
            if($mates_prefs['child'] < 2 && $mates_prefs['child'] != null) {
                $mates_conditions['Profile.child'] = $mates_prefs['child'];
            }
            if($mates_prefs['couple'] < 2 && $mates_prefs['couple'] != null) {
                $mates_conditions['Profile.couple'] = $mates_prefs['couple'];
            }

            if(empty($mates_conditions)) return array();

            return $mates_conditions;

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
                //var_dump("House");
                $this->send_mail_to($email_all['email_houses'], 'houses', 
                    $controller, $email, 'Τελευταίες καταχωρίσεις σπιτιών', 'cron_house_match' );
            }

            if( !empty($email_all['email_profiles'])){
                //var_dump("profiles");
                //var_dump( $email_all['email_profiles'] );
                $this->send_mail_to($email_all['email_profiles'], 'profiles', 
                    $controller, $email, 'Τελευταίες καταχωρίσεις προφίλ', 'cron_profile_match' );
            }


            if( !empty($email_all['email_both']) ){
                //var_dump("both");
                //var_dump($email_all['email_both']);
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

                        //var_dump($house_links);
                        $profile_links = "";
                        for($j=0; $j<count($profile_ids); $j++){
                            $link = "http://roommates.edu.teiath.gr/profiles/view/{$profile_ids[$j]}";
                            $profile_links .= "<a href=\"{$link}\">{$link}</a><br />";
                        }
                
                        //var_dump($profile_links);
                        $email->to = $email_addr[$i];
                        $controller->set('house_links', $house_links);
                        $controller->set('profile_links', $profile_links);
                        $email->send();

                    } else {

                        $ids = $email_all[$email_addr[$i]]; 
                        $controller->set('count', count($ids));
                    
                        
                        $links = "";
                        for($j=0; $j<count($ids); $j++){
                            $link = "http://roommates.edu.teiath.gr/".$type."/view/{$ids[$j]}";
                            $links .= "<a href=\"{$link}\">{$link}</a><br />";
                        }
                        //var_dump($links);
                        $email->to = $email_addr[$i];
                        $controller->set('links', $links);
                        $email->send();
                    }
            } 
        }

}
?>
