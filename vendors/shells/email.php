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




        //get (only) users data and preferences
        $users = $this->Profile->find('all', array('conditions' => array( 'User.role' => 'user', 'Profile.get_mail' => 1)));

        //pr($houses);die(); //User, Preference, Profile
        
       // pr( $houses ); die();


        $email_users = $this->get_emails( $users );

        /*for ($i=0; $i<count($users); $i++){

            $compatible_houses = array();


            try{
                $compatible_houses = $this->get_prefered_records( $users, $i, 'House' );
            }catch( Exception $ex ){
                echo $ex->getMessage();
            }

            if (empty($compatible_houses)) {
                continue;
            }

            //filtering House ids
            $house_ids = array();
            foreach( $compatible_houses as $house ){
    
                    $house_ids[] = $house['House']['id'];
            }

            $email_users[$users[$i]['Profile']['email']] = $house_ids;

 
        }*/
            var_dump($email_users); die();
                   

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


        private function get_emails( $users ){
        
            
            $email_houses = array();
            $email_profiles = array();
            //email both wil have nested houses and profiles arrays
            $email_both = array();

             for ($i=0; $i<count($users); $i++){

                $compatible_houses = array();
                $compatible_profiles = array();

                try{
                    //Get arrays with Houses and Profiles 
                    $compatible_houses = $this->get_prefered_records( $users, $i, 'House' );
                    $compatible_profiles = $this->get_prefered_records( $users, $i, 'Profile' );
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
        //returns house ids
        private function get_prefered_houses( $users , $i ){
            $today = date('Y-m-d', strtotime("+1 day")); //because daysAsSql returns yesterday, use strtotime
            $from = $today;
            $to = $today;
            App::import('Helper', 'Time');
            $time = new TimeHelper();
            $conditions_created = $time->daysAsSql($from, $to, "created", true);
            $conditions_modified = $time->daysAsSql($from, $to, "modified", true);

           
            $house_conditions =  $this->getHousePrefs($users, $i );


            $mod_conditions = array(
                
                'House.user_id <>' => $users[$i]['User']['id'],
                'OR' => array(
                    $conditions_created,
                    $conditions_modified)
            );


            $conditions = array_merge( $house_conditions, $mod_conditions  );
            return $this->House->find('all', array( 'conditions' => $conditions, 'fields'=>'House.id', 'recursive'=>0));
        }
// a generic get_prfered_houses function scoped in profiles and houses
        private function get_prefered_records( $users , $i, $type ){
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

                            $this->getHousePrefs( $users, $i ),

                            array(  

                                'House.user_id <>' => $users[$i]['User']['id']
                            )
                        );
            } else if( $type === 'Profile' ) {

                $conditions = array_merge( 

                            $this->getProfilePrefs( $users, $i ),

                            array(  

                                'Profile.user_id <>' => $users[$i]['User']['id']
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

        private function getHousePrefs( $users ,$i ){

            //HousesController needs Controller class
            App::import('Core', 'Controller');
            App::import('Controller', 'Houses');
            $House = new HousesController;

            $user_prefs = array( 'max_price'=>$users[$i]['Preference']['price_max'],
                'max_area'=>$users[$i]['Preference']['area_max'],
                'min_area'=> $users[$i]['Preference']['area_min'], 
                'municipality'=>$users[$i]['Preference']['pref_municipality'],
                'bedroom_num_min'=>$users[$i]['Preference']['bedroom_num_min'],
                'furnitured'=>$users[$i]['Preference']['pref_furnitured'],
                'floor_min'=>$users[$i]['Preference']['floor_id_min'],
                'bathroom_num_min'=>$users[$i]['Preference']['bathroom_num_min'],
                'construction_year_min'=>$users[$i]['Preference']['construction_year_min'],
                'rent_period_min'=>$users[$i]['Preference']['rent_period_min'],
                'solar_heater'=>$users[$i]['Preference']['pref_solar_heater'],
                'aircondition'=>$users[$i]['Preference']['pref_aircondition'],
                'garden'=>$users[$i]['Preference']['pref_garden'],
                'parking'=>$users[$i]['Preference']['pref_parking'],
                'security_doors'=>$users[$i]['Preference']['pref_security_doors'],
                'storeroom'=>$users[$i]['Preference']['pref_storeroom'],
                'house_type'=>$users[$i]['Preference']['pref_house_type_id'],
                'heating_type'=>$users[$i]['Preference']['pref_heating_type_id'],
                'no_shared_pay'=>$users[$i]['Preference']['pref_shared_pay'],
                'availability_date_min'=>$users[$i]['Preference']['availability_date_min'],
                'accessibility'=>$users[$i]['Preference']['pref_disability_facilities'] );

            
            $house_conditions = $House->getHouseConditions( $user_prefs );
            return $house_conditions;

        }


        private function getProfilePrefs( $users ,$i ){

            //HousesController needs Controller class
            App::import('Core', 'Controller');
            App::import('Controller', 'Houses');
            $House = new HousesController;

            $user_prefs = array( 'min_age'=>$users[$i]['Preference']['age_min'],
                'max_age'=>$users[$i]['Preference']['age_max'],
                'gender'=> $users[$i]['Preference']['pref_gender'], 
                'smoker'=>$users[$i]['Preference']['pref_smoker'],
                'pet'=>$users[$i]['Preference']['pref_pet'],
                'child'=>$users[$i]['Preference']['pref_child'],
                'couple'=>$users[$i]['Preference']['pref_couple'] );

            
            $profile_conditions = $House->getMatesConditions( $user_prefs, false);
            return $profile_conditions;

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
                $email->layout = 'default';

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
